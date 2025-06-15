<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\EquipmentPageController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MarketplaceBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Marketplace Pages
Route::get('/categories', [\App\Http\Controllers\CategoriesController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [\App\Http\Controllers\CategoriesController::class, 'show'])->name('categories.show');
Route::get('/shop', [\App\Http\Controllers\EquipmentPageController::class, 'index'])->name('equipment-page.index');
Route::get('/shop/{equipment}', [\App\Http\Controllers\EquipmentPageController::class, 'show'])->name('equipment-page.show');
Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about.index');
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Marketplace Booking Routes
Route::post('/booking/check-availability', [MarketplaceBookingController::class, 'checkAvailability'])->name('booking.check-availability');
Route::post('/booking', [MarketplaceBookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{booking}/confirmation', [MarketplaceBookingController::class, 'confirmation'])->name('booking.confirmation');

// Single page marketplace (optional)
Route::get('/marketplace', function () {
    return view('marketplace');
});

Route::get('/test-component', function () {
    return view('test-component');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Equipment routes
    Route::resource('equipment', EquipmentController::class);
    Route::post('equipment/{id}/maintenance', [EquipmentController::class, 'maintenance'])->name('equipment.maintenance');
    
    // Customer routes
    Route::resource('customers', CustomerController::class);
    
    // Subcategory routes
    Route::resource('subcategories', SubcategoryController::class);
    Route::get('categories/{category}/subcategories', [SubcategoryController::class, 'byCategory'])->name('subcategories.by-category');
    
    // Category routes with proper order (specific routes first, then catch-all routes)
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    
    // Admin-only category routes - these must come BEFORE the show route
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Category show route - must come AFTER more specific routes
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    
    // Booking routes - specific routes first to avoid conflicts
    Route::get('bookings/calendar', [BookingController::class, 'calendar'])->name('bookings.calendar');
    Route::get('bookings/search/equipment', [BookingController::class, 'searchEquipment'])->name('bookings.search-equipment');
    Route::get('bookings/search/customers', [BookingController::class, 'searchCustomers'])->name('bookings.search-customers');
    Route::get('bookings/stats', [BookingController::class, 'getStats'])->name('bookings.stats');
    Route::post('bookings/calculate-pricing', [BookingController::class, 'calculatePricing'])->name('bookings.calculate-pricing');
    Route::post('bookings/bulk-action', [BookingController::class, 'bulkAction'])->name('bookings.bulk-action');
    
    // AJAX Booking routes
    Route::post('bookings/{booking}/quick-confirm', [BookingController::class, 'quickConfirm'])->name('bookings.quick-confirm');
    Route::post('bookings/{booking}/quick-activate', [BookingController::class, 'quickActivate'])->name('bookings.quick-activate');
    Route::post('bookings/{booking}/quick-cancel', [BookingController::class, 'quickCancel'])->name('bookings.quick-cancel');
    Route::post('bookings/{booking}/duplicate', [BookingController::class, 'duplicate'])->name('bookings.duplicate');
    
    // Booking specific actions
    Route::patch('bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('bookings/{booking}/activate', [BookingController::class, 'activate'])->name('bookings.activate');
    Route::patch('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::patch('bookings/{booking}/return', [BookingController::class, 'processReturn'])->name('bookings.return');
    Route::get('bookings/{booking}/invoice', [BookingController::class, 'invoice'])->name('bookings.invoice');
    
    // Booking resource routes (must come last)
    Route::resource('bookings', BookingController::class);
    
    // Payment routes
    Route::resource('payments', PaymentController::class);
    Route::patch('payments/{payment}/complete', [PaymentController::class, 'markCompleted'])->name('payments.complete');
    Route::get('reports/payments/daily', [PaymentController::class, 'dailyReport'])->name('payments.daily-report');
    
    // Combo routes
    Route::resource('combos', ComboController::class);
    Route::patch('combos/{combo}/toggle-status', [ComboController::class, 'toggleStatus'])->name('combos.toggle-status');
    Route::get('api/combos/{combo}/items', [ComboController::class, 'getItems'])->name('api.combo.items');
    
    // Admin routes
    Route::middleware(['role:Admin'])->prefix('admin')->group(function () {
        Route::get('/users', function () {
            return 'Admin Users Panel';
        })->name('admin.users');
        
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics');
    });
    
    // Salesman routes - Updated to use BookingController
    Route::middleware(['role:Admin,Salesman'])->group(function () {
        // Booking routes are already defined above and accessible to Admin and Salesman roles
    });
    
    // Staff routes
    Route::middleware(['role:Admin,Staff'])->prefix('maintenance')->group(function () {
        Route::get('/', function () {
            return 'Maintenance Dashboard';
        })->name('maintenance.index');
    });
});

require __DIR__.'/auth.php';
