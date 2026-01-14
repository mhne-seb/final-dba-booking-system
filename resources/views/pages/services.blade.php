@extends('layouts.app')

@section('content')

@php
    // Use real data from controller
    $allRequests = $requests ?? [];
    $employees = $mockEmployees ?? [];
    
    $filter = request()->query('filter', 'all');
    $filteredRequests = $filter === 'all' ? $allRequests : 
        array_values(array_filter($allRequests, function($r) use ($filter) {
            return $r['status'] === $filter;
        }));
    
    $counts = [
        'all' => count($allRequests),
        'pending' => count(array_filter($allRequests, function($r) {
            return $r['status'] === 'pending';
        })),
        'confirmed' => count(array_filter($allRequests, function($r) {
            return $r['status'] === 'confirmed';
        })),
        'in-queue' => count(array_filter($allRequests, function($r) {
            return $r['status'] === 'in-queue';
        })),
        'cancelled' => count(array_filter($allRequests, function($r) {
            return $r['status'] === 'cancelled';
        })),
    ];
    
    $statusConfig = [
        'pending' => ['label' => 'Pending', 'color' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20', 'icon' => 'clock'],
        'confirmed' => ['label' => 'Confirmed', 'color' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-20', 'icon' => 'check-circle-2'],
        'in-queue' => ['label' => 'In Queue', 'color' => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20', 'icon' => 'alert-circle'],
        'cancelled' => ['label' => 'Cancelled', 'color' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20', 'icon' => 'x-circle'],
    ];
@endphp

<style>
    body {
        background-color: #0b1120 !important;
    }
    
    .custom-bg-dark {
        background-color: #0f172a !important;
    }
    
    .custom-bg-darker {
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
</style>

<div class="container-fluid py-4">
    <div class="mb-4">
        <h1 class="h2 fw-bold text-white">Service Requests</h1>
        <p class="custom-text-muted mb-4">Manage and process customer service requests</p>
    </div>
    
    <!-- Filter Tabs -->
    <div class="d-flex bg-dark border border-secondary rounded-pill p-1 mb-4 w-fit">
        <?php foreach(['all', 'pending', 'confirmed', 'in-queue', 'cancelled'] as $f): ?>
            <a href="?filter=<?php echo $f; ?>" 
               class="btn btn-sm px-3 py-1 me-1 rounded-pill text-decoration-none <?php echo $filter === $f ? 'tabs-trigger-active text-white' : 'custom-text-muted'; ?>">
                <?php echo ucfirst($f); ?> (<?php echo $counts[$f]; ?>)
            </a>
        <?php endforeach; ?>
    </div>
    
    <!-- Request List -->
    <div class="card custom-bg-dark border custom-border-dark shadow-lg">
        <div class="card-header border-bottom custom-border-dark">
            <h5 class="card-title mb-0 fw-semibold text-white">Request List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead class="custom-bg-darker">
                        <tr>
                            <th class="border-bottom custom-border-dark py-3 ps-4">Cust. ID</th>
                            <th class="border-bottom custom-border-dark py-3">Customer</th>
                            <th class="border-bottom custom-border-dark py-3">Vehicle</th>
                            <th class="border-bottom custom-border-dark py-3">Service</th>
                            <th class="border-bottom custom-border-dark py-3">Preferred Date</th>
                            <th class="border-bottom custom-border-dark py-3">Assigned To</th>
                            <th class="border-bottom custom-border-dark py-3">Status</th>
                            <th class="border-bottom custom-border-dark py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($filteredRequests) > 0): ?>
                            <?php foreach($filteredRequests as $request): ?>
                                <?php $cfg = $statusConfig[$request['status']] ?? ['label'=>'Unknown','color'=>'','icon'=>'help-circle']; ?>
                                <tr class="border-bottom custom-border-dark">
                                    <td class="ps-4 py-3">
                                        <span class="badge bg-dark border border-secondary text-muted font-monospace">
                                            #<?php echo str_pad($request['customerId'], 4, '0', STR_PAD_LEFT); ?>
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-medium text-white"><?php echo htmlspecialchars($request['customer']); ?></div>
                                        <div class="small custom-text-muted" title="<?php echo htmlspecialchars($request['concern']); ?>">
                                            Note: <?php echo mb_strimwidth(htmlspecialchars($request['concern']), 0, 50, '...'); ?>
                                        </div>
                                    </td>
                                    <td class="py-3 custom-text-muted"><?php echo htmlspecialchars($request['vehicle']); ?></td>
                                    <td class="py-3">
                                        <span class="badge bg-dark text-light"><?php echo htmlspecialchars($request['service']); ?></span>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-light"><?php echo htmlspecialchars($request['date']); ?></div>
                                        <div class="small custom-text-muted"><?php echo htmlspecialchars($request['time']); ?></div>
                                    </td>
                                    <td class="py-3">
                                        <?php if($request['assignedTo']): ?>
                                            <div class="d-flex align-items-center">
                                                <i data-lucide="user" class="text-primary me-2" style="width: 12px; height: 12px;"></i>
                                                <span class="text-light"><?php echo htmlspecialchars($request['assignedTo']); ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="small fst-italic custom-text-muted">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge <?php echo $cfg['color']; ?> px-3 py-2">
                                            <i data-lucide="<?php echo $cfg['icon']; ?>" class="me-1" style="width: 12px; height: 12px;"></i>
                                            <?php echo $cfg['label']; ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-outline-light border-0" 
                                                    onclick="openViewModal('<?php echo $request['id']; ?>')">
                                                <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                            </button>
                                            <?php if($request['status'] === 'pending'): ?>
                                                <button class="btn btn-sm btn-outline-success border-0" 
                                                        onclick="openConfirmModal('<?php echo $request['id']; ?>')">
                                                    <i data-lucide="check-circle-2" style="width: 16px; height: 16px;"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger border-0" 
                                                        onclick="openCancelModal('<?php echo $request['id']; ?>')">
                                                    <i data-lucide="x-circle" style="width: 16px; height: 16px;"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 custom-text-muted">
                                    <i data-lucide="inbox" class="mb-3" style="width: 48px; height: 48px;"></i>
                                    <div>No requests found.</div>
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
        <div class="modal-content custom-bg-dark border custom-border-dark" id="viewModalContent">
            <!-- Content loaded via JavaScript -->
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content custom-bg-dark border custom-border-dark">
            <div class="modal-header border-bottom custom-border-dark">
                <div class="d-flex align-items-center">
                    <i data-lucide="check-circle-2" class="text-success me-2" style="width: 24px; height: 24px;"></i>
                    <h5 class="modal-title text-white">Approve Request</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo url()->current(); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="confirm">
                <input type="hidden" name="request_id" id="confirm_request_id" value="">
                <div class="modal-body">
                    <p class="custom-text-muted mb-3">Assign an employee to confirm this booking.</p>
                    <div class="mb-3">
                        <label class="form-label text-light">Assign Technician</label>
                        <select name="technician" id="confirm_technician" required 
                                class="form-select bg-dark text-white border-secondary">
                            <option value="">Select an employee...</option>
                            <?php foreach($employees as $emp): ?>
                                <option value="<?php echo htmlspecialchars($emp['name']); ?>">
                                    <?php echo htmlspecialchars($emp['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top custom-border-dark">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content custom-bg-dark border custom-border-dark">
            <div class="modal-header border-bottom custom-border-dark">
                <div class="d-flex align-items-center">
                    <i data-lucide="alert-triangle" class="text-danger me-2" style="width: 24px; height: 24px;"></i>
                    <h5 class="modal-title text-white">Reject Request</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="custom-text-muted">Are you sure you want to cancel this request? This cannot be undone.</p>
            </div>
            <div class="modal-footer border-top custom-border-dark">
                <form method="POST" action="<?php echo url()->current(); ?>" class="d-flex gap-2">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="cancel">
                    <input type="hidden" name="request_id" id="cancel_request_id" value="">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Go Back</button>
                    <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content custom-bg-dark border custom-border-dark">
            <div class="modal-header border-bottom custom-border-dark">
                <div class="d-flex align-items-center">
                    <i data-lucide="trash" class="text-danger me-2" style="width: 24px; height: 24px;"></i>
                    <h5 class="modal-title text-white">Delete Request</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="custom-text-muted">This will permanently remove the request. Are you sure?</p>
            </div>
            <div class="modal-footer border-top custom-border-dark">
                <form method="POST" action="<?php echo url()->current(); ?>" class="d-flex gap-2">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="request_id" id="delete_request_id" value="">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Go Back</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Convert PHP data to JavaScript
    const requests = <?php echo json_encode($allRequests); ?>;
    const statusConfig = <?php echo json_encode($statusConfig); ?>;
    
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide && lucide.createIcons) {
            lucide.createIcons();
        }
        
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    
    // View modal functions
    function openViewModal(id) {
        const req = requests.find(function(r) { 
            return String(r.id) === String(id); 
        });
        
        if (!req) {
            alert('Request not found.');
            return;
        }
        
        const cfg = statusConfig[req.status] || {label: req.status, color: "", icon: "help-circle"};
        
        const modalContent = `
            <div class="modal-header border-bottom custom-border-dark">
                <div>
                    <h5 class="modal-title text-white">Request Details</h5>
                    <p class="small custom-text-muted mb-0">ID: #${String(req.id).padStart(4, '0')}</p>
                </div>
                <div>
                    <span class="badge ${cfg.color} px-3 py-2">
                        <i data-lucide="${cfg.icon}" class="me-1" style="width: 12px; height: 12px;"></i>
                        ${cfg.label}
                    </span>
                </div>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <small class="text-uppercase custom-text-muted fw-bold d-block mb-2">Customer</small>
                        <div class="h5 fw-bold text-white">${req.customer}</div>
                        <div class="small custom-text-muted">Cust ID: #${String(req.customerId).padStart(4, '0')}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-uppercase custom-text-muted fw-bold d-block mb-2">Vehicle</small>
                        <div class="h5 fw-bold text-white">${req.vehicle}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-uppercase custom-text-muted fw-bold d-block mb-2">Service Requested</small>
                        <div class="h5 fw-bold text-primary">${req.service}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-uppercase custom-text-muted fw-bold d-block mb-2">Preferred Schedule</small>
                        <div class="text-light">${req.date}</div>
                        <div class="small custom-text-muted">${req.time}</div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <small class="text-uppercase custom-text-muted fw-bold d-block mb-2">Assigned Technician</small>
                    <div class="bg-dark p-3 rounded border border-secondary">
                        ${req.assignedTo || 'Not yet assigned (Pending confirmation)'}
                    </div>
                </div>
                
                <div class="mb-4">
                    <small class="text-uppercase custom-text-muted fw-bold d-block mb-2">Customer Concern / Notes</small>
                    <div class="bg-dark p-3 rounded border border-secondary">
                        ${req.concern}
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top custom-border-dark">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="openDeleteModal('${req.id}')">Delete</button>
                <button type="button" class="btn btn-warning" onclick="openCancelModal('${req.id}')">Reject</button>
                <button type="button" class="btn btn-success" onclick="openConfirmModal('${req.id}')">Confirm & Assign</button>
            </div>
        `;
        
        document.getElementById('viewModalContent').innerHTML = modalContent;
        const modal = new bootstrap.Modal(document.getElementById('viewModal'));
        modal.show();
        
        // Reinitialize icons in modal
        if (window.lucide && lucide.createIcons) {
            lucide.createIcons();
        }
    }
    
    function openConfirmModal(id) {
        document.getElementById('confirm_request_id').value = id;
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
    }
    
    function openCancelModal(id) {
        document.getElementById('cancel_request_id').value = id;
        const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
        modal.show();
    }
    
    function openDeleteModal(id) {
        document.getElementById('delete_request_id').value = id;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal.show');
            modals.forEach(function(modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            });
        }
    });
</script>
@endsection