<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\InSchedule;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\ServiceDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $customers = DB::select("CALL customer_info_join()");
        $kpi_metrics = DB::select("CALL customer_kpi_metrics()")[0];
        return view('pages.dashboard', compact('customers', 'kpi_metrics'));
    }
}