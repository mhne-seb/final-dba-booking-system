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
use App\Http\Controllers\ShopController;
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

// bookings
Route::get('/bookings',[BookingController::class,'index'])->name('bookings.index');
Route::post('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
Route::put('/bookings/{id}/billing', [BookingController::class, 'updateBilling'])->name('bookings.billing');
Route::delete('/bookings/{id}', [BookingController::class, 'delete'])->name('bookings.delete');

// --- Employees Management ---
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{id}', [EmployeeController::class, 'delete'])->name('employees.delete');

// // --- Shop Management ---
Route::post('/shops', [ShopController::class, 'store'])->name('shops.store');
Route::put('/shops/{id}', [ShopController::class, 'update'])->name('shops.update');
Route::delete('/shops/{id}', [ShopController::class, 'delete'])->name('shops.delete');

// History
// History, Reports, and Logs
Route::get('/history', [HistoryController::class, 'index'])->name('history.index');





//--- Authentication Routes (Guest Access Only)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});



//--- Protected Admin & Staff Routes (Auth Required)
Route::middleware(['auth'])->group(function () {

    // Authentication: Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

});