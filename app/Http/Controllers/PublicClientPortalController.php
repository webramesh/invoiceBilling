<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class PublicClientPortalController extends Controller
{
    public function show($hash)
    {
        $client = Client::withoutGlobalScope('tenant')
            ->where('portal_hash', $hash)
            ->firstOrFail();

        // Use withoutGlobalScope for relationships too if needed, but standard child models 
        // usually need to be fetched without global scope if we want to show them publicly.
        $invoices = $client->invoices()->withoutGlobalScope('tenant')->latest()->get();
        $subscriptions = $client->subscriptions()->withoutGlobalScope('tenant')->with('service')->get();

        return view('portal.show', compact('client', 'invoices', 'subscriptions'));
    }
}
