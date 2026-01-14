<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    public function index()
    {
        return view('Landing');
    }
    public function store(Request $request)
    {
        // Hassle paganahin, no validation nalang :>
        // $request->validate([
        //     'name' => 'required|string|max:100',
        //     'phone_number' => 'required|string|size:10',
        //     'email' => 'nullable|email|unique:customer,email',
        //     'brand' => 'required|string|max:100',
        //     'model' => 'required|string|max:100',
        //     'vehicle_type' => 'required|string',
        //     'plate_number' => 'required|string|unique:vehicle,plate_number',
        //     'preferred_date' => 'required|date|after_or_equal:today', 
        //     'preferred_time' => 'required',
        //     'service_type'   => 'required|string|max:50',
        //     'description'    => 'required|string',
        // ]);
        
        $name = $request->input('fullName');
        $phone_number = $request->input('contactNumber');
        $email = $request->input('email');
        $vehicle_type = $request->input('vehicleType');
        $brand = $request->input('vehicleBrand');
        $model = $request->input('vehicleModel');
        $plate_number = $request->input('plateNumber');
        $preferred_date = $request->input('preferredDate');
        $preferred_time = $request->input('preferredTime');
        $service_type = $request->input('serviceType');
        $concern = $request->input('concern');

       
        DB::statement("CALL add_customer_request(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
            $name,           
            $email,          
            $phone_number,   
            $vehicle_type,  
            $brand,          
            $model,          
            $plate_number,   
            $preferred_date, 
            $preferred_time, 
            $service_type,  
            $concern    
        ]);
        
        return redirect()->back()->with('success', 'Service request submitted!');
    }   
}
