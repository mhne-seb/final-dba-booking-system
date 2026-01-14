@extends('layouts.app')
@section('content')

@php
// --- MOCK EMPLOYEES & REQUESTS (demo only) ---
$mockEmployees = [
    ['id' => 'emp1', 'name' => 'Mike Johnson (Senior Tech)'],
    ['id' => 'emp2', 'name' => 'John Smith (Mechanic)'],
    ['id' => 'emp3', 'name' => 'Carlos Garcia (Tire Specialist)'],
];

// Initialize demo requests in session (only once)
if (!session()->has('requests')) {
    session([
        'requests' => [
            ['id' => '1', 'customerId' => '1', 'customer' => 'Juan dela Cruz', 'vehicle' => 'Toyota Vios (ABC 1234)', 'service' => 'Tire Vulcanizing', 'date' => '2024-01-15', 'time' => '10:00 AM', 'status' => 'pending', 'concern' => 'Front left tire has a slow leak. Need to check if vulcanizing is enough or need replacement.', 'assignedTo' => null],
            ['id' => '2', 'customerId' => '2', 'customer' => 'Maria Santos', 'vehicle' => 'Honda CR-V (XYZ 5678)', 'service' => 'Wheel Alignment', 'date' => '2024-01-15', 'time' => '11:30 AM', 'status' => 'confirmed', 'concern' => 'Car pulling to the right when driving straight.', 'assignedTo' => 'John Smith'],
            ['id' => '3', 'customerId' => '3', 'customer' => 'Pedro Reyes', 'vehicle' => 'Ford Ranger (DEF 9012)', 'service' => 'Tire Replacement', 'date' => '2024-01-15', 'time' => '02:00 PM', 'status' => 'in-queue', 'concern' => 'Need new set of tires for off-road use.', 'assignedTo' => 'Carlos Garcia'],
            ['id' => '4', 'customerId' => '4', 'customer' => 'Ana Garcia', 'vehicle' => 'Mitsubishi Montero (GHI 3456)', 'service' => 'Wheel Balancing', 'date' => '2024-01-16', 'time' => '09:00 AM', 'status' => 'pending', 'concern' => 'Vibration at high speeds (80kph+).', 'assignedTo' => null],
            ['id' => '5', 'customerId' => '5', 'customer' => 'Jose Rizal', 'vehicle' => 'Hyundai Accent (JKL 7890)', 'service' => 'Flat Tire Repair', 'date' => '2024-01-14', 'time' => '03:00 PM', 'status' => 'cancelled', 'concern' => 'Flat tire on rear right.', 'assignedTo' => null],
        ]
    ]);
}

// --- HANDLE POST ACTIONS (demo only; in real app move to controller) ---
if (request()->isMethod('post')) {
    $action = request()->input('action', '');
    $id = request()->input('request_id', '');
    $requests = session('requests', []);

    if ($action === 'confirm') {
        $technician = request()->input('technician', '');
        foreach ($requests as &$r) {
            if ($r['id'] === $id) {
                $r['status'] = 'confirmed';
                $r['assignedTo'] = $technician ?: $r['assignedTo'];
            }
        }
        unset($r);
    } elseif ($action === 'cancel') {
        foreach ($requests as &$r) {
            if ($r['id'] === $id) {
                $r['status'] = 'cancelled';
            }
        }
        unset($r);
    } elseif ($action === 'update') {
        // Basic update from edit form (if implemented)
        foreach ($requests as &$r) {
            if ($r['id'] === $id) {
                $r['customer'] = request()->input('customer', $r['customer']);
                $r['vehicle'] = request()->input('vehicle', $r['vehicle']);
                $r['service'] = request()->input('service', $r['service']);
                $r['date'] = request()->input('date', $r['date']);
                $r['time'] = request()->input('time', $r['time']);
                $r['concern'] = request()->input('concern', $r['concern']);
                $r['assignedTo'] = request()->input('assignedTo', $r['assignedTo']);
                $r['status'] = request()->input('status', $r['status']);
            }
        }
        unset($r);
    } elseif ($action === 'delete') {
        $requests = array_values(array_filter($requests, fn($r) => $r['id'] !== $id));
    }

    session(['requests' => $requests]);

    // redirect back to avoid re-submission
    return redirect(request()->url());
}

