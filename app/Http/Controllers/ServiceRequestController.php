<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        // In a real app, use: $query = ServiceRequest::query();
        $filter = $request->query('status', 'all');
        
        // Mocking the data structure from your React code
        $requests = collect([
            ['id' => '1', 'customerId' => '1', 'customer' => 'Juan dela Cruz', 'vehicle' => 'Toyota Vios (ABC 1234)', 'service' => 'Tire Vulcanizing', 'date' => '2024-01-15', 'time' => '10:00 AM', 'status' => 'pending', 'concern' => 'Front left tire has a slow leak.', 'assignedTo' => null],
            ['id' => '2', 'customerId' => '2', 'customer' => 'Maria Santos', 'vehicle' => 'Honda CR-V (XYZ 5678)', 'service' => 'Wheel Alignment', 'date' => '2024-01-15', 'time' => '11:30 AM', 'status' => 'confirmed', 'concern' => 'Car pulling to the right.', 'assignedTo' => 'John Smith'],
        ]);

        $filtered = ($filter === 'all') ? $requests : $requests->where('status', $filter);

        return view('admin.requests.index', [
            'requests' => $filtered,
            'filter' => $filter,
            'counts' => [
                'all' => $requests->count(),
                'pending' => $requests->where('status', 'pending')->count(),
                'confirmed' => $requests->where('status', 'confirmed')->count(),
                'cancelled' => $requests->where('status', 'cancelled')->count(),
            ],
            'employees' => ['Mike Johnson', 'John Smith', 'Carlos Garcia']
        ]);
    }
}