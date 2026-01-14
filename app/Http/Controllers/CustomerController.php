<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Vehicle;

class CustomerController extends Controller
{
    // Display all customers
    public function index()
    {
        $customers = Customer::with('vehicles')->get();
        return view('pages.customers', compact('customers'));
    }
    
    // Store new customer
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone_number' => 'required|string|size:10',
            'email' => 'nullable|email|unique:customer,email',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'vehicle_type' => 'required|string',
            'plate_number' => 'required|string|unique:vehicle,plate_number',
        ]);
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Create customer
            $customer = Customer::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email ?? 'none',
                'created_since' => now()
            ]);
            
            // Create vehicle
            $customer->vehicles()->create([
                'vehicle_type' => $request->vehicle_type,
                'brand' => $request->brand,
                'model' => $request->model,
                'plate_number' => $request->plate_number
            ]);
            
            DB::commit();
            
            return redirect()->route('customers.index')
                ->with('success', 'Customer added successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to add customer: ' . $e->getMessage());
        }
    }
    
    // Show customer details (for API/JSON response)
    public function show($id)
    {
        $customer = Customer::with('vehicles')->findOrFail($id);
        return response()->json($customer);
    }
    
    // Edit customer (for API/JSON response)
    public function edit($id)
    {
        $customer = Customer::with('vehicles')->findOrFail($id);
        return response()->json($customer);
    }
    
    // Update customer
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone_number' => 'required|string|size:10',
            'email' => 'nullable|email|unique:customer,email,' . $id . ',customer_id',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'vehicle_type' => 'required|string',
            'plate_number' => 'required|string|unique:vehicle,plate_number,' . $request->vehicle_id . ',vehicle_id',
        ]);
        
        DB::beginTransaction();
        
        try {
            $customer = Customer::findOrFail($id);
            
            // Update customer
            $customer->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email ?? $customer->email
            ]);
            
            // Update or create vehicle
            if ($request->has('vehicle_id')) {
                $vehicle = Vehicle::where('customer_id', $id)
                    ->where('vehicle_id', $request->vehicle_id)
                    ->first();
                    
                if ($vehicle) {
                    $vehicle->update([
                        'vehicle_type' => $request->vehicle_type,
                        'brand' => $request->brand,
                        'model' => $request->model,
                        'plate_number' => $request->plate_number
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('customers.index')
                ->with('success', 'Customer updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update customer: ' . $e->getMessage());
        }
    }
    
    // Delete customer
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        
        // Delete related vehicles first
        Vehicle::where('customer_id', $id)->delete();
        
        // Then delete customer
        $customer->delete();
        
        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully!');
    }
}