<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaasPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Trial',
                'slug' => 'free',
                'dodo_product_id' => null,
                'price' => 0.00,
                'max_clients' => 5,
                'max_invoices_per_month' => 10,
                'has_whatsapp' => false,
                'updated_at' => now(),
            ],
            [
                'name' => 'Basic Plan',
                'slug' => 'basic',
                'dodo_product_id' => 'pdp_123_basic',
                'price' => 499.00,
                'max_clients' => 50,
                'max_invoices_per_month' => 100,
                'has_whatsapp' => true,
                'updated_at' => now(),
            ],
            [
                'name' => 'Unlimited Pro',
                'slug' => 'pro',
                'dodo_product_id' => 'pdp_456_pro',
                'price' => 999.00,
                'max_clients' => -1,
                'max_invoices_per_month' => -1,
                'has_whatsapp' => true,
                'updated_at' => now(),
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('saas_plans')->updateOrInsert(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
