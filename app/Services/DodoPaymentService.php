<?php

namespace App\Services;

use App\Models\SaasPlan;
use App\Models\User;

class DodoPaymentService
{
    protected $client;

    public function __construct()
    {
        $env = config('services.dodo.environment');
        $baseUrl = ($env === 'test' || $env === 'test_mode')
            ? 'https://test.dodopayments.com' 
            : 'https://live.dodopayments.com';
            
        \Log::info("Dodo initialized in [{$env}] mode with URL: {$baseUrl}");

        $this->client = new \Dodopayments\Client(
            bearerToken: config('services.dodo.api_key'),
            baseUrl: $baseUrl
        );
    }

    public function createCheckoutSession(User $user, SaasPlan $plan)
    {
        try {
            $response = $this->client->checkoutSessions->create(
                productCart: [
                    [
                        'productID' => $plan->dodo_product_id,
                        'quantity' => 1,
                    ]
                ],
                customer: [
                    'email' => $user->email,
                    'name' => $user->name,
                ],
                returnURL: route('dashboard'),
            );

            return $response;
        } catch (\Exception $e) {
            \Log::error('Dodo Payment Error: ' . $e->getMessage());
            return null;
        }
    }

    public function fetchProducts()
    {
        try {
            // Fetch products from Dodo Payments
            return $this->client->products->list();
        } catch (\Exception $e) {
            \Log::error('Dodo Sync Error: ' . $e->getMessage());
            return collect();
        }
    }
}
