<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BillingCycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\BillingCycle::create(['name' => 'Monthly', 'months' => 1, 'discount_percentage' => 0]);
        \App\Models\BillingCycle::create(['name' => 'Quarterly', 'months' => 3, 'discount_percentage' => 5]);
        \App\Models\BillingCycle::create(['name' => 'Semi-Annual', 'months' => 6, 'discount_percentage' => 10]);
        \App\Models\BillingCycle::create(['name' => 'Annual', 'months' => 12, 'discount_percentage' => 15]);
    }
}
