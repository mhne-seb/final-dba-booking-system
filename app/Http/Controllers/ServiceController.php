<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\ServiceDetails;
use App\Models\ServiceRequest;

class ServiceController extends Controller
{
    public function index(Request $request){
        $filter = $request->query('filter', 'all');

    $allRequests = collect(DB::select("CALL full_customer_info()"));

    if ($filter !== 'all') {
        // Filter the collection based on the status column
        $customers = $allRequests->where('status', $filter);
    } else {
        $customers = $allRequests;
    }

    $employees = DB::table('employee')->select('employee_id', 'first_name')->get();

    $service_count = DB::select("CALL service_counts()")[0];
    $counts = [
        'all'       => $service_count->total_service_requests,
        'pending'   => $service_count->total_pending,
        'confirmed' => $service_count->total_confirmed,
        'cancelled' => $service_count->total_cancelled,
    ];
        return view('pages.services', compact('customers', 'employees', 'counts', 'filter'));
  
    }

    // Process the service_request 
    public function update(Request $request, $id)
    {
        
        $request_id = $id;
        $status = $request->input('status');
        $employee = $request->input('employee_id');

        DB::statement("CALL service_request_status(?, ?, ?)", [
            $request_id,
            $status,
            $employee
        ]);

        return redirect()->route('services.index');
    }

    // Delete service request
    public function delete($id){
        $request_id = $id;
        $status = 'cancelled';
        $employee = null;
        DB::statement("CALL service_request_status(?, ?, ?)", [
            $request_id,
            $status,
            $employee
        ]);
        return redirect()->route('services.index');
    }
}