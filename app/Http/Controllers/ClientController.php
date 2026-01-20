<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $clients = $query->withCount([
            'subscriptions' => function ($q) {
                $q->where('status', 'active');
            }
        ])->withSum('invoices', 'total')->latest()->paginate(10)->withQueryString();

        $stats = [
            'total_active' => Client::where('status', 'active')->count(),
            'new_this_month' => Client::whereMonth('created_at', now()->month)->count(),
            'retention_rate' => 98.2, // Mocked for UI demonstration
        ];

        return view('clients.index', compact('clients', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = \App\Models\Service::all();
        $billingCycles = \App\Models\BillingCycle::all();
        return view('clients.create', compact('services', 'billingCycles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string',
            // Service assignment fields (optional)
            'service_id' => 'nullable|exists:services,id',
            'billing_cycle_id' => 'nullable|exists:billing_cycles,id',
            'start_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $client = Client::create(\Illuminate\Support\Arr::except($validated, ['service_id', 'billing_cycle_id', 'start_date', 'price']));

            if (!empty($validated['service_id'])) {
                $client->subscriptions()->create([
                    'service_id' => $validated['service_id'],
                    'billing_cycle_id' => $validated['billing_cycle_id'],
                    'start_date' => $validated['start_date'] ?? now(),
                    'next_billing_date' => $validated['start_date'] ?? now(),
                    'price' => $validated['price'],
                    'status' => 'active',
                    'auto_renewal' => true,
                ]);
            }
        });

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load([
            'subscriptions.service',
            'subscriptions.billingCycle',
            'invoices.items',
            'invoices.payments',
            'notifications' => function ($q) {
                $q->latest()->limit(5);
            }
        ]);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
