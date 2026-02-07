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
        Schema::create('saas_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 8, 2);
            $table->integer('max_clients')->default(-1); // -1 for unlimited
            $table->integer('max_invoices_per_month')->default(-1);
            $table->boolean('has_whatsapp')->default(false);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('saas_plan_id')->nullable()->after('password')->constrained('saas_plans');
            $table->timestamp('plan_expires_at')->nullable()->after('saas_plan_id');
            $table->boolean('is_admin')->default(false)->after('plan_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['saas_plan_id']);
            $table->dropColumn(['saas_plan_id', 'plan_expires_at', 'is_admin']);
        });
        Schema::dropIfExists('saas_plans');
    }
};
