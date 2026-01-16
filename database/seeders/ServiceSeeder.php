<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hosting = \App\Models\ServiceCategory::where('name', 'Shared Hosting')->first();
        $domain = \App\Models\ServiceCategory::where('name', 'Domain')->first();

        \App\Models\Service::create([
            'service_category_id' => $hosting->id,
            'name' => 'Basic Hosting',
            'description' => '1GB Storage, 10GB Bandwidth',
            'base_price' => 500,
            'status' => 'active'
        ]);

        \App\Models\Service::create([
            'service_category_id' => $domain->id,
            'name' => '.com Registration',
            'description' => '.com domain registration',
            'base_price' => 1200,
            'status' => 'active'
        ]);
    }
}
