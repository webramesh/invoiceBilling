<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Seed default reminder settings
        $defaultSettings = [
            'reminder_1_days' => 30,
            'reminder_2_days' => 15,
            'reminder_3_days' => 5,
            'reminder_final_days' => 1,
            'reminder_1_enabled' => true,
            'reminder_2_enabled' => true,
            'reminder_3_enabled' => true,
            'reminder_final_enabled' => true,
            'grace_period_days' => 3,
            'reminder_email_subject' => 'Upcoming Invoice Reminder - {service_name}',
            'reminder_email_body' => 'Dear {client_name},

This is a friendly reminder that your invoice for {service_name} will be due in {days_until_due} days.

Invoice Amount: {amount}
Due Date: {due_date}

Please ensure timely payment to avoid any service interruption.

Thank you for your business!',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : $value]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            'reminder_1_days',
            'reminder_2_days',
            'reminder_3_days',
            'reminder_final_days',
            'reminder_1_enabled',
            'reminder_2_enabled',
            'reminder_3_enabled',
            'reminder_final_enabled',
            'grace_period_days',
            'reminder_email_subject',
            'reminder_email_body',
        ];

        Setting::whereIn('key', $keys)->delete();
    }
};
