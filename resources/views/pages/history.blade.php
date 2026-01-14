@extends('layouts.app')
@section('content')

@php
// -----------------------------
// Demo: History & Reports Blade
// -----------------------------
// Mock completed bookings (demo data)
$completedBookings = [
    ['id' => '1', 'customer' => 'Juan dela Cruz', 'vehicle' => 'Toyota Vios', 'service' => 'Tire Vulcanizing', 'date' => '2024-01-15', 'employee' => 'Mike Johnson', 'duration' => '1.5 hrs'],
    ['id' => '2', 'customer' => 'Pedro Reyes', 'vehicle' => 'Ford Ranger', 'service' => 'Tire Replacement', 'date' => '2024-01-15', 'employee' => 'Carlos Garcia', 'duration' => '2 hrs'],
    ['id' => '3', 'customer' => 'Maria Santos', 'vehicle' => 'Honda CR-V', 'service' => 'Wheel Alignment', 'date' => '2024-01-14', 'employee' => 'John Smith', 'duration' => '1 hr'],
    ['id' => '4', 'customer' => 'Ana Garcia', 'vehicle' => 'Mitsubishi Montero', 'service' => 'Wheel Balancing', 'date' => '2024-01-14', 'employee' => 'Mike Johnson', 'duration' => '45 mins'],
    ['id' => '5', 'customer' => 'Jose Rizal', 'vehicle' => 'Hyundai Accent', 'service' => 'Flat Tire Repair', 'date' => '2024-01-13', 'employee' => 'John Smith', 'duration' => '30 mins'],
];

// Read filters from query (GET)
$startDate = request()->query('startDate', '2024-01-01');
$endDate   = request()->query('endDate', '2024-01-15');
$serviceFilter = trim(request()->query('service', ''));

// Helper: convert booking date string to DateTime for comparison
function to_date($d) {
    try { return new DateTime($d); } catch (Exception $e) { return null; }
}

// Filter bookings by date range and service substring
$filteredData = array_values(array_filter($completedBookings, function($b) use ($startDate, $endDate, $serviceFilter) {
    $bd = to_date($b['date']);
    $s = to_date($startDate);
    $e = to_date($endDate);
    if (!$bd || !$s || !$e) return false;
    $inRange = $bd >= $s && $bd <= $e;
    $serviceMatch = $serviceFilter === '' ? true : (stripos($b['service'], $serviceFilter) !== false);
    return $inRange && $serviceMatch;
}));

// Compute stats from filtered or overall dataset (we'll compute from filtered for relevance)
$totalCompleted = count($filteredData);
$vehiclesServiced = count($filteredData);

// Compute average service time: convert durations to minutes then average
function duration_to_minutes($str) {
    $str = trim(strtolower($str));
    if (preg_match('/([\d\.]+)\s*hrs?/', $str, $m)) {
        return floatval($m[1]) * 60;
    }
    if (preg_match('/([\d\.]+)\s*hr/', $str, $m)) {
        return floatval($m[1]) * 60;
    }
    if (preg_match('/([\d\.]+)\s*mins?/', $str, $m)) {
        return floatval($m[1]);
    }
    // fallback: try parse float and assume hours
    if (preg_match('/([\d\.]+)/', $str, $m)) {
        return floatval($m[1]) * 60;
    }
    return 0;
}

$totalMinutes = 0;
foreach ($filteredData as $b) {
    $totalMinutes += duration_to_minutes($b['duration'] ?? '');
}
$avgMinutes = $totalCompleted > 0 ? ($totalMinutes / $totalCompleted) : 0;
$avgHoursDisplay = $avgMinutes >= 60 ? round($avgMinutes / 60, 1) . ' hrs' : round($avgMinutes) . ' mins';

// For display in stat cards; fallback to sensible values if no filtered records
$stats = [
    ['title' => 'Total Completed', 'value' => (string)$totalCompleted, 'period' => 'Selected Range', 'color' => 'bg-emerald-500/10 text-emerald-400'],
    ['title' => 'Vehicles Serviced', 'value' => (string)$vehiclesServiced, 'period' => 'Selected Range', 'color' => 'bg-blue-500/10 text-blue-400'],
    ['title' => 'Avg. Service Time', 'value' => $avgHoursDisplay ?: '0 mins', 'period' => 'Selected Range', 'color' => 'bg-violet-500/10 text-violet-400'],
];

@endphp

<!-- Assets -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>document.addEventListener('DOMContentLoaded', ()=>{ if(window.lucide && lucide.createIcons) lucide.createIcons(); })</script>

<style>
    .toast { position: fixed; top: 1rem; right: 1rem; z-index: 60; }
    .modal-active { overflow: hidden; }
