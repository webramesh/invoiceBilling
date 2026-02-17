<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Enable/disable reminders per subscription
            $table->boolean('reminders_enabled')->default(true)->after('email_notifications');
            
            // Track last sent reminders (JSON) - prevents duplicate sends
            $table->json('last_reminders_sent')->nullable()->after('reminders_enabled');
            
            // Custom reminder overrides (JSON) - optional per-subscription customization
            $table->json('custom_reminder_days')->nullable()->after('last_reminders_sent');
            
            // Add composite index for efficient reminder queries
            $table->index(['next_billing_date', 'status', 'reminders_enabled'], 'idx_reminder_lookup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('idx_reminder_lookup');
            $table->dropColumn([
                'reminders_enabled',
                'last_reminders_sent',
                'custom_reminder_days',
            ]);
        });
    }
};
