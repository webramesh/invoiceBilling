<?php

namespace App\Services;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class MailConfigService
{
    /**
     * Configure the mailer for a specific user (tenant).
     *
     * @param User $user
     * @return void
     */
    public static function configureMail(User $user)
    {
        // Settings are stored with keys like 'mail_host', 'mail_port', etc.
        // We retrieve them using the Setting model which is scoped to the user via BelongsToTenant trait.
        // However, we need to bypass the global scope if we are running in a command context where no user is logged in.
        // The Setting::get() method uses `self::where(...)` which applies the global scope if Auth::check() is true.
        // If Auth::check() is false (console), we cannot easily use Setting::get() for a specific user without logging them in or bypassing scope.
        
        // Let's manually query settings for this user.
        $settings = Setting::withoutGlobalScope('tenant')
            ->where('user_id', $user->id)
            ->whereIn('key', [
                'mail_mailer',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_from_address',
                'mail_from_name',
            ])
            ->pluck('value', 'key');

        if ($settings->isEmpty()) {
            return; // Use default system config
        }

        // Only override if values are present.
        if (isset($settings['mail_host']) && !empty($settings['mail_host'])) {
            $config = [
                'transport' => $settings['mail_mailer'] ?? 'smtp',
                'host' => $settings['mail_host'],
                'port' => $settings['mail_port'],
                'encryption' => $settings['mail_encryption'],
                'username' => $settings['mail_username'],
                'password' => $settings['mail_password'],
                'timeout' => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ];

            Config::set('mail.mailers.smtp', $config);
            Config::set('mail.default', 'smtp'); // Force SMTP just in case

            if (isset($settings['mail_from_address'])) {
                Config::set('mail.from.address', $settings['mail_from_address']);
            }
            if (isset($settings['mail_from_name'])) {
                Config::set('mail.from.name', $settings['mail_from_name']);
            }
            
            // Purge the transport so it re-initializes with new config.
            // This is crucial for switching configs in a loop.
            try {
                app('mailer')->setSymfonyTransport(app('mail.manager')->createSymfonyTransport($config));
            } catch (\Exception $e) {
                // In some Laravel versions or setups, specific handling might be needed.
                // Re-forgetting the mailer instance is often safer.
                (new \Illuminate\Mail\MailServiceProvider(app()))->register();
            }
        }
    }
}