</style>

<div class="min-h-screen bg-slate-950 p-6 space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">History & Reports</h1>
            <p class="text-slate-400 mt-1">View completed bookings and generate reports</p>
        </div>

        <div>
            <button id="exportBtn" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded shadow-lg">
                <i data-lucide="download" class="w-4 h-4 inline-block mr-2"></i>
                Export Report
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($stats as $stat)
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400">{{ $stat['title'] }}</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $stat['value'] }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $stat['period'] }}</p>
                    </div>
                    <div class="p-3 rounded-xl {{ $stat['color'] }} shadow-lg">
                        <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="bg-slate-900 border border-slate-800 rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between gap-4">
                <form id="filterForm" method="GET" action="{{ url()->current() }}" class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="text-xs text-slate-300">Start Date</label>
                            <input type="date" name="startDate" value="{{ $startDate }}" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />
                        </div>
                        <div>
                            <label class="text-xs text-slate-300">End Date</label>
                            <input type="date" name="endDate" value="{{ $endDate }}" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />
                        </div>
                        <div>
                            <label class="text-xs text-slate-300">Service Type</label>
                            <input type="text" name="service" placeholder="All services" value="{{ $serviceFilter }}" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 px-4 py-2 rounded">
                                <i data-lucide="calendar" class="w-4 h-4 inline-block mr-2"></i>
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Completed Bookings Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-800">
            <h2 class="text-lg font-semibold text-white">Completed Bookings History</h2>
            <p class="text-slate-400 text-sm mt-1">Showing {{ count($filteredData) }} records</p>
        </div>

        <div class="p-6">
            <div class="rounded-md border border-slate-800 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-800/50 text-slate-300 text-sm font-semibold">
                        <tr>
                            <th class="p-4 border-b border-slate-800">Date</th>
                            <th class="p-4 border-b border-slate-800">Customer</th>
                            <th class="p-4 border-b border-slate-800">Vehicle</th>
                            <th class="p-4 border-b border-slate-800">Service</th>
                            <th class="p-4 border-b border-slate-800">Technician</th>
                            <th class="p-4 border-b border-slate-800">Duration</th>
                            <th class="p-4 border-b border-slate-800">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach ($filteredData as $b)
                            <tr class="hover:bg-slate-800/30">
                                <td class="p-4 text-slate-400">{{ $b['date'] }}</td>
                                <td class="p-4 font-medium text-white">{{ $b['customer'] }}</td>
                                <td class="p-4 text-slate-400">{{ $b['vehicle'] }}</td>
                                <td class="p-4 text-slate-300">{{ $b['service'] }}</td>
                                <td class="p-4 text-slate-400">{{ $b['employee'] }}</td>
                                <td class="p-4 text-slate-400">{{ $b['duration'] }}</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-emerald-500/10 text-emerald-400 border-emerald-500/20">
                                        <i data-lucide="check-circle-2" class="w-3 h-3 mr-1"></i>
                                        Completed
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                        @if (count($filteredData) === 0)
                            <tr><td colspan="7" class="p-6 text-center text-slate-400">No records found for the selected filters.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Notification toast (hidden by default) -->
<div id="exportToast" class="toast hidden">
    <div class="bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-2xl flex items-center gap-3 border border-emerald-400/50">
        <div class="bg-white/20 p-1 rounded-full">
            <i data-lucide="check" class="w-4 h-4 text-white"></i>
        </div>
        <div>
            <p class="font-bold text-sm">Export Successful!</p>
            <p class="text-xs text-emerald-100">Report has been downloaded to your device.</p>
        </div>
    </div>
</div>

<script>
    // Data for client-side CSV export (already filtered on server)
    const data = @json($filteredData);

    function exportCsv(filtered) {
        if (!Array.isArray(filtered) || filtered.length === 0) {
            alert('No records to export for the selected filters.');
            return;
        }

        const headers = ["Date","Customer","Vehicle","Service","Technician","Duration","Status"];
        const rows = filtered.map(r => [
            r.date,
            r.customer.replace(/"/g, '""'),
            r.vehicle.replace(/"/g, '""'),
            r.service.replace(/"/g, '""'),
            r.employee.replace(/"/g, '""'),
            r.duration,
            'Completed'
        ]);

        const csvContent = [headers, ...rows].map(r => r.map(c => `"${c}"`).join(",")).join("\n");
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `AutoCare_Report_{{ $startDate }}_to_{{ $endDate }}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);

        // show toast
        const toast = document.getElementById('exportToast');
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    document.getElementById('exportBtn').addEventListener('click', () => exportCsv(data));
</script>

@endsection