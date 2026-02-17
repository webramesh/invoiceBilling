<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Cache;

// Cron Heartbeat (Every Minute)
Schedule::call(function () {
    Cache::put('cron_heartbeat', now(), 600);
})->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule Invoice Generation
Schedule::command('invoices:generate')
    ->daily()
    ->at('01:00')
    ->emailOutputOnFailure(config('mail.from.address')); // Optional: monitor failures

// Schedule Invoice Reminders (optimized for shared hosting)
Schedule::command('invoice-reminders:send --limit=100')
    ->dailyAt('09:00') // Run at 9 AM when server load is typically lower
    ->withoutOverlapping(30) // Prevent concurrent runs, timeout after 30 minutes
    ->runInBackground()
    ->emailOutputOnFailure(config('mail.from.address'));

// Process Queue Jobs (for shared hosting without dedicated queue worker)
// Runs every 5 minutes to process pending reminder emails
Schedule::command('queue:work database --stop-when-empty --max-time=240 --tries=3 --queue=reminders,default')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();

// Cleanup old notification logs (monthly)
Schedule::call(function () {
    \App\Models\NotificationLog::where('created_at', '<', now()->subMonths(3))->delete();
})->monthly()->at('02:00');
