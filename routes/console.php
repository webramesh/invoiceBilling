<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule Invoice Generation
\Illuminate\Support\Facades\Schedule::command('invoices:generate')
    ->daily()
    ->at('01:00')
    ->emailOutputOnFailure(config('mail.from.address')); // Optional: monitor failures
