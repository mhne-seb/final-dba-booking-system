<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\InSchedule;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // Display all bookings
    public function index(Request $request)
    {

        $filter = $request->query('filter', 'all');
        $allBookings = collect(DB::select("CALL booking_info()"));

        if ($filter !== 'all') {
            $bookings = $allBookings->where('progress', $filter)->values();
        } else {
            $bookings = $allBookings->values();
        }
        $kpi = DB::select("CALL booking_kpi()")[0];
        
        $counts = [
            'all'           => $kpi->total_not_started + $kpi->total_in_progress + $kpi->total_stuck + $kpi->total_done,
            'not_started'   => $kpi->total_not_started, 
            'in_progress'   => $kpi->total_in_progress,
            'stuck'         => $kpi->total_stuck,
            'done'          => $kpi->total_done,
        ];
        
        return view('pages.bookings', compact('bookings', 'counts', 'filter'));
    }
    
    // Update booking status
    public function update(Request $request, $id)
    {
        $in_schedule_id = $id;
        $new_progress = $request->input('progress');
        DB::statement("CALL in_schedule_status(?, ?)", [$in_schedule_id, $new_progress]);
        
        return redirect()->route('bookings.index');
    }
    
    // Update billing amount
    public function updateBilling(Request $request)
    {
       
        
        return redirect()->route('bookings.index');
            
    }
    
    // Delete booking
    public function delete($id)
    {
        
        
        return redirect()->route('bookings')
            ->with('success', 'Booking deleted!');
    }
}