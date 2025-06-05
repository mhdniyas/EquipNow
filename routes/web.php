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
    
    // Booking routes
    Route::resource('bookings', BookingController::class);
    Route::patch('bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('bookings/{booking}/activate', [BookingController::class, 'activate'])->name('bookings.activate');
    Route::patch('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::patch('bookings/{booking}/return', [BookingController::class, 'processReturn'])->name('bookings.return');
    
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
