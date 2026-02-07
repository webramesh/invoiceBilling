<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BusinessOwnerController extends Controller
{
    public function index()
    {
        $query = User::where('is_admin', false)->with('saasPlan');

        // Apply search filter
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $businessOwners = $query->latest()->paginate(20);

        return view('superadmin.business-owners.index', compact('businessOwners'));
    }

    public function show(User $businessOwner)
    {
        // Ensure we're only viewing business owners, not admins
        if ($businessOwner->is_admin) {
            abort(404);
        }

        $businessOwner->load('saasPlan', 'clients', 'subscriptions');

        $stats = [
            'total_clients' => $businessOwner->clients()->count(),
            'active_subscriptions' => $businessOwner->subscriptions()->where('status', 'active')->count(),
            'total_revenue' => $businessOwner->clients()->sum('total_revenue'),
        ];

        return view('superadmin.business-owners.show', compact('businessOwner', 'stats'));
    }
}
