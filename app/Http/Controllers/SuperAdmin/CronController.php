<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class CronController extends Controller
{
    public function index()
    {
        // Check heartbeat (updated by scheduled task)
        $lastRun = Cache::get('cron_heartbeat');
        // Consider running if last run was within 5 minutes
        $isRunning = $lastRun && $lastRun->diffInMinutes(now()) < 5;
        
        $phpBinary = PHP_BINARY;
        $basePath = base_path();
        
        // Command to run (using php binary)
        $cronCommand = "* * * * * {$phpBinary} {$basePath}/artisan schedule:run >> /dev/null 2>&1";
        
        return view('superadmin.cron.status', compact('isRunning', 'lastRun', 'cronCommand'));
    }
}
