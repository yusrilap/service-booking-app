<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffBookingController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\ProfileController; // <-- ini ditambah

Route::get('/', [PublicController::class, 'index'])->name('home');

// Dashboard utama, redirect sesuai role
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.services.index');
    }

    if ($user->role === 'staff') {
        return redirect()->route('staff.dashboard');
    }

    // default: customer
    return redirect()->route('customer.bookings.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Group yang butuh login
Route::middleware(['auth', 'verified'])->group(function () {

    // ADMIN
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('services', AdminServiceController::class)->only(['index']);

            Route::get('bookings', [AdminBookingController::class, 'index'])
                ->name('bookings.index');

            // 👉 route untuk ubah status
            Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])
                ->name('bookings.update-status');
        });

    // STAFF
    Route::middleware('role:staff')
        ->prefix('staff')
        ->name('staff.')
        ->group(function () {
            Route::get('dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
            Route::get('bookings', [StaffBookingController::class, 'index'])->name('bookings.index');
        });

    // CUSTOMER
    Route::middleware('role:customer')
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {
            Route::get('slots', [CustomerBookingController::class, 'getAvailableSlots'])
            ->name('slots');
            Route::get('bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
            Route::get('bookings/create', [CustomerBookingController::class, 'create'])->name('bookings.create');
            Route::post('bookings', [CustomerBookingController::class, 'store'])->name('bookings.store');
            Route::get('services/{service}/staff', [CustomerBookingController::class, 'getStaffForService'])
            ->name('services.staff');
        });
        
});

// ROUTE PROFILE BAWAAN BREEZE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
