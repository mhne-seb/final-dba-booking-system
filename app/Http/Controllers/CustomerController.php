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

    
    // Update customer details
    public function update(Request $request, $id)
    {
        $customer_id = $id;
        $name = $request->input('name');
        $email = $request->input('email');
        $phone_number = $request->input('phone_number');
        $brand = $request->input('brand');
        $model = $request->input('model');
        $vehicle_type = $request->input('vehicle_type');
        $plate_number = $request->input('plate_number');
        $vehicle_id = DB::table('vehicle')
                    ->where('customer_id', $id)
                    ->where('plate_number', $plate_number)
                    ->value('vehicle_id'); 
        DB::statement('CALL update_customer_info(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $customer_id,$vehicle_id,$name, $email, $phone_number, $vehicle_type,$brand, $model, $plate_number
        ]);
        

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    // Delete customer and associated vehicle
    public function delete($id){
    
    $customer = Customer::find($id);

    if ($customer) {
        DB::statement('CALL delete_customer_info(?)', [$id]);
    } 
    
    return redirect()->route('customers.index');
}
}