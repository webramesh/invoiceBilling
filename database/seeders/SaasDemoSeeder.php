<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SaasPlan;

class SaasDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Super Admin
        User::updateOrCreate(
            ['email' => 'admin@invoice.com'],
            [
                'name' => 'SAAS Master',
                'password' => bcrypt('admin123'),
                'is_admin' => true,
            ]
        );

        // 2. Create some Tenants (Business Owners)
        $plans = SaasPlan::all();
        
        $tenants = [
            ['name' => 'Tech Solutions Inc', 'email' => 'owner@tech.com'],
            ['name' => 'Creative Agency', 'email' => 'hello@creative.com'],
            ['name' => 'Solo Freelancer', 'email' => 'me@freelance.com'],
        ];

        foreach ($tenants as $index => $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                    'is_admin' => false,
                    'saas_plan_id' => $plans->random()->id,
                    'plan_expires_at' => now()->addDays(rand(1, 30)),
                ]
            );
        }
    }
}
