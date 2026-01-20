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
        // Modify the enum to include 'unpaid'
        // Using raw statement because Laravel's Blueprint doesn't support altering enum values.
        if (config('database.default') === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `invoices` MODIFY `status` ENUM('draft','sent','paid','overdue','cancelled','unpaid') NOT NULL DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `invoices` MODIFY `status` ENUM('draft','sent','paid','overdue','cancelled') NOT NULL DEFAULT 'draft'");
        }
    }
};
