<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfYear = $now->copy()->startOfYear();

        $stats = [
            'total_clients' => Client::where('status', 'active')->count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'revenue_month' => Payment::where('payment_date', '>=', $startOfMonth)->sum('amount'),
            'revenue_year' => Payment::where('payment_date', '>=', $startOfYear)->sum('amount'),
            'overdue_invoices' => Invoice::where('status', 'unpaid')->where('due_date', '<', $now->toDateString())->count(),
        ];

        $upcomingRenewals = Subscription::with(['client', 'service'])
            ->where('status', 'active')
            ->where('next_billing_date', '>=', $now->toDateString())
            ->where('next_billing_date', '<=', $now->copy()->addDays(30)->toDateString())
            ->orderBy('next_billing_date')
            ->limit(5)
            ->get();

        $recentInvoices = Invoice::with('client')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'upcomingRenewals', 'recentInvoices'));
    }
}
