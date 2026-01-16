<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ServiceCategory::create(['name' => 'Domain', 'description' => 'Domain registration and renewal', 'icon' => 'globe-alt']);
        \App\Models\ServiceCategory::create(['name' => 'Shared Hosting', 'description' => 'Shared web hosting services', 'icon' => 'server']);
        \App\Models\ServiceCategory::create(['name' => 'VPS', 'description' => 'Virtual Private Servers', 'icon' => 'cpu-chip']);
        \App\Models\ServiceCategory::create(['name' => 'Business Email', 'description' => 'Professional email accounts', 'icon' => 'envelope']);
    }
}
