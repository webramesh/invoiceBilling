<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SaasPlan;
use App\Models\Invoice;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Platform Stats
        $stats = [
            'total_tenants' => User::where('is_admin', false)->count(),
            'active_tenants' => User::where('is_admin', false)->where('plan_expires_at', '>', now())->count(),
            'monthly_recurring_revenue' => DB::table('users')
                ->join('saas_plans', 'users.saas_plan_id', '=', 'saas_plans.id')
                ->where('users.plan_expires_at', '>', now())
                ->sum('saas_plans.price'),
            'total_platform_invoices' => Invoice::withoutGlobalScope('tenant')->count(),
        ];

        $recentTenants = User::where('is_admin', false)
            ->with('saasPlan')
            ->latest()
            ->limit(10)
            ->get();

        $plansDistribution = SaasPlan::withCount('users as users_count')->get();

        return view('superadmin.dashboard', compact('stats', 'recentTenants', 'plansDistribution'));
    }
}
