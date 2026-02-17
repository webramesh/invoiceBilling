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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Tenant tracking
            $table->string('type'); // 'reminder_1', 'reminder_2', 'reminder_3', 'reminder_final', 'overdue'
            $table->string('channel'); // 'email', 'whatsapp'
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable(); // Error details if failed
            $table->timestamp('sent_at')->nullable(); // When successfully sent
            $table->timestamps();
            
            // Efficient indexes for queries and cleanup
            $table->index(['subscription_id', 'type', 'sent_at'], 'idx_notification_lookup');
            $table->index(['created_at'], 'idx_notification_cleanup'); // For monthly cleanup
            $table->index(['status', 'created_at'], 'idx_notification_status'); // For retry logic
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
