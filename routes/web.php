<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\test;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::post('/', [LandingController::class, 'store'])->name('landing.store');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// Customers
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
Route::delete('/customers/{id}', [CustomerController::class, 'delete'])->name('customers.delete');

// services
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::put('/services/{id}', [ServiceController::class, 'update'])->name('services.update');
Route::delete('/services/{id}', [ServiceController::class, 'delete'])->name('services.delete');


/**
 * PUBLIC BOOKING LOGIC
 * These routes are accessible to guests. This fixes the 404 error 
 * when a user clicks "Book Now" on your Landing page.
 */
// This handles the form submission from your Landing page
Route::post('/services/book', [ServiceController::class, 'store'])->name('services.store');


//--- Authentication Routes (Guest Access Only)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});


//--- Protected Admin & Staff Routes (Auth Required)
Route::middleware(['auth'])->group(function () {

    // Authentication: Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    
    
    
    
   
    
    // Bookings (Active work / In Schedule)
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('bookings');
        Route::post('/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
        Route::post('/billing', [BookingController::class, 'updateBilling'])->name('bookings.billing');
        Route::delete('{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    });

  
    // Employees & Shop Management
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employees');
        Route::post('/', [EmployeeController::class, 'store'])->name('employees.store');
        Route::post('/{id}/update', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        
        // Shop configuration
        Route::prefix('shops')->group(function () {
            Route::post('/', [EmployeeController::class, 'storeShop'])->name('shops.store');
            Route::post('/{id}/update', [EmployeeController::class, 'updateShop'])->name('shops.update');
            Route::delete('/{id}', [EmployeeController::class, 'destroyShop'])->name('shops.destroy');
        });
    });
    
    // History, Reports, and Logs
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::post('/history/export', [HistoryController::class, 'export'])->name('history.export');
});