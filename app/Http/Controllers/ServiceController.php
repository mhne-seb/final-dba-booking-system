<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\ServiceDetails;
use App\Models\ServiceRequest;

class ServiceController extends Controller
{
    // Show the booking form
    public function create()
    {
        return view('pages.booking-form'); 
    }

    // Process the booking
    public function store(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone_number' => 'required|string|size:10',
            'vehicle_type' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'plate_number' => 'required|string|unique:vehicle,plate_number',
            'preferred_date' => 'required|date',
            'preferred_time' => 'required',
            'service_type' => 'required',
        ]);

        // 2. Database Transaction to ensure all or nothing is saved
        DB::beginTransaction();

        try {
            // Find or Create Customer
            $customer = Customer::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'created_since' => now()
                ]
            );

            // Create Vehicle linked to Customer
            $vehicle = Vehicle::create([
                'customer_id' => $customer->customer_id,
                'vehicle_type' => $request->vehicle_type,
                'brand' => $request->brand,
                'model' => $request->model,
                'plate_number' => $request->plate_number
            ]);

            // Create Service Details (The "What" and "When")
            $details = ServiceDetails::create([
                'preferred_date' => $request->preferred_date,
                'preferred_time' => $request->preferred_time,
                'service_type' => $request->service_type,
                'description' => $request->description ?? 'No additional notes.'
            ]);

            // Create the Service Request (The "Booking" anchor)
            ServiceRequest::create([
                'customer_id' => $customer->customer_id,
                'vehicle_id' => $vehicle->vehicle_id,
                'service_details_id' => $details->service_details_id,
                'shop_id' => 1, // Defaulting to main shop
                'status' => 'pending',
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('home')->with('success', 'Booking submitted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error processing booking: ' . $e->getMessage());
        }
    }
}