<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\InSchedule;

class BookingController extends Controller
{
    // Display all bookings
    public function index()
    {
        $bookings = ServiceRequest::with(['customer', 'vehicle', 'serviceDetails', 'shop'])
            ->whereIn('status', ['confirmed', 'in_queue'])
            ->get()
            ->map(function ($booking) {
                // Get schedule info if exists
                $schedule = InSchedule::where('request_id', $booking->request_id)->first();
                
                return [
                    'id' => $booking->request_id,
                    'customer' => $booking->customer->name ?? 'Unknown',
                    'email' => $booking->customer->email ?? '',
                    'vehicle' => $booking->vehicle->model ?? 'Unknown',
                    'plate' => $booking->vehicle->plate_number ?? '',
                    'service' => $booking->serviceDetails->service_type ?? 'Unknown',
                    'shop' => $booking->shop->name ?? 'Main Branch',
                    'date' => $booking->serviceDetails->preferred_date ?? '',
                    'time' => $booking->serviceDetails->preferred_time ?? '',
                    'status' => $schedule->progress ?? 'not-started',
                    'amount' => $schedule->billing_amount ?? 0
                ];
            });
        
        return view('pages.bookings', compact('bookings'));
    }
    
    // Update booking status
    public function updateStatus(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:service_request,request_id',
            'status' => 'required|in:not-started,in-progress,stuck,done'
        ]);
        
        $bookingId = $request->booking_id;
        $status = $request->status;
        
        // Find or create schedule entry
        $schedule = InSchedule::where('request_id', $bookingId)->first();
        
        if (!$schedule) {
            $schedule = InSchedule::create([
                'request_id' => $bookingId,
                'billing_amount' => 0,
                'date_started' => now(),
                'progress' => $status
            ]);
        } else {
            $schedule->update(['progress' => $status]);
            
            // If status is 'done', set date_ended
            if ($status === 'done') {
                $schedule->update(['date_ended' => now()]);
            }
        }
        
        return redirect()->route('bookings')
            ->with('success', 'Booking status updated!');
    }
    
    // Update billing amount
    public function updateBilling(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:service_request,request_id',
            'amount' => 'required|numeric|min:0'
        ]);
        
        $schedule = InSchedule::where('request_id', $request->booking_id)->first();
        
        if ($schedule) {
            $schedule->update(['billing_amount' => $request->amount]);
        } else {
            InSchedule::create([
                'request_id' => $request->booking_id,
                'billing_amount' => $request->amount,
                'date_started' => now(),
                'progress' => 'not-started'
            ]);
        }
        
        return redirect()->route('bookings')
            ->with('success', 'Billing amount updated!');
    }
    
    // Delete booking
    public function destroy($id)
    {
        // Delete schedule first
        InSchedule::where('request_id', $id)->delete();
        
        // Then delete service request
        ServiceRequest::where('request_id', $id)->delete();
        
        return redirect()->route('bookings')
            ->with('success', 'Booking deleted!');
    }
}