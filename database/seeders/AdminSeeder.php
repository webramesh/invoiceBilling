<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SaasPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Plans Exist (Required for assigning plan_id)
        $this->call(SaasPlanSeeder::class);

        // 2. Create the Super Admin (SaaS Owner)
        // This user manages the platform itself (plans, users, etc.)
        $adminDetails = [
            'name' => 'Super Admin',
            'email' => 'webdramesh@gmail.com',
            'password' => Hash::make('Doordie@*#12'),
            'is_admin' => true, // Flag for super admin access
            'email_verified_at' => now(),
        ];

        // Using updateOrCreate to avoid duplicates gracefully
        $admin = User::updateOrCreate(
            ['email' => $adminDetails['email']],
            $adminDetails
        );

        $this->command->info('Created Super Admin: webdramesh@gmail.com / Doordie@*#12');

        // 3. (Optional) Tenant creation removed as requested.
        // We will register tenants manually later.
    }
}
