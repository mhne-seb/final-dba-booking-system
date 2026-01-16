<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    // Add shop
    public function store(Request $request)
    {
        $name = $request->input('name');
        $address = $request->input('address');
        $phone_number = $request->input('phone_number');
        $email = $request->input('email');
        $city = $request->input('city');
        DB::statement('CALL add_shop(?, ?, ?, ?,?)', [
            $name,$address,$phone_number,$email,$city]);
        
        return redirect()->route('employees.index');
            
    }

    
    // Delete shop
    public function delete($id)
    {
        DB::statement('CALL delete_shop(?)', [$id]);

        return redirect()->route('employees.index');
           
    }
}
