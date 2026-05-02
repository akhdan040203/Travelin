<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{slug}', [DestinationController::class, 'show'])->name('destinations.show');

Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules');

Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactStore'])->name('contact.store');

// ==========================================
// AUTHENTICATED USER ROUTES
// ==========================================
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', fn() => redirect()->route('user.bookings'))->name('dashboard');
    Route::get('/bookings', [UserDashboardController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{bookingCode}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/wishlist', [UserDashboardController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/{destination}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{destination}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

Route::get('/booking/{scheduleId}', [BookingController::class, 'create'])->middleware('auth')->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->middleware('auth')->name('booking.store');

// Redirect Breeze's default dashboard to our custom one
Route::get('dashboard', fn() => redirect()->route('user.bookings'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// ==========================================
// ADMIN ROUTES
// ==========================================
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDestinationController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminScheduleController;

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('destinations', AdminDestinationController::class)->except(['show']);
        Route::resource('schedules', AdminScheduleController::class)->except(['show']);

        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    });

require __DIR__.'/auth.php';
