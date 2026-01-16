<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Client::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'company' => 'Doe Inc',
            'whatsapp_number' => '1234567890',
            'status' => 'active',
            'address' => '123 Main St, New York, NY'
        ]);
        \App\Models\Client::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '0987654321',
            'company' => 'Smith Co',
            'whatsapp_number' => '0987654321',
            'status' => 'active',
            'address' => '456 Elm St, San Francisco, CA'
        ]);
    }
}
