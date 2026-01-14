<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use App\Models\ServiceRequest;

class HistoryController extends Controller
{
    // Display history and reports
    public function index(Request $request)
    {
        $startDate = $request->query('startDate', date('Y-m-01'));
        $endDate = $request->query('endDate', date('Y-m-d'));
        $serviceFilter = trim($request->query('service', ''));
        
        // Get completed service requests
        $completedBookings = ServiceRequest::with(['customer', 'vehicle', 'serviceDetails'])
            ->where('status', 'cancelled') // Using cancelled as "completed" for demo
            ->whereHas('serviceDetails', function ($query) use ($startDate, $endDate, $serviceFilter) {
                $query->whereBetween('preferred_date', [$startDate, $endDate]);
                
                if ($serviceFilter) {
                    $query->where('service_type', 'like', '%' . $serviceFilter . '%');
                }
            })
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->request_id,
                    'customer' => $booking->customer->name ?? 'Unknown',
                    'vehicle' => $booking->vehicle->model ?? 'Unknown',
                    'service' => $booking->serviceDetails->service_type ?? 'Unknown',
                    'date' => $booking->serviceDetails->preferred_date ?? '',
                    'employee' => 'Technician Name', // You can add actual employee assignment
                    'duration' => '1.5 hrs' // You can calculate actual duration
                ];
            });
        
        // Calculate stats
        $stats = [
            'total_completed' => $completedBookings->count(),
            'vehicles_serviced' => $completedBookings->count(),
            'avg_service_time' => '1.2 hrs' // You can calculate this
        ];
        
        return view('pages.history', compact('completedBookings', 'stats', 'startDate', 'endDate', 'serviceFilter'));
    }
    
    // Export history to CSV
    public function export(Request $request)
    {
        $startDate = $request->query('startDate', date('Y-m-01'));
        $endDate = $request->query('endDate', date('Y-m-d'));
        
        $completedBookings = ServiceRequest::with(['customer', 'vehicle', 'serviceDetails'])
            ->where('status', 'cancelled')
            ->whereHas('serviceDetails', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('preferred_date', [$startDate, $endDate]);
            })
            ->get()
            ->map(function ($booking) {
                return [
                    'Date' => $booking->serviceDetails->preferred_date ?? '',
                    'Customer' => $booking->customer->name ?? 'Unknown',
                    'Vehicle' => $booking->vehicle->model ?? 'Unknown',
                    'Service' => $booking->serviceDetails->service_type ?? 'Unknown',
                    'Technician' => 'Technician Name',
                    'Duration' => '1.5 hrs',
                    'Status' => 'Completed'
                ];
            });
        
        // Generate CSV
        $filename = "AutoCare_Report_{$startDate}_to_{$endDate}.csv";
        
        // Set headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['Date', 'Customer', 'Vehicle', 'Service', 'Technician', 'Duration', 'Status']);
        
        // Add data rows
        foreach ($completedBookings as $row) {
            fputcsv($output, array_values($row));
        }
        
        fclose($output);
        exit;
    }
}