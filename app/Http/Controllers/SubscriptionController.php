<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Client;
use App\Models\Service;
use App\Models\BillingCycle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = Subscription::with(['client', 'service', 'billingCycle'])->latest()->paginate(10);
        return view('subscriptions.index', compact('subscriptions'));
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'billing_cycle_id' => 'required|exists:billing_cycles,id',
            'start_date' => 'required|date',
            'price' => 'nullable|numeric|min:0',
            'auto_renewal' => 'nullable',
        ]);

        try {
            $service = Service::findOrFail($validated['service_id']);
            $billingCycle = BillingCycle::findOrFail($validated['billing_cycle_id']);

            if (empty($validated['price']) && $validated['price'] !== '0' && $validated['price'] !== 0) {
                $validated['price'] = $service->base_price;
            }

            $startDate = Carbon::parse($validated['start_date']);
            $validated['next_billing_date'] = $startDate->copy()->addMonths($billingCycle->months);
            $validated['status'] = 'active';
            $validated['auto_renewal'] = $request->has('auto_renewal');

            Subscription::create($validated);

            return redirect()->route('subscriptions.index')->with('success', 'Service assigned to client successfully.');
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
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,suspended,cancelled',
            'auto_renewal' => 'nullable|boolean',
        ]);

        $validated['auto_renewal'] = $request->has('auto_renewal');
        $subscription->update($validated);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
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
}
