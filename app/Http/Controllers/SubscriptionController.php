<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Client;
use App\Models\Service;
use App\Models\BillingCycle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\InvoiceService;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subscription::with(['client', 'service', 'billingCycle']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('client', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('service', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('cycle')) {
            $query->whereHas('billingCycle', function ($q) use ($request) {
                $q->where('name', $request->input('cycle'));
            });
        }

        $subscriptions = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total_mrr' => Subscription::where('status', 'active')->sum(\Illuminate\Support\Facades\DB::raw('price * COALESCE(quantity, 1)')),
            'renewals_this_month' => Subscription::whereMonth('next_billing_date', now()->month)->count(),
            'active_services_count' => Subscription::where('status', 'active')->count(),
            'unique_clients_count' => Subscription::distinct('client_id')->count(),
        ];

        return view('subscriptions.index', compact('subscriptions', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clients = Client::all();
        $services = Service::where('status', 'active')->get();
        $billingCycles = BillingCycle::all();
        $selectedClient = $request->input('client_id');

        return view('subscriptions.create', compact('clients', 'services', 'billingCycles', 'selectedClient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, InvoiceService $invoiceService, \App\Services\NotificationService $notificationService)
    {
        // Enforce Plan Limits for Invoices
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && $user->saasPlan && $user->saasPlan->max_invoices_per_month != -1) {
            $currentMonthInvoices = $user->invoices()->whereMonth('created_at', now()->month)->count();
            if ($currentMonthInvoices >= $user->saasPlan->max_invoices_per_month) {
                return back()->with('error', "You have reached the monthly invoice limit ({$user->saasPlan->max_invoices_per_month}). Please upgrade to continue.");
            }
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'billing_cycle_id' => 'required|exists:billing_cycles,id',
            'start_date' => 'required|date',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:1',
            'service_alias' => 'nullable|string|max:255',
            'auto_renewal' => 'nullable',
        ]);

        try {
            $service = Service::findOrFail($validated['service_id']);
            $billingCycle = BillingCycle::findOrFail($validated['billing_cycle_id']);

            if (empty($validated['price']) && $validated['price'] !== '0' && $validated['price'] !== 0) {
                $validated['price'] = $service->base_price;
            }
            
            // Set next billing date to start date so the first invoice is generated immediately
            // The generation service will then advance it by one cycle
            $startDate = Carbon::parse($validated['start_date']);
            $validated['next_billing_date'] = $startDate;
            
            $validated['status'] = 'active';
            $validated['auto_renewal'] = $request->has('auto_renewal');
            $validated['quantity'] = $request->input('quantity', 1);
            $validated['service_alias'] = $request->input('service_alias');
            $validated['whatsapp_notifications'] = $request->has('whatsapp_notifications');
            $validated['email_notifications'] = $request->has('email_notifications');

            $subscription = Subscription::create($validated);
            
            // Generate First Invoice
            $invoice = $invoiceService->generateForSubscription($subscription);
            
            // Send Notifications
            $channels = [];
            if ($subscription->email_notifications) $channels[] = 'email';
            if ($subscription->whatsapp_notifications && $user->saasPlan->has_whatsapp) $channels[] = 'whatsapp';
            
            if (!empty($channels)) {
                $notificationService->sendInvoice($invoice, $channels);
            }

            return redirect()->route('subscriptions.index')->with('success', 'Subscription created, invoice generated and sent.');
        } catch (\Exception $e) {
            \Log::error('Subscription creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error assigning service: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        $subscription->load(['client', 'service', 'billingCycle', 'invoices']);
        return view('subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        $clients = Client::all();
        $services = Service::where('status', 'active')->get();
        $billingCycles = BillingCycle::all();

        return view('subscriptions.edit', compact('subscription', 'clients', 'services', 'billingCycles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'billing_cycle_id' => 'required|exists:billing_cycles,id',
            'start_date' => 'required|date',
            'next_billing_date' => 'required|date',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:1',
            'service_alias' => 'nullable|string|max:255',
            'status' => 'required|in:active,suspended,cancelled',
        ]);

        try {
            $service = Service::findOrFail($validated['service_id']);
            if (empty($validated['price']) && $validated['price'] !== '0' && $validated['price'] !== 0) {
                $validated['price'] = $service->base_price;
            }

            $validated['auto_renewal'] = $request->has('auto_renewal');
            $validated['quantity'] = $request->input('quantity', 1);
            $validated['service_alias'] = $request->input('service_alias');
            $validated['whatsapp_notifications'] = $request->has('whatsapp_notifications');
            $validated['email_notifications'] = $request->has('email_notifications');

            $subscription->update($validated);

            return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Subscription update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        if ($subscription->invoices()->count() > 0) {
            return back()->with('error', 'Cannot delete subscription with existing invoices.');
        }

        $subscription->delete();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }

    /**
     * Generate an invoice for a subscription on-demand.
     */
    public function generateInvoice(Subscription $subscription, InvoiceService $invoiceService)
    {
        if ($subscription->status !== 'active') {
            return back()->with('error', 'Only active subscriptions can generate invoices.');
        }

        try {
            $invoice = $invoiceService->generateForSubscription($subscription);

            // Send email notification if enabled (best-effort)
            if ($subscription->email_notifications && $subscription->client && $subscription->client->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($subscription->client->email)->send(new \App\Mail\InvoiceMailable($invoice));
                } catch (\Exception $e) {
                    \Log::error('Invoice email failed: ' . $e->getMessage());
                }
            }

            return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice generated: ' . $invoice->invoice_number);
        } catch (\Exception $e) {
            \Log::error('Invoice generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate invoice: ' . $e->getMessage());
        }
    }
}