// --- DATA FOR TEMPLATE ---
$allRequests = session('requests', []);
$filter = request()->query('filter', 'all');

$filteredRequests = $filter === 'all'
    ? $allRequests
    : array_values(array_filter($allRequests, fn($r) => $r['status'] === $filter));

$counts = [
    'all' => count($allRequests),
    'pending' => count(array_filter($allRequests, fn($r) => $r['status'] === 'pending')),
    'confirmed' => count(array_filter($allRequests, fn($r) => $r['status'] === 'confirmed')),
    'in-queue' => count(array_filter($allRequests, fn($r) => $r['status'] === 'in-queue')),
    'cancelled' => count(array_filter($allRequests, fn($r) => $r['status'] === 'cancelled')),
];

$statusConfig = [
    'pending' => ['label' => 'Pending', 'color' => 'bg-orange-500/10 text-orange-400 border-orange-500/20', 'icon' => 'clock'],
    'confirmed' => ['label' => 'Confirmed', 'color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'icon' => 'check-circle-2'],
    'in-queue' => ['label' => 'In Queue', 'color' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'icon' => 'alert-circle'],
    'cancelled' => ['label' => 'Cancelled', 'color' => 'bg-red-500/10 text-red-400 border-red-500/20', 'icon' => 'x-circle'],
];
@endphp

<!-- Page content -->
<script src="https://unpkg.com/lucide@latest"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tailwindcss/ui@latest/dist/tailwind-ui.min.css" />
<style>
    .tabs-trigger-active { background-color: #1e293b; color: white; border-radius: 4px; }
    .modal-active { overflow: hidden; }
</style>

<div class="min-h-screen p-6 space-y-8 max-w-7xl mx-auto">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Service Requests</h1>
        <p class="text-slate-400 mt-1">Manage and process customer service requests</p>
    </div>

    <div class="flex bg-slate-900 border border-slate-800 p-1 w-fit rounded-lg gap-2 text-slate-400 text-sm">
        @foreach (['all', 'pending', 'confirmed', 'in-queue', 'cancelled'] as $f)
            <a href="?filter={{ $f }}" class="px-4 py-1.5 transition-all {{ $filter === $f ? 'tabs-trigger-active text-white' : 'hover:text-slate-200' }}">
                {{ ucfirst($f) }} ({{ $counts[$f] }})
            </a>
        @endforeach
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-800">
            <h2 class="text-lg font-semibold text-white">Request List</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-800/50 text-slate-300 text-sm font-semibold">
                    <tr>
                        <th class="p-4 border-b border-slate-800">Cust. ID</th>
                        <th class="p-4 border-b border-slate-800">Customer</th>
                        <th class="p-4 border-b border-slate-800">Vehicle</th>
                        <th class="p-4 border-b border-slate-800">Service</th>
                        <th class="p-4 border-b border-slate-800">Preferred Date</th>
                        <th class="p-4 border-b border-slate-800">Assigned To</th>
                        <th class="p-4 border-b border-slate-800">Status</th>
                        <th class="p-4 border-right border-b border-slate-800 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @foreach ($filteredRequests as $request)
                        @php $cfg = $statusConfig[$request['status']] ?? ['label'=>'Unknown','color'=>'','icon'=>'help-circle']; @endphp
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="p-4">
                                <span class="font-mono text-xs font-bold text-slate-400 bg-slate-950 px-2 py-1 rounded border border-slate-800">
                                    #{{ str_pad($request['customerId'], 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="font-medium text-white">{{ $request['customer'] }}</div>
                                <div class="text-xs text-slate-500 mt-0.5 truncate max-w-[150px]" title="{{ $request['concern'] }}">Note: {{ $request['concern'] }}</div>
                            </td>
                            <td class="p-4 text-slate-400">{{ $request['vehicle'] }}</td>
                            <td class="p-4">
                                <span class="text-slate-300 bg-slate-800 px-2 py-1 rounded text-sm">{{ $request['service'] }}</span>
                            </td>
                            <td class="p-4 text-slate-400 text-sm">
                                <div>{{ $request['date'] }}</div>
                                <div class="text-xs text-slate-500">{{ $request['time'] }}</div>
                            </td>
                            <td class="p-4">
                                @if ($request['assignedTo'])
                                    <div class="flex items-center gap-2 text-slate-300 text-sm">
                                        <i data-lucide="user" class="w-3 h-3 text-blue-500"></i>
                                        {{ $request['assignedTo'] }}
                                    </div>
                                @else
                                    <span class="text-xs text-slate-600 italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $cfg['color'] }}">
                                    <i data-lucide="{{ $cfg['icon'] }}" class="w-3 h-3 mr-1.5"></i>
                                    {{ $cfg['label'] }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="inline-flex items-center gap-2 justify-end">
                                    <!-- Actions dropdown (simple implementation) -->
                                    <div class="relative inline-block text-left">
                                        <button onclick="toggleMenu('menu-{{ $request['id'] }}')" class="p-2 text-slate-400 hover:text-slate-100 hover:bg-slate-800 rounded">
                                            <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                        </button>
                                        <div id="menu-{{ $request['id'] }}" class="hidden absolute right-0 mt-2 w-48 bg-slate-900 border border-slate-800 rounded shadow-lg z-40">
                                            <div class="py-1 text-sm text-white">
                                                <button class="w-full text-left px-4 py-2 hover:bg-slate-800" onclick="openViewModal('{{ $request['id'] }}')">
                                                    <i data-lucide="eye" class="w-4 h-4 inline-block mr-2"></i> View Details
                                                </button>

                                                @if ($request['status'] === 'pending')
                                                    <div class="border-t border-slate-800"></div>
                                                    <button class="w-full text-left px-4 py-2 text-emerald-400 hover:bg-slate-800" onclick="openConfirmModal('{{ $request['id'] }}')">
                                                        <i data-lucide="check-circle-2" class="w-4 h-4 inline-block mr-2"></i> Confirm
                                                    </button>
                                                    <button class="w-full text-left px-4 py-2 text-red-400 hover:bg-slate-800" onclick="openCancelModal('{{ $request['id'] }}')">
                                                        <i data-lucide="x-circle" class="w-4 h-4 inline-block mr-2"></i> Cancel
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if (count($filteredRequests) === 0)
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-400">No requests found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- VIEW Modal -->
<div id="viewModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 rounded-xl max-w-2xl w-full p-0 overflow-hidden">
        <div class="p-6 border-b border-slate-800 bg-slate-800/30 flex justify-between items-start">
            <div>
                <h3 class="text-xl font-bold text-white">Request Details</h3>
                <p class="text-slate-400 text-sm" id="view_req_id">ID: #0000</p>
            </div>
            <div id="view_status_badge"></div>
        </div>

        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Customer</p>
                    <div class="text-lg font-bold text-white" id="view_customer">—</div>
                    <div class="text-xs text-slate-500 mt-1" id="view_customer_id"></div>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Vehicle</p>
                    <div class="text-lg font-bold text-white" id="view_vehicle">—</div>
                </div>

                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Service Requested</p>
                    <div class="text-lg font-bold" id="view_service">—</div>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Preferred Schedule</p>
                    <div class="text-sm text-slate-300" id="view_schedule_date">—</div>
                    <div class="text-xs text-slate-500" id="view_schedule_time"></div>
                </div>

                <div class="md:col-span-2">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Assigned Technician</p>
                    <div class="bg-slate-800 p-3 rounded mt-2 text-slate-300" id="view_assigned">Not yet assigned (Pending confirmation)</div>
                </div>

                <div class="md:col-span-2">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Customer Concern / Notes</p>
                    <div class="bg-slate-800 p-3 rounded mt-2 text-slate-300" id="view_concern">—</div>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-800/30 flex justify-end gap-3">
            <button onclick="closeViewModal()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-md text-white">Close</button>
            <button onclick="openDeleteModalFromView()" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-md text-white">Delete</button>
            <button onclick="openEditModalFromView()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md text-white">Edit</button>
            <button onclick="openCancelModalFromView()" class="px-4 py-2 bg-red-500 hover:bg-red-600 rounded-md text-white">Reject</button>
            <button onclick="openConfirmModalFromView()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-md text-white">Confirm & Assign</button>
        </div>
    </div>
</div>

<!-- CONFIRM Modal (form) -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 rounded-lg max-w-md w-full p-6 shadow-2xl">
        <div class="flex items-center gap-2 text-emerald-500 mb-2">
            <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            <h3 class="text-lg font-bold text-white">Approve Request</h3>
        </div>
        <p class="text-slate-400 mb-4">Assign an employee to confirm this booking.</p>

        <form method="POST" action="{{ url()->current() }}">
            @csrf
            <input type="hidden" name="action" value="confirm">
            <input type="hidden" name="request_id" id="confirm_request_id" value="">

            <div class="space-y-3">
                <label class="block text-sm font-medium text-slate-300 mb-2">Assign Technician *</label>
                <select name="technician" id="confirm_technician" required class="w-full bg-slate-950 border border-slate-700 rounded-md p-2 text-white">
                    <option value="">Select an employee...</option>
                    @foreach ($mockEmployees as $emp)
                        <option value="{{ $emp['name'] }}">{{ $emp['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 text-slate-300 border border-slate-700 rounded-md hover:bg-slate-800">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md">Confirm</button>
            </div>
        </form>
    </div>
</div>

<!-- CANCEL Modal -->
<div id="cancelModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 rounded-lg max-w-md w-full p-6">
        <div class="flex items-center gap-3 text-red-500 mb-2">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            <h3 class="text-lg font-bold">Reject Request</h3>
        </div>
        <p class="text-slate-400">Are you sure you want to cancel this request? This cannot be undone.</p>

        <form method="POST" action="{{ url()->current() }}" class="mt-6 flex justify-end gap-3">
            @csrf
            <input type="hidden" name="action" value="cancel">
            <input type="hidden" name="request_id" id="cancel_request_id" value="">
            <button type="button" onclick="closeCancelModal()" class="px-4 py-2 text-slate-300 border border-slate-700 rounded-md hover:bg-slate-800">No, Go Back</button>
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md font-medium">Yes, Cancel</button>
        </form>
    </div>
</div>

<!-- DELETE Modal (used from view) -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-lg max-w-md w-full">
        <div class="flex items-center gap-3 text-red-500 mb-4">
            <i data-lucide="trash" class="w-6 h-6"></i>
            <h3 class="text-xl font-bold">Delete Request</h3>
        </div>
        <p class="text-slate-400">This will permanently remove the request. Are you sure?</p>
        <form method="POST" action="{{ url()->current() }}" class="mt-6 flex justify-end gap-3">
            @csrf
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="request_id" id="delete_request_id" value="">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-slate-300 border border-slate-700 rounded-md hover:bg-slate-800">No, Go Back</button>
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md font-medium">Yes, Delete</button>
        </form>
    </div>
</div>

<!-- Optional Edit Modal (prefill from view) -->
<div id="editModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 rounded-lg max-w-2xl w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Edit Request</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form method="POST" action="{{ url()->current() }}">
            @csrf
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="request_id" id="edit_request_id" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-slate-300">Customer</label>
                    <input type="text" name="customer" id="edit_customer" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                </div>
                <div>
                    <label class="text-xs text-slate-300">Vehicle</label>
                    <input type="text" name="vehicle" id="edit_vehicle" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                </div>
                <div>
                    <label class="text-xs text-slate-300">Service</label>
                    <input type="text" name="service" id="edit_service" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                </div>
                <div>
                    <label class="text-xs text-slate-300">Assigned Technician</label>
                    <input type="text" name="assignedTo" id="edit_assignedTo" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" placeholder="Leave blank to unassign">
                </div>
                <div>
                    <label class="text-xs text-slate-300">Preferred Date</label>
                    <input type="date" name="date" id="edit_date" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                </div>
                <div>
                    <label class="text-xs text-slate-300">Preferred Time</label>
                    <input type="time" name="time" id="edit_time" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs text-slate-300">Customer Concern / Notes</label>
                    <textarea name="concern" id="edit_concern" rows="3" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white"></textarea>
                </div>
                <div>
                    <label class="text-xs text-slate-300">Status</label>
                    <select name="status" id="edit_status" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="in-queue">In Queue</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-slate-300 border border-slate-700 rounded-md hover:bg-slate-800">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
    // Provide requests & status config to JS for modal population
    const requests = @json(array_values($allRequests));
    const statusConfig = @json($statusConfig);

    // Small utility to toggle per-row dropdown menu (simple)
    function toggleMenu(id) {
        // close others
        document.querySelectorAll('[id^="menu-"]').forEach(m => {
            if (m.id !== id) m.classList.add('hidden');
        });
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.toggle('hidden');
    }

    function closeAllMenus() {
        document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
    }

    document.addEventListener('click', function(e) {
        // close menus when clicking outside
        if (!e.target.closest('[id^="menu-"]') && !e.target.closest('[onclick^="toggleMenu"]')) {
            closeAllMenus();
        }
    });

    // Render lucide icons (if loaded)
    if (window.lucide && typeof lucide.createIcons === 'function') lucide.createIcons();

    // VIEW modal functions
    function openViewModal(id) {
        const req = requests.find(r => String(r.id) === String(id));
        if (!req) return alert('Request not found.');
        document.getElementById('view_req_id').textContent = 'ID: #' + String(req.id).padStart(4, '0');
        document.getElementById('view_customer').textContent = req.customer || '-';
        document.getElementById('view_customer_id').textContent = 'Cust ID: #' + String(req.customerId).padStart(4, '0');
        document.getElementById('view_vehicle').textContent = req.vehicle || '-';
        document.getElementById('view_service').textContent = req.service || '-';
        document.getElementById('view_schedule_date').textContent = req.date || '-';
        document.getElementById('view_schedule_time').textContent = req.time || '';
        document.getElementById('view_assigned').textContent = req.assignedTo || 'Not yet assigned (Pending confirmation)';
        document.getElementById('view_concern').textContent = req.concern || '-';

        // status badge
        const cfg = statusConfig[req.status] || {label: req.status, color: '', icon: 'help-circle'};
        document.getElementById('view_status_badge').innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ${cfg.color}"><i data-lucide="${cfg.icon}" class="w-3 h-3 mr-1.5"></i>${cfg.label}</span>`;
        if (window.lucide && typeof lucide.createIcons === 'function') lucide.createIcons();

        // set ids for confirm/cancel/delete forms
        document.getElementById('confirm_request_id').value = req.id;
        document.getElementById('cancel_request_id').value = req.id;
        document.getElementById('delete_request_id').value = req.id;
        document.getElementById('edit_request_id').value = req.id;

        // prefill edit modal fields
        document.getElementById('edit_customer').value = req.customer || '';
        document.getElementById('edit_vehicle').value = req.vehicle || '';
        document.getElementById('edit_service').value = req.service || '';
        document.getElementById('edit_assignedTo').value = req.assignedTo || '';
        document.getElementById('edit_date').value = req.date && req.date.match(/^\d{4}-\d{2}-\d{2}$/) ? req.date : '';
        // attempt to parse time like "10:00 AM"
        const timeInput = document.getElementById('edit_time');
        if (req.time && req.time.match(/\d{1,2}:\d{2}\s?(AM|PM)/i)) {
            const d = new Date('1970-01-01 ' + req.time);
            const hh = String(d.getHours()).padStart(2,'0');
            const mm = String(d.getMinutes()).padStart(2,'0');
            timeInput.value = hh + ':' + mm;
        } else if (req.time && req.time.match(/^\d{2}:\d{2}$/)) {
            timeInput.value = req.time;
        } else {
            timeInput.value = '';
        }
        document.getElementById('edit_concern').value = req.concern || '';
        document.getElementById('edit_status').value = req.status || 'pending';

        document.getElementById('viewModal').classList.remove('hidden');
        document.body.classList.add('modal-active');

        // close any open menus
        closeAllMenus();
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    // Confirm modal functions
    function openConfirmModal(id) {
        document.getElementById('confirm_request_id').value = id;
        document.getElementById('confirm_technician').value = '';
        document.getElementById('confirmModal').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    function openConfirmModalFromView() {
        const id = document.getElementById('confirm_request_id').value;
        openConfirmModal(id);
    }
    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    // Cancel modal functions
    function openCancelModal(id) {
        document.getElementById('cancel_request_id').value = id;
        document.getElementById('cancelModal').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    function openCancelModalFromView() {
        const id = document.getElementById('cancel_request_id').value;
        openCancelModal(id);
    }
    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    // Delete modal functions
    function openDeleteModal(id) {
        document.getElementById('delete_request_id').value = id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    function openDeleteModalFromView() {
        const id = document.getElementById('delete_request_id').value;
        openDeleteModal(id);
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    // Edit modal
    function openEditModalFromView() {
        document.getElementById('editModal').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }
</script>

<script>
    // Render lucide icons initially and after modal content updates
    if (window.lucide && typeof lucide.createIcons === 'function') lucide.createIcons();
</script>

@endsection