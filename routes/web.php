<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PartnerProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublicOrderController;

// Page 1: The Landing Page (/laundryapp)
Route::redirect('/', '/laundryapp');
Route::get('/laundryapp', function () {
    return view('welcome');
})->name('public.landing');

// Page 2: How It Works (/instructions)
Route::get('/instructions', function () {
    return view('public.instructions');
});

// The Portal
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/portal', function () {
        $user = auth()->user();
        
        // If partner, see all company orders. Otherwise see personal orders.
        $query = $user->isPartner() 
            ? \App\Models\Transaction::where('partner_id', $user->partner_id)
            : $user->transactions();

        $activeTransactions = (clone $query)
            ->where('laundry_status', '!=', 'Completed')
            ->latest()
            ->get();
            
        $archivedTransactions = (clone $query)
            ->where('laundry_status', 'Completed')
            ->latest()
            ->get();

        return view('dashboard', compact('activeTransactions', 'archivedTransactions'));
    })->name('dashboard');

    // Page 3: The Order Portal (/order)
    Route::get('/order', [OrderController::class, 'create'])->name('public.order.create');
    Route::post('/order', [OrderController::class, 'store'])->name('public.order.store');

    // Success Page
    Route::get('/order/success/{invoiceCode}', [OrderController::class, 'success'])->name('public.order.success');
    
    // Portal Tracker
    Route::get('/portal/track/{id}', [OrderController::class, 'portalTrack'])->name('portal.track');

    // Demo Advancement API
    Route::post('/api/demo/advance-status/{id}', [OrderController::class, 'advanceStatus'])->name('demo.advance-status');
    
    // Review Submission
    Route::post('/order/review', [OrderController::class, 'storeReview'])->name('public.order.review');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Corporate Partner Profile
    Route::get('/partner/profile', [PartnerProfileController::class, 'index'])->name('partner.profile');
});

require __DIR__.'/auth.php';

// Keeping existing tracking functionality for compatibility
Route::get('/track', [PublicOrderController::class, 'trackForm'])->name('public.track.form');
Route::get('/track/{invoiceCode}', [PublicOrderController::class, 'trackShow'])->name('public.track.show');

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');
