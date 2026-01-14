<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', '2024-01-01');
        $endDate = $request->get('end_date', '2024-01-15');
        $serviceFilter = $request->get('service', '');

        // Mock Data (Ideally from your Database)
        $history = collect([
            ['id' => '1', 'customer' => 'Juan dela Cruz', 'vehicle' => 'Toyota Vios', 'service' => 'Tire Vulcanizing', 'date' => '2024-01-15', 'employee' => 'Mike Johnson', 'duration' => '1.5 hrs'],
            ['id' => '2', 'customer' => 'Pedro Reyes', 'vehicle' => 'Ford Ranger', 'service' => 'Tire Replacement', 'date' => '2024-01-15', 'employee' => 'Carlos Garcia', 'duration' => '2 hrs'],
            ['id' => '3', 'customer' => 'Maria Santos', 'vehicle' => 'Honda CR-V', 'service' => 'Wheel Alignment', 'date' => '2024-01-14', 'employee' => 'John Smith', 'duration' => '1 hr'],
        ]);

        $filtered = $history->filter(function ($item) use ($startDate, $endDate, $serviceFilter) {
            $matchesDate = $item['date'] >= $startDate && $item['date'] <= $endDate;
            $matchesService = empty($serviceFilter) || str_contains(strtolower($item['service']), strtolower($serviceFilter));
            return $matchesDate && $matchesService;
        });

        return view('admin.reports.index', [
            'history' => $filtered,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'serviceFilter' => $serviceFilter,
        ]);
    }

    public function export(Request $request)
    {
        // CSV logic similar to index but returns a download response
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=AutoCare_Report.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Date', 'Customer', 'Vehicle', 'Service', 'Technician', 'Duration', 'Status'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            // Add rows here from DB query
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}