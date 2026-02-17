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
        Schema::create('subscription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "john@company.com", "SSL Certificate"
            $table->text('description')->nullable(); // Additional details
            $table->integer('quantity')->default(1); // Quantity of this item
            $table->decimal('unit_price', 10, 2); // Price per unit
            $table->json('metadata')->nullable(); // Flexible custom fields (e.g., {"expiry": "2026-12-31"})
            $table->integer('sort_order')->default(0); // Display order
            $table->timestamps();
            
            // Composite index for fast lookups
            $table->index(['subscription_id', 'sort_order'], 'idx_subscription_items_lookup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_items');
    }
};
