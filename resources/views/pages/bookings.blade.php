@extends('layouts.app')

@section('content')

{{-- Presentation Configuration --}}
@php
    // FIX: Keys changed to use UNDERSCORES to match Database ENUMs
    $ProgressConfig = [
        'not_started' => ['label' => 'Not Started', 'icon' => 'circle',       'color' => 'bg-secondary'],
        'in_progress' => ['label' => 'In Progress', 'icon' => 'loader',       'color' => 'bg-primary'],
        'stuck'       => ['label' => 'Stuck',       'icon' => 'alert-circle', 'color' => 'bg-danger'],
        'done'        => ['label' => 'Done',        'icon' => 'check-circle', 'color' => 'bg-success'],
    ];
@endphp

<style>
    /* Essential Dark Mode Styles */
    body { background-color: #0b1120 !important; color: #f8fafc !important; }
    
    /* Backgrounds */
    .custom-bg-dark { background-color: #1e293b !important; }
    .custom-bg-darker { background-color: #0f172a !important; }
    .custom-border-dark { border-color: #334155 !important; }
    
    /* Text Visibility Overrides */
    .text-muted { color: #94a3b8 !important; } 
    .text-light-head { color: #e2e8f0 !important; }
    h1, h2, h3, h4, h5, h6 { color: #ffffff !important; }
    
    /* Tabs & Buttons */
    .tabs-trigger-active { background-color: #3b82f6 !important; color: white !important; font-weight: bold; }
    .btn-outline-light:hover { background-color: #334155; }
    
    /* Inputs */
    .form-select, .form-control { 
        background-color: #0f172a !important; 
        color: #ffffff !important; 
        border-color: #475569 !important; 
    }
    .form-select:focus, .form-control:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
    }
    
    /* Utility */
    .w-fit { width: fit-content; }
    input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }
</style>

<div class="container-fluid py-4">
    <div class="mb-4">
        <h1 class="h2 fw-bold text-white">Booking & Scheduling</h1>
        <p class="text-light-head mb-0" style="opacity: 0.8;">Manage service bookings and assignments</p>
    </div>
    
    <div class="row mb-4">
        @foreach($ProgressConfig as $key => $config)
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card custom-bg-dark border custom-border-dark rounded-3 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="p-3 rounded-circle {{ $config['color'] }} me-3 bg-opacity-25 text-white">
                            <i data-lucide="{{ $config['icon'] }}" class="w-5 h-5"></i>
                        </div>
                        <div>
                            {{-- This pulls from the mapped $counts array in Controller --}}
                            <div class="h2 fw-bold text-white mb-0">{{ $counts[$key] ?? 0 }}</div>
                            <div class="small text-light-head">{{ $config['label'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mb-4">
        <div class="d-flex bg-dark border border-secondary rounded-pill p-1 w-fit">
            <a href="?filter=all" class="btn btn-sm px-3 py-1 me-1 rounded-pill text-decoration-none {{ ($filter ?? 'all') === 'all' ? 'tabs-trigger-active' : 'text-light-head' }}">
                All ({{ $counts['all'] ?? 0 }})
            </a>
            @foreach($ProgressConfig as $key => $config)
                <a href="?filter={{ $key }}" 
                   class="btn btn-sm px-3 py-1 me-1 rounded-pill text-decoration-none {{ ($filter ?? 'all') === $key ? 'tabs-trigger-active' : 'text-light-head' }}">
                    {{ $config['label'] }} ({{ $counts[$key] ?? 0 }})
                </a>
            @endforeach
        </div>
    </div>
    
    <div class="card custom-bg-dark border custom-border-dark shadow-lg">
        <div class="card-header border-bottom custom-border-dark py-3">
            <h5 class="card-title mb-0 fw-bold text-white">Bookings List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead class="custom-bg-darker">
                        <tr>
                            <th class="border-bottom custom-border-dark py-3 ps-4 text-light-head">ID</th>
                            <th class="border-bottom custom-border-dark py-3 text-light-head">Customer</th>
                            <th class="border-bottom custom-border-dark py-3 text-light-head">Service</th>
                            <th class="border-bottom custom-border-dark py-3 text-light-head">Shop</th>
                            <th class="border-bottom custom-border-dark py-3 text-light-head">Schedule</th>
                            <th class="border-bottom custom-border-dark py-3 text-light-head">Progress</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr class="border-bottom custom-border-dark align-middle">
                                <td class="ps-4 py-3">
                                    <span class="badge bg-dark border border-secondary text-white font-monospace px-3 py-2">
                                        #{{ str_pad($booking->in_schedule_id, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <div class="fw-bold text-white">{{ $booking->customer_name }}</div>
                                    <div class="small text-muted">{{ $booking->customer_email }}</div>
                                </td>
                                <td class="py-3">
                                    <i data-lucide="car" class="me-2 text-muted" style="width: 14px;"></i>
                                    <span class="text-light">{{ $booking->service_type }}</span>
                                </td>
                                <td class="py-3 text-light">{{ $booking->name }}</td>
                                <td class="py-3">
                                    <div class="text-white">{{ $booking->date_started }}</div>
                                    <div class="small text-muted">{{ $booking->date_ended }}</div>
                                </td>
                                <td class="py-3">
                                    <form method="POST" action="{{ route('bookings.update', $booking->in_schedule_id) }}" class="d-inline">
                                        @csrf
                                        {{-- NOTE: Ensure your route uses PUT or PATCH, otherwise add @method('PUT') --}}
                                        <input type="hidden" name="booking_id" value="{{ $booking->in_schedule_id }}">
                                        
                                        {{-- Dropdown: Uses keys (not_started) matching the DB --}}
                                        <select name="progress" onchange="this.form.submit()" class="form-select form-select-sm w-auto cursor-pointer">
                                            @foreach($ProgressConfig as $key => $config)
                                                <option value="{{ $key }}" {{ $booking->progress === $key ? 'selected' : '' }}>
                                                    {{ $config['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
            
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i data-lucide="inbox" class="mb-2 mx-auto" style="width: 48px; height: 48px;"></i>
                                    <div>No bookings found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content custom-bg-dark border custom-border-dark text-white">
            <div class="modal-header border-bottom custom-border-dark">
                <h5 class="modal-title fw-bold text-white">Booking Details</h5>
                <div>
                    <button id="printBtn" class="btn btn-primary btn-sm me-2 fw-bold">Print Receipt</button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-6 mb-3">
                        <small class="text-uppercase text-muted fw-bold">Customer</small>
                        <div class="h5 fw-bold text-white" id="modal_customer"></div>
                    </div>
                    <div class="col-6 mb-3">
                        <small class="text-uppercase text-muted fw-bold">Vehicle</small>
                        <div class="h5 fw-bold text-white" id="modal_vehicle"></div>
                        <div class="text-light-head" id="modal_plate"></div>
                    </div>
                    <div class="col-6 mb-3">
                        <small class="text-uppercase text-muted fw-bold">Service</small>
                        <div class="h5 fw-bold text-primary" id="modal_service"></div>
                    </div>
                    <div class="col-6 mb-3">
                        <small class="text-uppercase text-muted fw-bold">Technicians</small>
                        <div class="text-white" id="modal_techs">N/A</div>
                    </div>
                </div>
                
                <div class="card custom-bg-darker border custom-border-dark">
                    <div class="card-body">
                        <small class="text-uppercase text-muted fw-bold mb-2 d-block">Billing</small>
                        <form method="POST" action="#" class="d-flex gap-2">
                            @csrf
                            <input type="hidden" name="booking_id" id="modal_id">
                            <input type="number" step="0.01" name="amount" id="modal_amount" 
                                   class="form-control fw-bold fs-5" placeholder="0.00">
                            <button type="submit" class="btn btn-success fw-bold">Update</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top custom-border-dark">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Print Logic
    document.getElementById('printBtn').addEventListener('click', () => {
        const id = document.getElementById('modal_id').value;
        const b = bookings.find(x => String(x.in_schedule_id) === String(id));
        if(!b) return;

        const amt = parseFloat(b.amount || 0).toFixed(2);
        const win = window.open('', '_blank', 'width=400,height=600');
        
        win.document.write(`
            <html><body style="font-family:monospace; padding:20px; text-align:center;">
                <h3>AUTOCARE SERVICES</h3>
                <p>Receipt #B${String(b.in_schedule_id).padStart(3,'0')}</p>
                <hr>
                <div style="text-align:left;">
                    Customer: ${b.customer_name}<br>
                    Service: ${b.service_type}<br>
                </div>
                <hr>
                <h2>Total: ₱${amt}</h2>
                <hr>
                <p>Thank you!</p>
            </body></html>
        `);
        win.document.close();
        win.focus();
        setTimeout(() => { win.print(); win.close(); }, 500);
    });
</script>
@endsection