<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'clients',
            'service_categories',
            'services',
            'subscriptions',
            'invoices',
            'payments',
            'notifications',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->nullable()->constrained()->onDelete('cascade');
            });
        }

        // Special handling for settings table
        Schema::table('settings', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->onDelete('cascade');
            $table->dropUnique('settings_key_unique');
            $table->unique(['user_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'key']);
            $table->unique('key');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        $tables = [
            'notifications',
            'payments',
            'invoices',
            'subscriptions',
            'services',
            'service_categories',
            'clients',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
