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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('hash')->nullable()->unique()->after('invoice_number');
        });

        // Initialize hash for existing invoices
        \App\Models\Invoice::whereNull('hash')->get()->each(function ($invoice) {
            $invoice->hash = \Illuminate\Support\Str::random(12);
            $invoice->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('hash');
        });
    }
};
