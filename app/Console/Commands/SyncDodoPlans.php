<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DodoPaymentService;
use App\Models\SaasPlan;

class SyncDodoPlans extends Command
{
    protected $signature = 'dodo:sync';
    protected $description = 'Sync local SaasPlans with products from Dodo Payments';

    public function handle(DodoPaymentService $dodo)
    {
        $this->info('Fetching products from Dodo Payments...');
        $dodoProducts = $dodo->fetchProducts();

        if (empty($dodoProducts->items)) {
            $this->error('No products found in your Dodo account (check your API key and environment).');
            return;
        }

        $localPlans = SaasPlan::all();
        
        $this->table(
            ['Local Plan', 'Current Dodo ID', 'Recommended Match'],
            $localPlans->map(function($plan) use ($dodoProducts) {
                $match = collect($dodoProducts->items)->first(function($p) use ($plan) {
                    return strpos(strtolower($p->name), strtolower($plan->name)) !== false;
                });

                return [
                    $plan->name,
                    $plan->dodo_product_id ?: 'Empty',
                    $match ? "✅ {$match->name} ({$match->productID})" : '❌ No Match found'
                ];
            })
        );

        $this->newLine();
        $this->info('Available Products in your Dodo Account:');
        $this->table(
            ['Remote Name', 'Remote Product ID'],
            collect($dodoProducts->items)->map(fn($p) => [$p->name, $p->productID])
        );

        if ($this->confirm('Perform auto-sync based on name match?')) {
            foreach ($localPlans as $plan) {
                $match = collect($dodoProducts->items)->first(function($p) use ($plan) {
                    return strtolower($p->name) === strtolower($plan->name);
                });

                if ($match) {
                    $plan->update(['dodo_product_id' => $match->productID]);
                    $this->info("Updated {$plan->name} with ID: {$match->productID}");
                }
            }
            $this->info('Sync complete.');
        } else {
            $this->warn('Sync aborted. You can manually set the IDs in your DB.');
        }
    }
}
