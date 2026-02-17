<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingController;

// Public Portal Routes
Route::get('portal/{hash}', [\App\Http\Controllers\PublicClientPortalController::class, 'show'])->name('portal.show');

Route::get('/', function () {
    return redirect()->route('login');
});

// Public Invoice Routes
Route::get('i/{hash}', [\App\Http\Controllers\PublicInvoiceController::class, 'show'])->name('invoices.public.show');
Route::get('i/{hash}/download', [\App\Http\Controllers\PublicInvoiceController::class, 'downloadPdf'])->name('invoices.public.download');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // SuperAdmin Routes
    Route::middleware(['superadmin'])->group(function () {
        Route::get('/superadmin', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('superadmin.dashboard');
        Route::get('/superadmin/business-owners', [\App\Http\Controllers\SuperAdmin\BusinessOwnerController::class, 'index'])->name('superadmin.business-owners.index');
        Route::get('/superadmin/business-owners/{businessOwner}', [\App\Http\Controllers\SuperAdmin\BusinessOwnerController::class, 'show'])->name('superadmin.business-owners.show');
    });

    Route::get('/pricing', function() {
        return view('pricing', ['plans' => \App\Models\SaasPlan::all()]);
    })->name('pricing');

    Route::post('/pricing/subscribe/{plan}', function(\App\Models\SaasPlan $plan, \App\Services\DodoPaymentService $dodo) {
        $user = auth()->user();
        
        // If it's a free plan (price = 0), assign it directly
        if ($plan->price == 0) {
            $user->update([
                'saas_plan_id' => $plan->id,
                'plan_expires_at' => now()->addYear(),
            ]);
            
            return redirect()->route('pricing')->with('success', "Successfully subscribed to {$plan->name} plan!");
        }
        
        // For paid plans, create checkout session
        $session = $dodo->createCheckoutSession($user, $plan);
        
        if ($session && isset($session->checkoutURL)) {
            return redirect()->away($session->checkoutURL);
        }

        return back()->with('error', 'Could not initiate payment. Please try again or contact support.');
    })->name('pricing.subscribe');

    // Webhook - Exclude from CSRF (must handle in VerifyCsrfToken/Middleware)
    Route::post('/webhooks/dodo', [DodoWebhookController::class, 'handle'])->name('webhooks.dodo');

    Route::middleware(['plan.active'])->group(function () {
        Route::resource('clients', ClientController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('service-categories', ServiceCategoryController::class);
        Route::resource('subscriptions', SubscriptionController::class);
        Route::post('subscriptions/{subscription}/generate-invoice', [\App\Http\Controllers\SubscriptionController::class, 'generateInvoice'])->name('subscriptions.generate-invoice');
        Route::resource('invoices', InvoiceController::class);
        Route::post('invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-as-paid');
        Route::get('invoices/{invoice}/download', [InvoiceController::class, 'downloadPdf'])->name('invoices.download');
        Route::post('invoices/{invoice}/send-email', [InvoiceController::class, 'sendEmail'])->name('invoices.send-email');
        Route::post('invoices/{invoice}/send-whatsapp', [InvoiceController::class, 'sendWhatsApp'])->name('invoices.send-whatsapp');

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/test-email', [SettingController::class, 'testEmail'])->name('settings.test-email');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';