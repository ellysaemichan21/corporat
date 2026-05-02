<?php

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

// Page 3: The Public Order Portal (/order)
Route::get('/order', [OrderController::class, 'create'])->name('public.order.create');
Route::post('/order', [OrderController::class, 'store'])->name('public.order.store');

// Success Page
Route::get('/order/success/{invoiceCode}', [OrderController::class, 'success'])->name('public.order.success');

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
