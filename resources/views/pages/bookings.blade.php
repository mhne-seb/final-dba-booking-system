@extends('layouts.app')
@section('content')

@php
// --- MOCK BOOKINGS & EMPLOYEES (demo only) ---
$mockEmployees = [
    ['id' => 'emp1', 'name' => 'Mike Johnson (Senior Tech)'],
    ['id' => 'emp2', 'name' => 'John Smith (Mechanic)'],
    ['id' => 'emp3', 'name' => 'Carlos Garcia (Tire Specialist)'],
];

if (!session()->has('bookings')) {
    session([
        'bookings' => [
            ['id' => '1', 'customer' => 'Juan dela Cruz', 'email' => 'juan@email.com', 'vehicle' => 'Toyota Vios', 'plate' => 'ABC 1234', 'service' => 'Tire Vulcanizing', 'shop' => 'Main Branch', 'date' => '2024-01-15', 'time' => '10:00 AM', 'employees' => ['Mike','John'], 'status' => 'in-progress', 'amount' => 500],
            ['id' => '2', 'customer' => 'Maria Santos', 'email' => 'maria@email.com', 'vehicle' => 'Honda CR-V', 'plate' => 'XYZ 5678', 'service' => 'Wheel Alignment', 'shop' => 'Main Branch', 'date' => '2024-01-15', 'time' => '11:30 AM', 'employees' => ['John'], 'status' => 'not-started', 'amount' => 1500],
            ['id' => '3', 'customer' => 'Pedro Reyes', 'email' => 'pedro@email.com', 'vehicle' => 'Ford Ranger', 'plate' => 'DEF 9012', 'service' => 'Tire Replacement', 'shop' => 'Branch 2', 'date' => '2024-01-15', 'time' => '02:00 PM', 'employees' => ['Mike','Carlos'], 'status' => 'done', 'amount' => 8000],
            ['id' => '4', 'customer' => 'Ana Garcia', 'email' => 'ana@email.com', 'vehicle' => 'Mitsubishi Montero', 'plate' => 'GHI 3456', 'service' => 'Wheel Balancing', 'shop' => 'Main Branch', 'date' => '2024-01-15', 'time' => '03:30 PM', 'employees' => ['Carlos'], 'status' => 'stuck', 'amount' => 1200],
        ]
    ]);
}

// --- HANDLE POST ACTIONS (demo logic in view) ---
if (request()->isMethod('post')) {
    $action = request()->input('action', '');
    $id = request()->input('booking_id', '');
    $bookings = session('bookings', []);

    if ($action === 'change_status') {
        $newStatus = request()->input('status', '');
        foreach ($bookings as &$b) {
            if ($b['id'] === $id) {
                $b['status'] = $newStatus;
            }
        }
        unset($b);
    } elseif ($action === 'update_amount') {
        $newAmount = request()->input('amount', '');
        foreach ($bookings as &$b) {
            if ($b['id'] === $id) {
                $b['amount'] = is_numeric($newAmount) ? floatval($newAmount) : $b['amount'];
            }
        }
        unset($b);
    } elseif ($action === 'delete') {
        $bookings = array_values(array_filter($bookings, fn($b) => $b['id'] !== $id));
    } elseif ($action === 'update_booking') {
        // generic update (if using edit form)
        foreach ($bookings as &$b) {
            if ($b['id'] === $id) {
                $b['customer'] = request()->input('customer', $b['customer']);
                $b['vehicle'] = request()->input('vehicle', $b['vehicle']);
                $b['service'] = request()->input('service', $b['service']);
                $b['date'] = request()->input('date', $b['date']);
                $b['time'] = request()->input('time', $b['time']);
                $b['amount'] = request()->input('amount', $b['amount']);
                $b['status'] = request()->input('status', $b['status']);
            }
        }
        unset($b);
    }

    session(['bookings' => $bookings]);
    // redirect back to avoid resubmission
    return redirect(request()->url());
}

