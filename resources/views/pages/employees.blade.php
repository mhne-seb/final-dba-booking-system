@extends('layouts.app')
@section('content')

@php
// -------------------------------------------------------------------------
// Employees & Shops - Demo Blade (uses session storage for demo purposes)
// -------------------------------------------------------------------------
// Seed demo data (only once per session)
if (!session()->has('employees')) {
    session([
        'employees' => [
            ['id' => 'emp1', 'full_name' => 'Mike Johnson', 'position' => 'Senior Technician', 'contact' => '09111222333', 'shop' => 'Main Branch', 'status' => 'active'],
            ['id' => 'emp2', 'full_name' => 'John Smith', 'position' => 'Technician', 'contact' => '09222333444', 'shop' => 'Main Branch', 'status' => 'active'],
            ['id' => 'emp3', 'full_name' => 'Carlos Garcia', 'position' => 'Technician', 'contact' => '09333444555', 'shop' => 'Branch 2', 'status' => 'active'],
            ['id' => 'emp4', 'full_name' => 'Ana Martinez', 'position' => 'Junior Technician', 'contact' => '09444555666', 'shop' => 'Main Branch', 'status' => 'inactive'],
        ]
    ]);
}

if (!session()->has('shops')) {
    session([
        'shops' => [
            ['id' => 'shop1', 'name' => 'Main Branch', 'address' => '123 Main Street, City Center', 'phone' => '02-1234567', 'status' => 'active'],
            ['id' => 'shop2', 'name' => 'Branch 2', 'address' => '456 Side Street, Downtown', 'phone' => '02-7654321', 'status' => 'active'],
        ]
    ]);
}

// -------------------------------------------------------------------------
// Handle POST actions (demo-only; in a real app move to controller)
// Supported actions: add_employee, edit_employee, delete_employee,
//                   add_shop, edit_shop, delete_shop
// -------------------------------------------------------------------------
if (request()->isMethod('post')) {
    $action = request()->input('action', '');
    $employees = session('employees', []);
    $shops = session('shops', []);

    // ---------- EMPLOYEES ----------
    if ($action === 'add_employee') {
        $nextId = 'emp' . (count($employees) + 1) . '_' . time();
        $employees[] = [
            'id' => $nextId,
            'full_name' => request()->input('full_name', 'Unnamed'),
            'position' => request()->input('position', ''),
            'contact' => request()->input('contact', ''),
            'shop' => request()->input('shop', ''),
            'status' => request()->input('status', 'active'),
        ];
    } elseif ($action === 'edit_employee') {
        $id = request()->input('employee_id', '');
        foreach ($employees as &$e) {
            if ($e['id'] === $id) {
                $e['full_name'] = request()->input('full_name', $e['full_name']);
                $e['position'] = request()->input('position', $e['position']);
                $e['contact'] = request()->input('contact', $e['contact']);
                $e['shop'] = request()->input('shop', $e['shop']);
                $e['status'] = request()->input('status', $e['status']);
            }
        }
        unset($e);
    } elseif ($action === 'delete_employee') {
        $id = request()->input('employee_id', '');
        $employees = array_values(array_filter($employees, fn($x) => $x['id'] !== $id));
    }

    // ---------- SHOPS ----------
    if ($action === 'add_shop') {
        $nextId = 'shop' . (count($shops) + 1) . '_' . time();
        $shops[] = [
            'id' => $nextId,
            'name' => request()->input('name', 'New Shop'),
            'address' => request()->input('address', ''),
            'phone' => request()->input('phone', ''),
            'status' => request()->input('status', 'active'),
        ];
    } elseif ($action === 'edit_shop') {
        $id = request()->input('shop_id', '');
        foreach ($shops as &$s) {
            if ($s['id'] === $id) {
                $s['name'] = request()->input('name', $s['name']);
                $s['address'] = request()->input('address', $s['address']);
                $s['phone'] = request()->input('phone', $s['phone']);
                $s['status'] = request()->input('status', $s['status']);
            }
        }
        unset($s);
    } elseif ($action === 'delete_shop') {
        $id = request()->input('shop_id', '');
        // Before deleting shop, set employees assigned to it to no shop
        foreach ($employees as &$e) {
            if ($e['shop'] === array_values(array_filter($shops, fn($sh) => $sh['id'] === $id))[0]['name'] ?? null) {
                $e['shop'] = '';
            }
        }
        unset($e);
        $shops = array_values(array_filter($shops, fn($x) => $x['id'] !== $id));
    }

    // persist
    session(['employees' => $employees, 'shops' => $shops]);

    // redirect back to avoid form resubmission
    return redirect(request()->url());
}

