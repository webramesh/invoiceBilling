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
        Schema::table('services', function (Blueprint $table) {
            $table->string('tax_status')->default('standard')->after('status');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('tax_status');
            $table->json('billing_options')->nullable()->after('tax_rate');
            $table->boolean('is_draft')->default(false)->after('billing_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['tax_status', 'tax_rate', 'billing_options', 'is_draft']);
        });
    }
};
