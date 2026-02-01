<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SaasPlan;

class DodoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $header = $request->header('x-dodo-signature');
        
        // In production, we must verify this signature using config('services.dodo.webhook_key')
        // For the demo, we process the event directly
        
        $type = $payload['type'] ?? '';
        $data = $payload['data'] ?? [];

        if ($type === 'subscription.created' || $type === 'subscription.renewed') {
            $customerEmail = $data['customer']['email'] ?? null;
            $productId = $data['product_id'] ?? null;
            
            if ($customerEmail && $productId) {
                $user = User::where('email', $customerEmail)->first();
                $plan = SaasPlan::where('dodo_product_id', $productId)->first();
                
                if ($user && $plan) {
                    $user->update([
                        'saas_plan_id' => $plan->id,
                        'plan_expires_at' => now()->addMonth(),
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
