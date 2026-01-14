<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// --- Public Routes ---
Route::get('/', function () { return view('Landing'); })->name('home');
Route::get('/login', function () { return view('Login'); })->name('login');

Route::post('/login', function (Request $request) {
    return redirect()->route('dashboard');
});

// --- Admin Pages ---
Route::middleware([])->group(function () { 

    Route::get('/dashboard', function () { 
        return view('pages.dashboard'); 
    })->name('dashboard');

    // --- Customer Management ---
    Route::prefix('customers')->group(function () {
        Route::get('/', function () { 
            $customers = [
                ['id' => 1, 'full_name' => 'Juan dela Cruz', 'email' => 'juan@email.com', 'contact_number' => '09123456789', 'vehicle_brand' => 'Toyota', 'vehicle_model' => 'Vios', 'plate_number' => 'ABC 1234'],
                ['id' => 2, 'full_name' => 'Maria Santos', 'email' => 'maria@email.com', 'contact_number' => '09234567890', 'vehicle_brand' => 'Honda', 'vehicle_model' => 'Civic', 'plate_number' => 'XYZ 9876'],
            ];
            return view('pages.customers', compact('customers'));
        })->name('customers.index');

        Route::post('/', function (Request $request) { return back()->with('success', 'Customer added!'); })->name('customers.store');
        Route::delete('/{id}', function ($id) { return back()->with('success', 'Customer deleted!'); })->name('customers.destroy');
        Route::put('/{id}', function (Request $request, $id) { return back()->with('success', 'Customer updated!'); })->name('customers.update');
    });

    // --- Service Requests (FIXED) ---
    Route::get('/services', function () { 
        // Mock Data for the Service Request UI
        $requests = [
            ['id' => '1', 'customerId' => '1', 'customer' => 'Juan dela Cruz', 'vehicle' => 'Toyota Vios (ABC 1234)', 'service' => 'Tire Vulcanizing', 'date' => '2024-01-15', 'time' => '10:00 AM', 'status' => 'pending', 'concern' => 'Front left tire has a slow leak.', 'assignedTo' => null],
            ['id' => '2', 'customerId' => '2', 'customer' => 'Maria Santos', 'vehicle' => 'Honda CR-V (XYZ 5678)', 'service' => 'Wheel Alignment', 'date' => '2024-01-15', 'time' => '11:30 AM', 'status' => 'confirmed', 'concern' => 'Car pulling to the right.', 'assignedTo' => 'John Smith'],
            ['id' => '3', 'customerId' => '3', 'customer' => 'Pedro Reyes', 'vehicle' => 'Ford Ranger (DEF 9012)', 'service' => 'Tire Replacement', 'date' => '2024-01-15', 'time' => '02:00 PM', 'status' => 'in-queue', 'concern' => 'Need new set of off-road tires.', 'assignedTo' => 'Carlos Garcia'],
        ];

        $employees = [
            ['id' => 'emp1', 'name' => 'Mike Johnson (Senior Tech)'],
            ['id' => 'emp2', 'name' => 'John Smith (Mechanic)'],
            ['id' => 'emp3', 'name' => 'Carlos Garcia (Tire Specialist)'],
        ];

        return view('pages.services', compact('requests', 'employees')); 
    })->name('services');

    Route::get('/bookings', function () { return view('pages.bookings'); })->name('bookings');
    Route::get('/employees', function () { return view('pages.employees'); })->name('employees');
    Route::get('/history', function () { return view('pages.history'); })->name('history');
});

// --- Logout ---
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');