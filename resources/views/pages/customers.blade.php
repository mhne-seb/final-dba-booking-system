{{-- 
    MOCK DATA: Delete this block when you have a real database controller 
--}}
@php
    if(!isset($customers)) {
        $customers = [
            [
                'id' => 1,
                'full_name' => 'Juan dela Cruz',
                'contact_number' => '09123456789',
                'email' => 'juan@email.com',
                'vehicle_type' => 'Sedan',
                'vehicle_brand' => 'Toyota',
                'vehicle_model' => 'Vios',
                'plate_number' => 'ABC 1234'
            ],
            [
                'id' => 2,
                'full_name' => 'Maria Santos',
                'contact_number' => '09234567890',
                'email' => 'maria@email.com',
                'vehicle_type' => 'SUV',
                'vehicle_brand' => 'Honda',
                'vehicle_model' => 'CR-V',
                'plate_number' => 'XYZ 5678'
            ]
        ];
    }
@endphp

@extends('layouts.app')

@section('content')
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(1); }
        .modal-active { overflow: hidden; }
    </style>
</head>

<div class="min-h-screen bg-slate-950 text-white p-6 space-y-8 font-sans">
    
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Customer Information</h1>
            <p class="text-sm text-slate-400 mt-1">Manage customer records and vehicle information</p>
        </div>
        
        <button onclick="openModal('addModal')" class="bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/20 px-5 py-3 rounded-lg flex items-center transition-all transform active:scale-95 font-medium">
            <i class="fas fa-plus mr-2"></i>
            Add Customer
        </button>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-2xl overflow-hidden">
        <div class="p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <h2 class="text-xl font-semibold">All Customers</h2>
                <div class="relative w-full md:w-80">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
                    <input type="text" id="searchInput" placeholder="Search name, plate, ID..." 
                        class="w-full pl-10 bg-slate-800 border border-slate-700 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg py-2.5 transition-all outline-none">
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-800">
                <table class="min-w-full divide-y divide-slate-800">
                    <thead class="bg-slate-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Customer ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Vehicle</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase">Plate Number</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 bg-slate-900/50" id="customerTableBody">
                        @foreach($customers as $customer)
                        <tr class="customer-row hover:bg-slate-800/30 transition-colors" data-search="{{ strtolower($customer['full_name'] . ' ' . $customer['plate_number']) }}">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-slate-400 bg-slate-950 px-2 py-1 rounded border border-slate-800">
                                    #{{ str_pad($customer['id'], 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400 font-bold">
                                        {{ substr($customer['full_name'], 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $customer['full_name'] }}</div>
                                        <div class="text-xs text-slate-500">{{ $customer['email'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-300">{{ $customer['contact_number'] }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-car text-slate-500 text-xs"></i>
                                    <div>
                                        <span class="text-sm text-slate-300">{{ $customer['vehicle_brand'] }} {{ $customer['vehicle_model'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold bg-slate-800 border border-slate-700 px-2 py-1 rounded">{{ $customer['plate_number'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="viewCustomer({{ $customer['id'] }})" class="p-2 hover:bg-blue-500/10 text-slate-400 hover:text-blue-400 rounded-lg transition-colors"><i class="fas fa-eye text-sm"></i></button>
                                    <button onclick="editCustomer({{ $customer['id'] }})" class="p-2 hover:bg-emerald-500/10 text-slate-400 hover:text-emerald-400 rounded-lg transition-colors"><i class="fas fa-edit text-sm"></i></button>
                                    <button onclick="confirmDelete({{ $customer['id'] }}, '{{ $customer['full_name'] }}')" class="p-2 hover:bg-red-500/10 text-slate-400 hover:text-red-400 rounded-lg transition-colors"><i class="fas fa-trash text-sm"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="addModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 w-full max-w-2xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center sticky top-0 bg-slate-900 z-10">
            <div>
                <h3 class="text-xl font-bold">Add New Customer</h3>
                <p class="text-xs text-slate-500">Enter the customer's personal details, vehicle info, and service request.</p>
            </div>
            <button onclick="closeModal('addModal')" class="text-slate-500 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form class="p-6 space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-500 mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-blue-500 rounded-full"></span> Personal Information
                            </h3>
                            <div class="grid gap-5">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Full Name *</label>
                                    <input type="text" name="fullName" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 focus:ring-2 focus:ring-blue-500 outline-none text-white" placeholder="Enter your full name">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Contact Number *</label>
                                        <input type="text" name="contactNumber" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white" placeholder="09123456789">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Email (Optional)</label>
                                        <input type="email" name="email" class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white" placeholder="your@email.com">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-blue-500 mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-blue-500 rounded-full"></span> Vehicle Information
                            </h3>
                            <div class="grid gap-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Vehicle Type *</label>
                                        <select name="vehicleType" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white outline-none">
                                            <option value="">Select type</option>
                                            @foreach(['Sedan', 'SUV', 'Pickup Truck', 'Van', 'Motorcycle', 'Other'] as $type)
                                                <option value="{{ $type }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Brand *</label>
                                        <input type="text" name="vehicleBrand" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white" placeholder="e.g., Toyota">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Model *</label>
                                        <input type="text" name="vehicleModel" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white" placeholder="e.g., Vios">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Plate Number *</label>
                                        <input type="text" name="plateNumber" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white" placeholder="ABC 1234">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-blue-500 mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-blue-500 rounded-full"></span> Service Details
                            </h3>
                            <div class="grid gap-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Preferred Date *</label>
                                        <input type="date" name="preferredDate" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white [color-scheme:dark]">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Preferred Time *</label>
                                        <input type="time" name="preferredTime" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white [color-scheme:dark]">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Service Type *</label>
                                    <select name="serviceType" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white outline-none">
                                        <option value="">Select service</option>
                                        @foreach(['Tire Vulcanizing', 'Tire Replacement', 'Wheel Alignment', 'Wheel Balancing', 'Flat Tire Repair', 'Tire Rotation', 'Other'] as $service)
                                            <option value="{{ $service }}">{{ $service }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Describe Your Concern</label>
                                    <textarea name="concern" rows="4" class="w-full bg-slate-800 border-slate-700 rounded-md p-4 text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Please describe the issue..."></textarea>
                                </div>
                            </div>
                        </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-800">
                <button type="button" onclick="closeModal('addModal')" class="px-4 py-2 text-slate-400 hover:text-white font-bold">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-bold text-white transition-all">Save Customer</button>
            </div>
        </form>
    </div>
</div>

<div id="viewEditModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    </div>

<div id="deleteModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div id="deleteModalContent"></div>
</div>

<script>
    // Data from PHP to JS
    const customers = @json($customers);

    // Search Logic
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.customer-row');
        rows.forEach(row => {
            row.style.display = row.getAttribute('data-search').includes(filter) ? '' : 'none';
        });
    });

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.classList.add('modal-active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    function viewCustomer(id) {
        const customer = customers.find(c => c.id == id);
        const modal = document.getElementById('viewEditModal');
        
        modal.innerHTML = `
            <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-xl shadow-2xl relative overflow-hidden">
                <button onclick="closeModal('viewEditModal')" class="absolute top-4 right-4 text-slate-500 hover:text-white z-10"><i class="fas fa-times"></i></button>
                
                <div class="p-6 border-b border-slate-800 flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center text-2xl font-bold shadow-lg shadow-blue-500/20">
                        ${customer.full_name.charAt(0)}
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">${customer.full_name}</h3>
                        <p class="text-slate-400 text-sm flex items-center gap-2">
                            <i class="fas fa-envelope text-slate-600"></i> ${customer.email}
                        </p>
                    </div>
                </div>

                <div class="p-6 space-y-8">
                    <div class="space-y-4">
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-user"></i> Personal Details
                        </p>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] text-slate-500 uppercase font-bold">Contact Number</label>
                                <p class="text-lg font-bold flex items-center gap-2 mt-1">
                                    <i class="fas fa-phone text-blue-500 text-sm"></i> ${customer.contact_number}
                                </p>
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 uppercase font-bold">Customer ID</label>
                                <p class="mt-1"><span class="bg-slate-950 border border-slate-800 font-mono text-sm px-3 py-1 rounded">#${String(customer.id).padStart(4, '0')}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-slate-800/50">
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-car"></i> Vehicle Information
                        </p>
                        <div class="bg-slate-950/50 border border-slate-800 rounded-xl p-5 flex justify-between items-center">
                            <div>
                                <label class="text-[10px] text-slate-500 uppercase font-bold block mb-1">Vehicle Model</label>
                                <p class="text-xl font-bold">${customer.vehicle_brand} ${customer.vehicle_model}</p>
                                <p class="text-blue-500 text-sm font-medium">${customer.vehicle_type}</p>
                            </div>
                            <div class="bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-center">
                                <label class="text-[10px] text-slate-500 uppercase font-bold block">Plate No.</label>
                                <p class="font-mono font-bold text-lg tracking-tight">${customer.plate_number}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-800/30 flex justify-end gap-3">
                    <button onclick="closeModal('viewEditModal')" class="px-6 py-2 bg-slate-100 text-slate-900 rounded-lg font-bold hover:bg-white transition-colors">Close</button>
                    <button onclick="editCustomer(${customer.id})" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold flex items-center gap-2 shadow-lg shadow-blue-500/10 transition-all">
                        <i class="fas fa-edit"></i> Edit Details
                    </button>
                </div>
            </div>
        `;
        openModal('viewEditModal');
    }

    function editCustomer(id) {
        const customer = customers.find(c => c.id == id);
        const modal = document.getElementById('viewEditModal');
        
        modal.innerHTML = `
            <div class="bg-slate-900 border border-slate-800 w-full max-w-2xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-slate-800 flex justify-between items-center sticky top-0 bg-slate-900 z-10">
                    <h3 class="text-xl font-bold">Edit Customer Records</h3>
                    <button onclick="closeModal('viewEditModal')" class="text-slate-500 hover:text-white"><i class="fas fa-times"></i></button>
                </div>
                <form class="p-6 space-y-6">
                    <div class="space-y-4">
                        <p class="text-blue-500 text-xs font-bold uppercase flex items-center gap-2"><i class="fas fa-user"></i> Personal Information</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="text-xs text-slate-400 font-bold mb-1 block">Full Name *</label>
                                <input type="text" value="${customer.full_name}" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 font-bold mb-1 block">Contact Number *</label>
                                <input type="text" value="${customer.contact_number}" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 outline-none">
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 font-bold mb-1 block">Email</label>
                                <input type="email" value="${customer.email}" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 outline-none">
                            </div>
                        </div>

                        <p class="text-blue-500 text-xs font-bold uppercase flex items-center gap-2 pt-4"><i class="fas fa-car"></i> Vehicle Information</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-slate-400 font-bold mb-1 block">Vehicle Type</label>
                                <select class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 outline-none">
                                    <option ${customer.vehicle_type == 'Sedan' ? 'selected' : ''}>Sedan</option>
                                    <option ${customer.vehicle_type == 'SUV' ? 'selected' : ''}>SUV</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 font-bold mb-1 block">Brand</label>
                                <input type="text" value="${customer.vehicle_brand}" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 outline-none">
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 font-bold mb-1 block">Model</label>
                                <input type="text" value="${customer.vehicle_model}" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 outline-none">
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 font-bold mb-1 block">Plate Number</label>
                                <input type="text" value="${customer.plate_number}" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 font-mono uppercase outline-none">
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-800">
                        <button type="button" onclick="closeModal('viewEditModal')" class="px-4 py-2 text-slate-400 hover:text-white font-bold">Cancel</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-bold">Update Record</button>
                    </div>
                </form>
            </div>
        `;
        openModal('viewEditModal');
    }

    function confirmDelete(id, name) {
        const content = document.getElementById('deleteModalContent');
        content.innerHTML = `
            <div class="bg-slate-900 border border-slate-800 p-8 rounded-xl max-w-md w-full relative">
                <button onclick="closeModal('deleteModal')" class="absolute top-4 right-4 text-slate-500 hover:text-white"><i class="fas fa-times"></i></button>
                <div class="text-left">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                        <h3 class="text-xl font-bold text-white">Confirm Deletion</h3>
                    </div>
                    <p class="text-slate-300 mb-8 leading-relaxed">
                        Are you sure you want to delete <span class="text-white font-bold">${name}</span>? This action cannot be undone.
                    </p>
                    <div class="flex gap-3 justify-end">
                        <button onclick="closeModal('deleteModal')" class="px-6 py-2.5 bg-slate-100 text-slate-900 rounded-lg font-bold hover:bg-white transition-colors">Cancel</button>
                        <button class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg shadow-red-900/20 transition-all">Yes, Delete Record</button>
                    </div>
                </div>
            </div>
        `;
        openModal('deleteModal');
    }
</script>
@endsection