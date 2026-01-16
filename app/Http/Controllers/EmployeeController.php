<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Shop;
use App\Models\EmployeesPerShop;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    // Display employees 
    public function index(){
      $employees = DB::select("CALL employees_info()");
      $shops = Shop::all();
      return view('pages.employees', compact('employees', 'shops'));
    }
        
       
    
    // Store new employee
    public function store(Request $request)
    {
        
        $first_name = $request -> input('first_name');
        $last_name = $request -> input('last_name');
        $email = $request -> input('email');
        $phone_number = $request -> input('phone_number');
        $role = $request -> input('role');
        $is_active = $request -> input('is_active');
        $shop = $request -> input('shop');
        DB::statement("CALL add_employee(?, ?, ?, ?, ?, ?, ?)",[
            $first_name,$last_name,$email,$phone_number,$role,$is_active,$shop
        ]);
        return redirect()->route('employees.index');
    }
    
    // Update employee
    public function update(Request $request, $id)
    {
        $employee_id = $id;
        $first_name = $request -> input('first_name');
        $last_name = $request -> input('last_name');
        $email = $request -> input('email');
        $phone_number = $request -> input('phone_number');
        $role = $request -> input('role');
        $is_active = $request -> input('is_active');
        $shop = $request -> input('shop');

        DB::statement("CALL update_employee(?, ?, ?, ?, ?, ?, ?, ?)",[
            $employee_id,$first_name,$last_name,$email,$phone_number,$role,$is_active,$shop
        ]);
        return redirect()->route('employees.index');
    }
    
    // Delete employee
    public function delete($id)
    {
        $employee_id = $id;
        DB::statement('CALL delete_employee(?)',[$id]);
        return redirect()->route('employees.index');
           
    }
    
    
}