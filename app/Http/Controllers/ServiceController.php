<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::with('category')->withCount([
            'subscriptions as active_clients_count' => function ($query) {
                $query->where('status', 'active');
            }
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($catQuery) use ($search) {
                        $catQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $services = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total_services' => Service::count(),
            'services_this_month' => Service::whereMonth('created_at', now()->month)->count(),
            'active_subscriptions' => \App\Models\Subscription::where('status', 'active')->count(),
            'total_revenue' => \App\Models\Subscription::where('status', 'active')->sum('price'),
        ];

        return view('services.index', compact('services', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ServiceCategory::all();
        $billingCycles = \App\Models\BillingCycle::all();
        return view('services.create', compact('categories', 'billingCycles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'tax_status' => 'required|string',
            'tax_rate' => 'nullable|numeric|min:0',
            'billing_options' => 'nullable|array',
            'is_draft' => 'nullable',
        ]);

        $validated['is_draft'] = $request->has('is_draft');
        $validated['status'] = $validated['status'] ?? 'active';
        $validated['tax_rate'] = $validated['tax_rate'] ?? 0;

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $categories = ServiceCategory::all();
        $billingCycles = \App\Models\BillingCycle::all();
        return view('services.edit', compact('service', 'categories', 'billingCycles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'tax_status' => 'required|string',
            'tax_rate' => 'nullable|numeric|min:0',
            'billing_options' => 'nullable|array',
            'is_draft' => 'nullable',
        ]);

        $validated['is_draft'] = $request->has('is_draft');
        $validated['tax_rate'] = $validated['tax_rate'] ?? 0;

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        if ($service->subscriptions()->count() > 0) {
            return back()->with('error', 'Cannot delete service with active subscriptions.');
        }

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
