<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UnitController as AdminUnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RentalController as AdminRentalController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\RentalController as MemberRentalController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/units/{unit}', [HomeController::class, 'show'])->name('units.show');

// Auth required routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Categories management
    Route::resource('categories', CategoryController::class);
    
    // Units management
    Route::resource('units', AdminUnitController::class);
    
    // Users management
    Route::resource('users', UserController::class);
    
    // Rentals management
    Route::get('/rentals/ongoing', [AdminRentalController::class, 'ongoing'])->name('rentals.ongoing');
    Route::get('/rentals/history', [AdminRentalController::class, 'history'])->name('rentals.history');
    Route::post('/rentals/{rental}/return', [AdminRentalController::class, 'return'])->name('rentals.return');
    Route::get('/rentals/{rental}/print', [AdminRentalController::class, 'printInvoice'])->name('rentals.print');
    Route::post('/rentals/update-overdue', [AdminRentalController::class, 'updateOverdue'])->name('rentals.updateOverdue');
    Route::resource('rentals', AdminRentalController::class)->except(['create', 'store', 'edit', 'update']);
});

// Member routes
Route::middleware(['auth', 'member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    
    // Rentals
    Route::get('/rentals', [MemberRentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/create/{unit}', [MemberRentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals', [MemberRentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}', [MemberRentalController::class, 'show'])->name('rentals.show');
    Route::put('/rentals/{rental}/return', [MemberRentalController::class, 'return'])->name('rentals.return');
});

require __DIR__.'/auth.php';
