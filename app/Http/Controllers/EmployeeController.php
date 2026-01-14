<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Shop;
use App\Models\EmployeesPerShop;

class EmployeeController extends Controller
{
    // Display employees and shops
    public function index()
    {
        $employees = Employee::all()->map(function ($emp) {
            // Get shop assignment
            $shopAssignment = EmployeesPerShop::where('employee_id', $emp->employee_id)
                ->first();
            
            $shopName = $shopAssignment ? 
                Shop::find($shopAssignment->shop_id)->name ?? 'Unknown' : 'Unassigned';
            
            return [
                'id' => $emp->employee_id,
                'full_name' => $emp->first_name . ' ' . $emp->last_name,
                'position' => $emp->role,
                'contact' => $emp->phone_number,
                'shop' => $shopName,
                'status' => $emp->is_active
            ];
        });
        
        $shops = Shop::all()->map(function ($shop) {
            return [
                'id' => $shop->shop_id,
                'name' => $shop->name,
                'address' => $shop->address,
                'phone' => $shop->phone_number,
                'status' => 'active' // You can add status field to shops table
            ];
        });
        
        return view('pages.employees', compact('employees', 'shops'));
    }
    
    // Store new employee
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:employee,email',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|string|size:10',
            'role' => 'required|string|max:50',
            'hire_date' => 'required|date',
            'active_until' => 'nullable|date',
            'is_active' => 'required|in:active,inactive'
        ]);
        
        $employee = Employee::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hash password
            'phone_number' => $request->phone_number,
            'hire_date' => $request->hire_date,
            'active_until' => $request->active_until,
            'role' => $request->role,
            'is_active' => $request->is_active
        ]);
        
        // Assign to shop if provided
        if ($request->has('shop_id')) {
            EmployeesPerShop::create([
                'employee_id' => $employee->employee_id,
                'shop_id' => $request->shop_id
            ]);
        }
        
        return redirect()->route('employees')
            ->with('success', 'Employee added successfully!');
    }
    
    // Update employee
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:employee,email,' . $id . ',employee_id',
            'phone_number' => 'required|string|size:10',
            'role' => 'required|string|max:50',
            'hire_date' => 'required|date',
            'active_until' => 'nullable|date',
            'is_active' => 'required|in:active,inactive'
        ]);
        
        $employee = Employee::findOrFail($id);
        $employee->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'hire_date' => $request->hire_date,
            'active_until' => $request->active_until,
            'role' => $request->role,
            'is_active' => $request->is_active
        ]);
        
        // Update password if provided
        if ($request->filled('password')) {
            $employee->update(['password' => bcrypt($request->password)]);
        }
        
        // Update shop assignment
        if ($request->has('shop_id')) {
            EmployeesPerShop::updateOrCreate(
                ['employee_id' => $id],
                ['shop_id' => $request->shop_id]
            );
        }
        
        return redirect()->route('employees')
            ->with('success', 'Employee updated successfully!');
    }
    
    // Delete employee
    public function destroy($id)
    {
        // Remove shop assignments first
        EmployeesPerShop::where('employee_id', $id)->delete();
        
        // Then delete employee
        Employee::findOrFail($id)->delete();
        
        return redirect()->route('employees')
            ->with('success', 'Employee deleted successfully!');
    }
    
    // Store new shop
    public function storeShop(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:75',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:75',
            'phone_number' => 'required|string|size:10',
            'email' => 'required|email|unique:shop,email',
            'description' => 'nullable|string'
        ]);
        
        Shop::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'description' => $request->description
        ]);
        
        return redirect()->route('employees')
            ->with('success', 'Shop added successfully!');
    }
    
    // Update shop
    public function updateShop(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:75',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:75',
            'phone_number' => 'required|string|size:10',
            'email' => 'required|email|unique:shop,email,' . $id . ',shop_id',
            'description' => 'nullable|string'
        ]);
        
        $shop = Shop::findOrFail($id);
        $shop->update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'description' => $request->description
        ]);
        
        return redirect()->route('employees')
            ->with('success', 'Shop updated successfully!');
    }
    
    // Delete shop
    public function destroyShop($id)
    {
        // Remove all employee assignments first
        EmployeesPerShop::where('shop_id', $id)->delete();
        
        // Then delete shop
        Shop::findOrFail($id)->delete();
        
        return redirect()->route('employees')
            ->with('success', 'Shop deleted successfully!');
    }
}