// --- PREPARE TEMPLATE DATA ---
$allBookings = session('bookings', []);
$filter = request()->query('filter', 'all');
$filteredBookings = $filter === 'all' ? $allBookings : array_values(array_filter($allBookings, fn($b) => $b['status'] === $filter));
$counts = [
    'all' => count($allBookings),
    'not-started' => count(array_filter($allBookings, fn($b) => $b['status'] === 'not-started')),
    'in-progress' => count(array_filter($allBookings, fn($b) => $b['status'] === 'in-progress')),
    'stuck' => count(array_filter($allBookings, fn($b) => $b['status'] === 'stuck')),
    'done' => count(array_filter($allBookings, fn($b) => $b['status'] === 'done')),
];

$statusConfig = [
    'not-started' => ['label' => 'Not Started', 'color' => 'bg-slate-500/10 text-slate-400 border-slate-500/20', 'icon' => 'clock'],
    'in-progress' => ['label' => 'In Progress', 'color' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'icon' => 'play'],
    'stuck' => ['label' => 'Stuck', 'color' => 'bg-red-500/10 text-red-400 border-red-500/20', 'icon' => 'alert-triangle'],
    'done' => ['label' => 'Done', 'color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'icon' => 'check-circle-2'],
];
@endphp

<!-- Assets -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>window.lucide && lucide.createIcons && document.addEventListener('DOMContentLoaded', ()=>lucide.createIcons())</script>

<style>
    .tabs-trigger-active { background-color: #1e293b; color: white; border-radius: 4px; }
    .modal-active { overflow: hidden; }
</style>

<div class="min-h-screen p-6 max-w-7xl mx-auto">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Booking & Scheduling</h1>
            <p class="text-slate-400 mt-1">Manage service bookings and assignments</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
        @foreach (['not-started','in-progress','stuck','done'] as $k)
            <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl {{ $statusConfig[$k]['color'] }} border shadow-sm">
                        <i data-lucide="{{ $statusConfig[$k]['icon'] }}" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white">{{ $counts[$k] }}</div>
                        <div class="text-sm text-slate-400">{{ $statusConfig[$k]['label'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        <div class="flex bg-slate-900 border border-slate-800 p-1 w-fit rounded-lg gap-2 text-slate-400 text-sm">
            @foreach (['all','not-started','in-progress','stuck','done'] as $f)
                <a href="?filter={{ $f }}" class="px-4 py-1.5 transition-all {{ $filter === $f ? 'tabs-trigger-active text-white' : 'hover:text-slate-200' }}">
                    {{ ucfirst($f) }} ({{ $counts[$f] ?? $counts['all'] }})
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-xl overflow-hidden mt-6">
        <div class="p-6 border-b border-slate-800">
            <h2 class="text-lg font-semibold text-white">Bookings List</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-800/50 text-slate-300 text-sm font-semibold">
                    <tr>
                        <th class="p-4 border-b border-slate-800">Booking ID</th>
                        <th class="p-4 border-b border-slate-800">Customer</th>
                        <th class="p-4 border-b border-slate-800">Service</th>
                        <th class="p-4 border-b border-slate-800">Shop</th>
                        <th class="p-4 border-b border-slate-800">Schedule</th>
                        <th class="p-4 border-b border-slate-800">Assigned To</th>
                        <th class="p-4 border-b border-slate-800 w-[180px]">Status</th>
                        <th class="p-4 border-right border-b border-slate-800 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($filteredBookings as $booking)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="p-4">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-md bg-slate-950 border border-slate-800 text-xs font-mono text-white font-bold">
                                    #B{{ str_pad($booking['id'], 3, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            <td class="p-4">
                                <div class="font-medium text-white">{{ $booking['customer'] }}</div>
                                <div class="text-xs text-slate-500">{{ $booking['email'] }}</div>
                            </td>

                            <td class="p-4 text-slate-300">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="car" class="w-3 h-3 text-slate-500"></i>
                                    {{ $booking['service'] }}
                                </div>
                            </td>

                            <td class="p-4 text-slate-400">{{ $booking['shop'] }}</td>

                            <td class="p-4">
                                <div class="flex flex-col text-slate-300">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="calendar" class="w-3 h-3 text-slate-500"></i>
                                        {{ $booking['date'] }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <i data-lucide="clock" class="w-3 h-3 text-slate-500"></i>
                                        <span class="text-xs">{{ $booking['time'] }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="p-4">
                                <div class="flex items-center gap-1.5 text-slate-300">
                                    <i data-lucide="users" class="w-4 h-4 text-slate-500"></i>
                                    {{ implode(', ', $booking['employees']) }}
                                </div>
                            </td>

                            <td class="p-4">
                                <!-- status change form -->
                                <form method="POST" action="{{ url()->current() }}" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="action" value="change_status">
                                    <input type="hidden" name="booking_id" value="{{ $booking['id'] }}">
                                    <select name="status" onchange="this.form.submit()" class="bg-slate-950 border border-slate-700 rounded-md px-3 py-1 text-sm">
                                        <option value="not-started" {{ $booking['status'] === 'not-started' ? 'selected' : '' }}>Not Started</option>
                                        <option value="in-progress" {{ $booking['status'] === 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="stuck" {{ $booking['status'] === 'stuck' ? 'selected' : '' }}>Stuck</option>
                                        <option value="done" {{ $booking['status'] === 'done' ? 'selected' : '' }}>Done</option>
                                    </select>
                                </form>
                            </td>

                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded" onclick="openViewModal('{{ $booking['id'] }}')">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-400">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- VIEW / BILLING Modal -->
<div id="viewModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 rounded-xl max-w-2xl w-full p-0 overflow-hidden">
        <div class="p-6 border-b border-slate-800 bg-slate-800/30 flex justify-between items-start">
            <div>
                <h3 class="text-xl font-bold text-white">Booking Details</h3>
                <p class="text-slate-400 text-sm mt-1" id="view_id">ID: #B000</p>
            </div>
            <div>
                <button id="printReceiptBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded mr-2">Print Receipt</button>
                <button onclick="closeViewModal()" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-slate-400 uppercase font-bold">Customer</p>
                    <p class="text-white font-medium text-lg" id="view_customer">—</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase font-bold">Vehicle</p>
                    <p class="text-white font-medium text-lg" id="view_vehicle">—</p>
                    <p class="text-slate-400 text-sm" id="view_plate">—</p>
                </div>
            </div>

            <div class="h-px bg-slate-800"></div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-slate-400 uppercase font-bold">Service</p>
                    <p class="text-primary font-bold text-lg" id="view_service">—</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase font-bold">Technicians</p>
                    <p class="text-slate-300" id="view_techs">—</p>
                </div>
            </div>

            <div class="bg-slate-950 p-4 rounded-lg border border-slate-800">
                <p class="text-xs text-slate-500 uppercase font-bold mb-2">Billing Information</p>
                <form id="billingForm" method="POST" action="{{ url()->current() }}" class="flex items-center gap-4">
                    @csrf
                    <input type="hidden" name="action" value="update_amount">
                    <input type="hidden" name="booking_id" id="billing_booking_id" value="">
                    <input type="number" step="0.01" name="amount" id="billing_amount" class="bg-slate-900 border-slate-700 text-white text-xl font-bold h-12 px-3 rounded w-full" />
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white h-12 px-6 rounded font-bold">Update Amount</button>
                </form>
            </div>
        </div>

        <div class="p-4 bg-slate-900 border-t border-slate-800 flex justify-end">
            <button onclick="closeViewModal()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-md text-white">Close</button>
            <form id="deleteFormFromView" method="POST" action="{{ url()->current() }}" class="ml-2">
                @csrf
                <input type="hidden" name="action" value="delete" />
                <input type="hidden" name="booking_id" id="delete_booking_id" value="" />
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-md text-white">Delete</button>
            </form>
        </div>
    </div>
</div>

<!-- HIDDEN THERMAL RECEIPT (printed) -->
<div id="receiptPrintArea" style="display:none;">
    <div id="receiptContent" style="width:300px; font-family:monospace; padding:8px;">
        <!-- content populated by JS before print -->
    </div>
</div>

<script>
    // Provide booking data to JS for modal population
    const bookings = @json(array_values($allBookings));
    const statusConfig = @json($statusConfig);

    // Helpers to open/close view modal and populate fields
    function openViewModal(id) {
        const b = bookings.find(x => String(x.id) === String(id));
        if (!b) return alert('Booking not found');
        document.getElementById('view_id').textContent = 'ID: #B' + String(b.id).padStart(3,'0');
        document.getElementById('view_customer').textContent = b.customer;
        document.getElementById('view_vehicle').textContent = b.vehicle;
        document.getElementById('view_plate').textContent = b.plate;
        document.getElementById('view_service').textContent = b.service;
        document.getElementById('view_techs').textContent = b.employees.join(', ');
        document.getElementById('billing_booking_id').value = b.id;
        document.getElementById('billing_amount').value = b.amount ?? '';
        document.getElementById('delete_booking_id').value = b.id;

        document.getElementById('viewModal').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    // Print receipt: populate thermal markup and call window.print()
    function printReceiptForBooking(id) {
        const b = bookings.find(x => String(x.id) === String(id));
        if (!b) return alert('Booking not found');
        const content = document.getElementById('receiptContent');
        const amount = (b.amount !== undefined && b.amount !== null) ? parseFloat(b.amount).toFixed(2) : '0.00';
        content.innerHTML = `
            <div style="text-align:center; font-weight:bold; margin-bottom:6px;">
                AUTOCARE VULCANIZING & AUTO SERVICES
            </div>
            <div style="text-align:center; font-size:11px; margin-bottom:8px;">
                📍 M123 Manila City<br/>📞 09XX-XXX-XXXX
            </div>
            ------------------------------<br/>
            OFFICIAL RECEIPT<br/>
            ------------------------------<br/>
            Rcpt No.: AC-B${String(b.id).padStart(3,'0')}<br/>
            Date: ${b.date}<br/>
            Time: ${b.time}<br/>
            ------------------------------<br/>
            CUSTOMER: ${b.customer}<br/>
            Vhcl: ${b.vehicle}<br/>
            Plate: ${b.plate}<br/>
            ------------------------------<br/>
            ${b.service} .......... ${amount}<br/>
            ------------------------------<br/>
            TOTAL: ₱${Number(amount).toLocaleString(undefined,{minimumFractionDigits:2})}<br/>
            ------------------------------<br/>
            Staff: ${b.employees[0] || '-'}<br/>
            Thank you for choosing AutoCare!<br/>
        `;
        // open new window for printing to avoid printing the rest of the page
        const w = window.open('', '_blank', 'width=400,height=800');
        if (!w) {
            alert('Please allow popups to print the receipt.');
            return;
        }
        w.document.write('<html><head><title>Receipt</title>');
        w.document.write('<style>body{font-family:monospace;font-size:12px;padding:10px;background:#fff;color:#000}</style>');
        w.document.write('</head><body>');
        w.document.write(content.innerHTML);
        w.document.write('</body></html>');
        w.document.close();
        w.focus();
        setTimeout(() => {
            w.print();
            w.close();
        }, 300);
    }

    // Attach print button behavior on modal
    document.addEventListener('click', (e) => {
        if (e.target && e.target.id === 'printReceiptBtn') {
            const id = document.getElementById('billing_booking_id').value;
            printReceiptForBooking(id);
        }
    });

    // Render lucide icons in dynamic content
    if (window.lucide && typeof lucide.createIcons === 'function') lucide.createIcons();
</script>

@endsection