// -------------------------------------------------------------------------
// Prepare data for view
// -------------------------------------------------------------------------
$employees = session('employees', []);
$shops = session('shops', []);

// For shop-select inputs we use shop names
$shopNames = array_map(fn($s) => $s['name'], $shops);

// counts
$counts = [
    'employees' => count($employees),
    'shops' => count($shops),
];

@endphp

<!-- Assets -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>document.addEventListener('DOMContentLoaded', ()=>{ if(window.lucide && lucide.createIcons) lucide.createIcons(); })</script>

<style>
    .modal-active { overflow: hidden; }
    .badge-active { background-color: #064e3b; color: #bbf7d0; padding: 4px 8px; border-radius: 9999px; font-size: 12px; display:inline-block; }
    .badge-inactive { background-color: #0f172a; color: #94a3b8; padding: 4px 8px; border-radius: 9999px; font-size: 12px; display:inline-block; }
</style>

<div class="min-h-screen p-6 max-w-7xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Employees & Shop Management</h1>
            <p class="text-slate-400 mt-1">Manage your workforce and shop locations</p>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <button id="tabEmployees" class="px-4 py-2 bg-slate-800 text-slate-200 rounded shadow-sm">Employees</button>
        <button id="tabShops" class="px-4 py-2 bg-slate-900 text-slate-400 rounded hover:bg-slate-800">Shops</button>
    </div>

    <!-- EMPLOYEES PANEL -->
    <div id="panelEmployees" class="mt-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-white">Employee List</h2>
                <p class="text-slate-400 text-sm">Manage technicians and staff</p>
            </div>
            <div class="flex items-center gap-3">
                <input id="employeeSearch" type="text" placeholder="Search employees..." class="bg-slate-900 border border-slate-800 rounded px-3 py-2 text-slate-300" />
                <button onclick="openAddEmployee()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">+ Add Employee</button>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-xl overflow-hidden mt-6">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-slate-300 text-sm font-semibold">
                            <tr>
                                <th class="p-4 border-b border-slate-800">Name</th>
                                <th class="p-4 border-b border-slate-800">Position</th>
                                <th class="p-4 border-b border-slate-800">Contact</th>
                                <th class="p-4 border-b border-slate-800">Assigned Shop</th>
                                <th class="p-4 border-b border-slate-800">Status</th>
                                <th class="p-4 border-b border-slate-800 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employeesTable" class="divide-y divide-slate-800">
                            @foreach ($employees as $emp)
                                <tr class="hover:bg-slate-800/30 transition-colors employee-row" data-search="{{ strtolower($emp['full_name'] . ' ' . $emp['position'] . ' ' . $emp['shop']) }}">
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400 font-bold">
                                                {{ strtoupper(substr($emp['full_name'],0,1)) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-white">{{ $emp['full_name'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-slate-300">{{ $emp['position'] }}</td>
                                    <td class="p-4 text-slate-300">{{ $emp['contact'] }}</td>
                                    <td class="p-4 text-slate-300">{{ $emp['shop'] ?: '—' }}</td>
                                    <td class="p-4">
                                        @if ($emp['status'] === 'active')
                                            <span class="badge-active">Active</span>
                                        @else
                                            <span class="badge-inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="inline-flex items-center gap-2 justify-end">
                                            <button onclick="openEditEmployee('{{ $emp['id'] }}')" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </button>
                                            <button onclick="openDeleteEmployee('{{ $emp['id'] }}')" class="p-2 text-red-400 hover:text-red-300 hover:bg-slate-800 rounded">
                                                <i data-lucide="trash" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if (count($employees) === 0)
                                <tr><td colspan="6" class="p-6 text-center text-slate-400">No employees found.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SHOPS PANEL -->
    <div id="panelShops" class="mt-6 hidden">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-white">Shops</h2>
                <p class="text-slate-400 text-sm">Manage shop locations</p>
            </div>
            <div>
                <button onclick="openAddShop()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">+ Add Shop</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            @foreach ($shops as $shop)
                @php
                    // count employees assigned
                    $assigned = array_values(array_filter($employees, fn($e)=> $e['shop'] === $shop['name']));
                @endphp
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-3">
                                <div class="p-3 rounded-lg bg-slate-800/40">
                                    <i data-lucide="home" class="w-5 h-5 text-slate-300"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-white">{{ $shop['name'] }}</div>
                                    <div class="text-sm text-slate-400 mt-1">{{ $shop['status'] === 'active' ? 'Active' : 'Inactive' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="openEditShop('{{ $shop['id'] }}')" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <button onclick="openDeleteShop('{{ $shop['id'] }}')" class="p-2 text-red-400 hover:text-red-300 hover:bg-slate-800 rounded">
                                <i data-lucide="trash" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="bg-slate-950 p-3 rounded border border-slate-800 text-slate-300">
                            <i data-lucide="map-pin" class="w-4 h-4 inline-block mr-2"></i> {{ $shop['address'] }}
                        </div>
                        <div class="bg-slate-950 p-3 rounded border border-slate-800 text-slate-300">
                            <i data-lucide="phone" class="w-4 h-4 inline-block mr-2"></i> {{ $shop['phone'] }}
                        </div>
                        <div class="bg-slate-950 p-3 rounded border border-slate-800 text-slate-300">
                            <i data-lucide="users" class="w-4 h-4 inline-block mr-2"></i> {{ count($assigned) }} employees assigned
                        </div>
                    </div>
                </div>
            @endforeach

            @if (count($shops) === 0)
                <div class="p-6 text-slate-400">No shops configured.</div>
            @endif
        </div>
    </div>
</div>

<!-- ----------------------- Employee Modals ----------------------- -->

<!-- Add Employee -->
<div id="addEmployeeModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 rounded-lg max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Add New Employee</h3>
            <button onclick="closeAddEmployee()" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="{{ url()->current() }}">
            @csrf
            <input type="hidden" name="action" value="add_employee" />
            <div class="space-y-3">
                <label class="text-xs text-slate-300">Full Name</label>
                <input name="full_name" type="text" required class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" placeholder="e.g. Juan dela Cruz" />

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-slate-300">Position</label>
                        <input name="position" type="text" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" placeholder="e.g. Technician" />
                    </div>
                    <div>
                        <label class="text-xs text-slate-300">Contact No.</label>
                        <input name="contact" type="text" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" placeholder="09XX..." />
                    </div>
                </div>

                <div>
                    <label class="text-xs text-slate-300">Assigned Shop</label>
                    <select name="shop" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                        <option value="">Select Shop</option>
                        @foreach ($shopNames as $sname)
                            <option value="{{ $sname }}">{{ $sname }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-300">Status</label>
                    <select name="status" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-3">
                <button type="button" onclick="closeAddEmployee()" class="px-4 py-2 text-slate-300 border border-slate-700 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Employee -->
<div id="editEmployeeModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 rounded-lg max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Edit Employee</h3>
            <button onclick="closeEditEmployee()" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="editEmployeeForm" method="POST" action="{{ url()->current() }}">
            @csrf
            <input type="hidden" name="action" value="edit_employee" />
            <input type="hidden" name="employee_id" id="edit_employee_id" value="" />

            <div class="space-y-3">
                <label class="text-xs text-slate-300">Full Name</label>
                <input id="edit_full_name" name="full_name" type="text" required class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-slate-300">Position</label>
                        <input id="edit_position" name="position" type="text" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />
                    </div>
                    <div>
                        <label class="text-xs text-slate-300">Contact No.</label>
                        <input id="edit_contact" name="contact" type="text" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />
                    </div>
                </div>

                <div>
                    <label class="text-xs text-slate-300">Assigned Shop</label>
                    <select id="edit_shop" name="shop" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                        <option value="">Select Shop</option>
                        @foreach ($shopNames as $sname)
                            <option value="{{ $sname }}">{{ $sname }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-300">Status</label>
                    <select id="edit_status" name="status" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-3">
                <button type="button" onclick="closeEditEmployee()" class="px-4 py-2 text-slate-300 border border-slate-700 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Employee -->
<div id="deleteEmployeeModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 rounded-lg max-w-sm w-full p-6">
        <h3 class="text-lg font-bold text-white mb-2">Delete Employee</h3>
        <p class="text-slate-400 mb-4">Are you sure you want to remove this employee? This action cannot be undone.</p>
        <form id="deleteEmployeeForm" method="POST" action="{{ url()->current() }}">
            @csrf
            <input type="hidden" name="action" value="delete_employee" />
            <input type="hidden" name="employee_id" id="delete_employee_id" value="" />
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDeleteEmployee()" class="px-4 py-2 text-slate-300 border border-slate-700 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>

<!-- ----------------------- Shop Modals ----------------------- -->

<!-- Add Shop -->
<div id="addShopModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 rounded-lg max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Add Shop</h3>
            <button onclick="closeAddShop()" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="{{ url()->current() }}">
            @csrf
            <input type="hidden" name="action" value="add_shop" />
            <div class="space-y-3">
                <label class="text-xs text-slate-300">Shop Name</label>
                <input name="name" type="text" required class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />

                <label class="text-xs text-slate-300">Address</label>
                <input name="address" type="text" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />

                <label class="text-xs text-slate-300">Contact Number</label>
                <input name="phone" type="text" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />

                <label class="text-xs text-slate-300">Status</label>
                <select name="status" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="mt-4 flex justify-end gap-3">
                <button type="button" onclick="closeAddShop()" class="px-4 py-2 text-slate-300 border border-slate-700 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Save Shop</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Shop -->
<div id="editShopModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 rounded-lg max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Edit Shop</h3>
            <button onclick="closeEditShop()" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="editShopForm" method="POST" action="{{ url()->current() }}">
            @csrf
            <input type="hidden" name="action" value="edit_shop" />
            <input type="hidden" name="shop_id" id="edit_shop_id" value="" />
            <div class="space-y-3">
                <label class="text-xs text-slate-300">Shop Name</label>
                <input id="edit_shop_name" name="name" type="text" required class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />

                <label class="text-xs text-slate-300">Address</label>
                <input id="edit_shop_address" name="address" type="text" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />

                <label class="text-xs text-slate-300">Contact Number</label>
                <input id="edit_shop_phone" name="phone" type="text" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white" />

                <label class="text-xs text-slate-300">Status</label>
                <select id="edit_shop_status" name="status" class="w-full bg-slate-800 border border-slate-700 rounded p-2 text-white">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="mt-4 flex justify-end gap-3">
                <button type="button" onclick="closeEditShop()" class="px-4 py-2 text-slate-300 border border-slate-700 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Shop -->
<div id="deleteShopModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50">
    <div class="bg-slate-900 border border-slate-800 rounded-lg max-w-sm w-full p-6">
        <h3 class="text-lg font-bold text-white mb-2">Delete Shop</h3>
        <p class="text-slate-400 mb-4">Deleting a shop will unassign employees assigned to it. Continue?</p>
        <form id="deleteShopForm" method="POST" action="{{ url()->current() }}">
            @csrf
            <input type="hidden" name="action" value="delete_shop" />
            <input type="hidden" name="shop_id" id="delete_shop_id" value="" />
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDeleteShop()" class="px-4 py-2 text-slate-300 border border-slate-700 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>

<!-- ----------------------- Scripts ----------------------- -->
<script>
    // Simple tab handling
    const tabEmployees = document.getElementById('tabEmployees');
    const tabShops = document.getElementById('tabShops');
    const panelEmployees = document.getElementById('panelEmployees');
    const panelShops = document.getElementById('panelShops');

    tabEmployees.addEventListener('click', () => {
        panelEmployees.classList.remove('hidden');
        panelShops.classList.add('hidden');
        tabEmployees.classList.remove('bg-slate-900'); tabEmployees.classList.add('bg-slate-800');
        tabShops.classList.remove('bg-slate-800'); tabShops.classList.add('bg-slate-900');
    });
    tabShops.addEventListener('click', () => {
        panelShops.classList.remove('hidden');
        panelEmployees.classList.add('hidden');
        tabShops.classList.remove('bg-slate-900'); tabShops.classList.add('bg-slate-800');
        tabEmployees.classList.remove('bg-slate-800'); tabEmployees.classList.add('bg-slate-900');
    });

    // -------------------- Employee modals --------------------
    function openAddEmployee() {
        document.getElementById('addEmployeeModal').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    function closeAddEmployee() {
        document.getElementById('addEmployeeModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    function openEditEmployee(id) {
        // find employee by id from server-provided array
        const employees = @json($employees);
        const e = employees.find(x => x.id === id);
        if (!e) return alert('Employee not found');
        document.getElementById('edit_employee_id').value = e.id;
        document.getElementById('edit_full_name').value = e.full_name;
        document.getElementById('edit_position').value = e.position;
        document.getElementById('edit_contact').value = e.contact;
        document.getElementById('edit_shop').value = e.shop || '';
        document.getElementById('edit_status').value = e.status || 'active';
        document.getElementById('editEmployeeModal').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    function closeEditEmployee() {
        document.getElementById('editEmployeeModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    function openDeleteEmployee(id) {
        document.getElementById('delete_employee_id').value = id;
        document.getElementById('deleteEmployeeModal').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    function closeDeleteEmployee() {
        document.getElementById('deleteEmployeeModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    // -------------------- Shop modals --------------------
    function openAddShop() {
        document.getElementById('addShopModal').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    function closeAddShop() {
        document.getElementById('addShopModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    function openEditShop(id) {
        const shops = @json($shops);
        const s = shops.find(x => x.id === id);
        if (!s) return alert('Shop not found');
        document.getElementById('edit_shop_id').value = s.id;
        document.getElementById('edit_shop_name').value = s.name;
        document.getElementById('edit_shop_address').value = s.address;
        document.getElementById('edit_shop_phone').value = s.phone;
        document.getElementById('edit_shop_status').value = s.status || 'active';
        document.getElementById('editShopModal').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    function closeEditShop() {
        document.getElementById('editShopModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    function openDeleteShop(id) {
        document.getElementById('delete_shop_id').value = id;
        document.getElementById('deleteShopModal').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    function closeDeleteShop() {
        document.getElementById('deleteShopModal').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    // -------------------- Search filter for employees --------------------
    document.getElementById('employeeSearch').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.employee-row').forEach(row => {
            const text = row.getAttribute('data-search') || '';
            row.style.display = text.includes(q) ? '' : 'none';
        });
    });

    // close modals when pressing Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeAddEmployee(); closeEditEmployee(); closeDeleteEmployee();
            closeAddShop(); closeEditShop(); closeDeleteShop();
        }
    });

    // render lucide icons inside dynamically opened modals
    document.addEventListener('click', () => {
        if (window.lucide && typeof lucide.createIcons === 'function') lucide.createIcons();
    });
</script>

@endsection