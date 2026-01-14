@extends('layouts.app')

@section('content')

<!-- @php
    // Use real data from controller
    $allBookings = $bookings ?? [];
    
    $filter = request()->query('filter', 'all');
    $filteredBookings = $filter === 'all' ? $allBookings : 
        array_values(array_filter($allBookings, function($b) use ($filter) {
            return $b['status'] === $filter;
        }));
    
    $counts = [
        'all' => count($allBookings),
        'not-started' => count(array_filter($allBookings, function($b) {
            return $b['status'] === 'not-started';
        })),
        'in-progress' => count(array_filter($allBookings, function($b) {
            return $b['status'] === 'in-progress';
        })),
        'stuck' => count(array_filter($allBookings, function($b) {
            return $b['status'] === 'stuck';
        })),
        'done' => count(array_filter($allBookings, function($b) {
            return $b['status'] === 'done';
        })),
    ];
    
    $statusConfig = [
        'not-started' => ['label' => 'Not Started', 'color' => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20', 'icon' => 'clock'],
        'in-progress' => ['label' => 'In Progress', 'color' => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20', 'icon' => 'play'],
        'stuck' => ['label' => 'Stuck', 'color' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20', 'icon' => 'alert-triangle'],
        'done' => ['label' => 'Done', 'color' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-20', 'icon' => 'check-circle-2'],
    ];
@endphp -->

<style>
    body {
        background-color: #0b1120 !important;
    }
    
    .modal-backdrop {
        background: rgba(2, 6, 23, 0.65) !important;
        backdrop-filter: blur(3px) !important;
    }
    
    .custom-bg-dark {
        background-color: #0f172a !important;
    }
    
    .custom-bg-darker {
        background-color: #0d121f !important;
    }
    
    .custom-bg-darkest {
        background-color: #1e293b !important;
    }
    
    .custom-border-dark {
        border-color: #475569 !important;
    }
    
    .custom-text-muted {
        color: #94a3b8 !important;
    }
    
    .tabs-trigger-active {
        background-color: #1e293b !important;
        color: white !important;
        border-radius: 4px !important;
    }
    
    .modal-active {
        overflow: hidden !important;
    }
    
    /* Scrollbar styling */
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #1e293b;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
    
    /* Calendar picker indicator */
    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="time"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-white">Booking & Scheduling</h1>
            <p class="text-muted mb-0">Manage service bookings and assignments</p>
        </div>
    </div>
    
    <div class="row mb-4">
        @foreach(['not-started', 'in-progress', 'stuck', 'done'] as $k)
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card custom-bg-dark border custom-border-dark rounded-3 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-circle {{ $statusConfig[$k]['color'] }} me-3">
                                <i data-lucide="{{ $statusConfig[$k]['icon'] }}" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="h2 fw-bold text-white mb-0">{{ $counts[$k] }}</div>
                                <div class="small text-muted">{{ $statusConfig[$k]['label'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mb-4">
        <div class="d-flex bg-dark border border-secondary rounded-pill p-1 w-fit">
            @foreach(['all', 'not-started', 'in-progress', 'stuck', 'done'] as $f)
                <a href="?filter=<?php echo $f; ?>" 
                   class="btn btn-sm px-3 py-1 me-1 rounded-pill text-decoration-none <?php echo $filter === $f ? 'tabs-trigger-active' : 'text-muted hover-text-white'; ?>">
                    <?php echo ucfirst($f); ?> (<?php echo $counts[$f] ?? $counts['all']; ?>)
                </a>
            @endforeach
        </div>
    </div>
    
    <div class="card custom-bg-dark border custom-border-dark shadow-lg">
        <div class="card-header border-bottom custom-border-dark py-3">
            <h5 class="card-title mb-0 fw-semibold text-white">Bookings List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive custom-scrollbar">
                <table class="table table-dark table-hover mb-0">
                    <thead class="custom-bg-darker">
                        <tr>
                            <th class="border-bottom custom-border-dark py-3 ps-4">Booking ID</th>
                            <th class="border-bottom custom-border-dark py-3">Customer</th>
                            <th class="border-bottom custom-border-dark py-3">Service</th>
                            <th class="border-bottom custom-border-dark py-3">Shop</th>
                            <th class="border-bottom custom-border-dark py-3">Schedule</th>
                            <th class="border-bottom custom-border-dark py-3">Status</th>
                            <th class="border-bottom custom-border-dark py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($filteredBookings) > 0): ?>
                            <?php foreach($filteredBookings as $booking): ?>
                                <tr class="border-bottom custom-border-dark">
                                    <td class="ps-4 py-3">
                                        <span class="badge bg-dark border border-secondary text-white font-monospace px-3 py-2">
                                            #B<?php echo str_pad($booking['id'], 3, '0', STR_PAD_LEFT); ?>
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-medium text-white"><?php echo htmlspecialchars($booking['customer']); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($booking['email']); ?></div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <i data-lucide="car" class="me-2 text-muted" style="width: 16px; height: 16px;"></i>
                                            <span class="text-light"><?php echo htmlspecialchars($booking['service']); ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted"><?php echo htmlspecialchars($booking['shop']); ?></td>
                                    <td class="py-3">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-1">
                                                <i data-lucide="calendar" class="me-2 text-muted" style="width: 14px; height: 14px;"></i>
                                                <span class="text-light"><?php echo htmlspecialchars($booking['date']); ?></span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i data-lucide="clock" class="me-2 text-muted" style="width: 14px; height: 14px;"></i>
                                                <span class="small text-muted"><?php echo htmlspecialchars($booking['time']); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <form method="POST" action="<?php echo route('bookings.status'); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm bg-dark text-light border-secondary" style="width: auto;">
                                                <option value="not-started" <?php echo $booking['status'] === 'not-started' ? 'selected' : ''; ?>>Not Started</option>
                                                <option value="in-progress" <?php echo $booking['status'] === 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="stuck" <?php echo $booking['status'] === 'stuck' ? 'selected' : ''; ?>>Stuck</option>
                                                <option value="done" <?php echo $booking['status'] === 'done' ? 'selected' : ''; ?>>Done</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <button class="btn btn-sm btn-outline-light border-0" 
                                                onclick="openViewModal(<?php echo json_encode($booking['id']); ?>)">
                                            <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i data-lucide="inbox" class="mb-2" style="width: 48px; height: 48px;"></i>
                                    <div>No bookings found.</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content custom-bg-dark border custom-border-dark">
            <div class="modal-header border-bottom custom-border-dark">
                <h5 class="modal-title text-white">Booking Details</h5>
                <div>
                    <button id="printReceiptBtn" class="btn btn-primary btn-sm me-2">Print Receipt</button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <small class="text-uppercase text-muted fw-bold">Customer</small>
                        <div class="h5 fw-bold text-white" id="view_customer"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-uppercase text-muted fw-bold">Vehicle</small>
                        <div class="h5 fw-bold text-white" id="view_vehicle"></div>
                        <div class="text-muted" id="view_plate"></div>
                    </div>
                </div>
                
                <hr class="bg-secondary my-4">
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <small class="text-uppercase text-muted fw-bold">Service</small>
                        <div class="h5 fw-bold text-primary" id="view_service"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-uppercase text-muted fw-bold">Technicians</small>
                        <div class="text-light" id="view_techs">N/A</div>
                    </div>
                </div>
                
                <div class="card custom-bg-darker border custom-border-dark">
                    <div class="card-body">
                        <small class="text-uppercase text-muted fw-bold mb-2 d-block">Billing Information</small>
                        <form id="billingForm" method="POST" action="<?php echo route('bookings.billing'); ?>" class="d-flex align-items-center gap-2">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="booking_id" id="billing_booking_id" value="">
                            <input type="number" step="0.01" name="amount" id="billing_amount" 
                                   class="form-control bg-dark text-white border-secondary h-100" style="font-size: 1.25rem; font-weight: bold;">
                            <button type="submit" class="btn btn-success h-100 px-4 fw-bold">Update Amount</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top custom-border-dark">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form id="deleteFormFromView" method="POST" action="" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <input type="hidden" name="booking_id" id="delete_booking_id" value="">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Convert PHP data to JavaScript safely
    const bookings = <?php echo json_encode($allBookings); ?>;
    const statusConfig = <?php echo json_encode($statusConfig); ?>;
    
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide && lucide.createIcons) {
            lucide.createIcons();
        }
    });

    function openViewModal(id) {
        const b = bookings.find(function(x) { 
            return String(x.id) === String(id); 
        });
        
        if (!b) {
            alert('Booking not found');
            return;
        }
        
        // Update modal content
        document.getElementById('view_customer').textContent = b.customer || '';
        document.getElementById('view_vehicle').textContent = b.vehicle || '';
        document.getElementById('view_plate').textContent = b.plate || '';
        document.getElementById('view_service').textContent = b.service || '';
        document.getElementById('billing_booking_id').value = b.id || '';
        document.getElementById('billing_amount').value = b.amount || '';
        document.getElementById('delete_booking_id').value = b.id || '';
        
        // Update delete form action
        const deleteForm = document.getElementById('deleteFormFromView');
        deleteForm.action = "<?php echo route('bookings.destroy', ''); ?>/" + b.id;

        // Show modal using Bootstrap
        const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
        viewModal.show();
    }

    // Print receipt function
    function printReceiptForBooking(id) {
        const b = bookings.find(function(x) { 
            return String(x.id) === String(id); 
        });
        
        if (!b) {
            alert('Booking not found');
            return;
        }

        const amount = (b.amount !== undefined && b.amount !== null) ? parseFloat(b.amount).toFixed(2) : '0.00';
        const receiptContent = `
            <div style="text-align:center; font-weight:bold; margin-bottom:6px;">
                AUTOCARE VULCANIZING & AUTO SERVICES
            </div>
            <div style="text-align:center; font-size:11px; margin-bottom:8px;">
                M123 Manila City<br/>09XX-XXX-XXXX
            </div>
            ---<br/>
            OFFICIAL RECEIPT<br/>
            ---<br/>
            Rcpt No.: AC-B${String(b.id).padStart(3, '0')}<br/>
            Date: ${b.date}<br/>
            Time: ${b.time}<br/>
            ---<br/>
            CUSTOMER: ${b.customer}<br/>
            Vhcl: ${b.vehicle}<br/>
            Plate: ${b.plate}<br/>
            ------------------------------<br/>
            ${b.service} .......... ${amount}<br/>
            ------------------------------<br/>
            TOTAL: ₱${Number(amount).toLocaleString(undefined,{minimumFractionDigits:2})}<br/>
            ------------------------------<br/>
            Thank you for choosing AutoCare!<br/>
        `;

        const w = window.open('', '_blank', 'width=400,height=800');
        if (!w) {
            alert('Please allow popups to print the receipt.');
            return;
        }
        
        w.document.write('<html><head><title>Receipt</title>');
        w.document.write('<style>body{font-family:monospace;font-size:12px;padding:10px;background:#fff;color:#000}</style>');
        w.document.write('</head><body>');
        w.document.write(receiptContent);
        w.document.write('</body></html>');
        w.document.close();
        w.focus();
        
        setTimeout(function() {
            w.print();
            w.close();
        }, 300);
    }

    // Attach print button behavior
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'printReceiptBtn') {
            const id = document.getElementById('billing_booking_id').value;
            printReceiptForBooking(id);
        }
    });
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = bootstrap.Modal.getInstance(document.getElementById('viewModal'));
            if (modal) {
                modal.hide();
            }
        }
    });
</script>
@endsection