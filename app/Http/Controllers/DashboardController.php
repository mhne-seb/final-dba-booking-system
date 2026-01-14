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
    /**
     * Display the main admin dashboard.
     */
    public function index()
    {
        $today = Carbon::today();

        // 1. STATS LOGIC
        // We use ServiceRequest for intake and InSchedule for production/completion
        $stats = [
            'today_bookings'   => ServiceRequest::whereDate('created_at', $today)->count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'completed_today'  => InSchedule::where('progress', 'done')
                                    ->whereDate('date_ended', $today)->count(),
            'total_customers'  => Customer::count(),
            'total_employees'  => Employee::where('is_active', 'active')->count(),
        ];

        // 2. TODAY'S DETAILED BOOKINGS
        // Uses the relationships defined in your ServiceRequest model
        $todayBookings = ServiceRequest::with(['customer', 'vehicle', 'serviceDetails'])
            ->whereDate('created_at', $today)
            ->get()
            ->map(function($request) {
                return [
                    'customer' => $request->customer->name ?? 'Unknown',
                    'vehicle'  => ($request->vehicle->brand ?? '') . ' ' . ($request->vehicle->model ?? ''),
                    'service'  => $request->serviceDetails->service_type ?? 'General Service',
                    // Format time from the service_details table
                    'time'     => $request->serviceDetails 
                                    ? Carbon::parse($request->serviceDetails->preferred_time)->format('h:i A') 
                                    : 'N/A',
                    'status'   => ucfirst(str_replace('_', ' ', $request->status))
                ];
            });

        // 3. WEEKLY STATS (Chart Logic)
        // Calculates daily volume for the last 7 days
        $weeklyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = ServiceRequest::whereDate('created_at', $date)->count();
            
            // Assume 20 bookings is 100% capacity for UI scaling
            $weeklyStats[] = [
                'day' => $date->format('D'),
                'percent' => min(($count / 20) * 100, 100) 
            ];
        }

        // 4. POPULAR SERVICES (Real Database Data)
        // Groups the ENUM values from service_details to show what's trending
        $totalRequests = max(ServiceRequest::count(), 1); 
        
        $popularServices = ServiceDetails::select('service_type', DB::raw('count(*) as total'))
            ->groupBy('service_type')
            ->orderBy('total', 'desc')
            ->limit(4)
            ->get()
            ->map(function($item) use ($totalRequests) {
                return [
                    'name' => $item->service_type,
                    'percent' => round(($item->total / $totalRequests) * 100)
                ];
            });

        // Return the view with all compact data
        return view('pages.dashboard', compact('stats', 'todayBookings', 'weeklyStats', 'popularServices'));
    }
}