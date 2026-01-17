<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Dynamic Mail Configuration from Settings Table
        if (app()->environment() !== 'testing' && \Illuminate\Support\Facades\Schema::hasTable('settings')) {
            $settings = \App\Models\Setting::all()->pluck('value', 'key');

            if ($settings->has('mail_mailer')) {
                config([
                    'mail.mailers.smtp.host' => $settings->get('mail_host'),
                    'mail.mailers.smtp.port' => $settings->get('mail_port'),
                    'mail.mailers.smtp.encryption' => $settings->get('mail_encryption'),
                    'mail.mailers.smtp.username' => $settings->get('mail_username'),
                    'mail.mailers.smtp.password' => $settings->get('mail_password'),
                    'mail.from.address' => $settings->get('mail_from_address'),
                    'mail.from.name' => $settings->get('mail_from_name'),
                ]);
            }
        }
    }
}
