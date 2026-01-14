@extends('layouts.app')

@section('content')

@php
    // Use real data from controller
    $customers = $customers ?? [];
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
    
    .custom-bg-darkest {
        background-color: #0d121f !important;
    }
    
    .custom-border-dark {
        border-color: #475569 !important;
    }
    
    .custom-text-muted {
        color: #94a3b8 !important;
    }
    
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
    
    .modal-active {
        overflow: hidden !important;
    }
    
    .nav-link-custom {
        color: #94a3b8 !important;
        padding: 12px 16px !important;
        border-radius: 8px !important;
        margin-bottom: 4px !important;
        text-decoration: none !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        transition: all 0.3s ease !important;
    }
    
    .nav-link-custom:hover {
        background-color: rgba(30, 41, 59, 0.6) !important;
        color: white !important;
    }
    
    .nav-link-custom.active {
        background-color: #008ecc !important;
        color: white !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 12px rgba(0, 142, 204, 0.3) !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-white">Customer Information</h1>
            <p class="custom-text-muted mb-0">Manage customer records and vehicle information</p>
        </div>
        <button type="button" class="btn btn-primary btn-lg shadow" 
                data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="fas fa-plus me-2"></i> Add Customer
        </button>
    </div>
    
    <div class="card custom-bg-dark border custom-border-dark shadow-lg mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <h5 class="card-title mb-0 fw-semibold text-white">All Customers</h5>
                <div class="position-relative w-100 w-md-auto" style="max-width: 320px;">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="searchInput" placeholder="Search name, plate, ID..." 
                           class="form-control bg-dark text-white border-secondary ps-5">
                </div>
            </div>
            
            <div class="table-responsive custom-scrollbar">
                <table class="table table-dark table-hover mb-0">
                    <thead class="custom-bg-darker">
                        <tr>
                            <th class="border-bottom custom-border-dark py-3">Customer ID</th>
                            <th class="border-bottom custom-border-dark py-3">Name</th>
                            <th class="border-bottom custom-border-dark py-3">Contact</th>
                            <th class="border-bottom custom-border-dark py-3">Vehicle</th>
                            <th class="border-bottom custom-border-dark py-3">Plate Number</th>
                            <th class="border-bottom custom-border-dark py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customerTableBody">
                        <?php if(count($customers) > 0): ?>
                            <?php foreach($customers as $customer): ?>
                                <?php
                                    $vehicle = $customer->vehicles->first();
                                ?>
                                <tr class="customer-row border-bottom custom-border-dark" 
                                    data-search="<?php echo strtolower($customer->name . ' ' . ($vehicle->plate_number ?? '')); ?>">
                                    <td class="py-3">
                                        <span class="badge bg-dark border border-secondary text-muted font-monospace px-3 py-2">
                                            <?php echo str_pad($customer->customer_id, 4, '0', STR_PAD_LEFT); ?>
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-20 border border-primary border-opacity-30 d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 36px; height: 36px;">
                                                <span class="text-primary fw-bold"><?php echo strtoupper(substr($customer->name, 0, 1)); ?></span>
                                            </div>
                                            <div>
                                                <div class="fw-medium text-white"><?php echo htmlspecialchars($customer->name); ?></div>
                                                <div class="small custom-text-muted"><?php echo htmlspecialchars($customer->email); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-light"><?php echo htmlspecialchars($customer->phone_number); ?></span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-car me-2 text-muted"></i>
                                            <span class="text-light">
                                                <?php if($vehicle): ?>
                                                    <?php echo htmlspecialchars($vehicle->brand); ?> <?php echo htmlspecialchars($vehicle->model); ?>
                                                    <div class="small custom-text-muted"><?php echo ucfirst(str_replace('_', ' ', $vehicle->vehicle_type)); ?></div>
                                                <?php else: ?>
                                                    No vehicle
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-dark border border-secondary text-light font-monospace">
                                            <?php echo $vehicle->plate_number ?? 'N/A'; ?>
                                        </span>
                                    </td>
                                    <td class="text-end py-3">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-outline-info border-0" 
                                                    onclick="viewCustomer(<?php echo $customer->customer_id; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success border-0" 
                                                    onclick="editCustomer(<?php echo $customer->customer_id; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger border-0" 
                                                    onclick="confirmDelete(<?php echo $customer->customer_id; ?>, '<?php echo addslashes($customer->name); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 custom-text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <div>No customers found</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content custom-bg-dark border custom-border-dark">
            <div class="modal-header border-bottom custom-border-dark">
                <h5 class="modal-title text-white">Add New Customer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo route('customers.store'); ?>" method="POST" class="modal-body p-4">
                <?php echo csrf_field(); ?>
                
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="bg-primary rounded-circle me-2" style="width: 4px; height: 24px;"></span>
                        <h6 class="mb-0 text-primary fw-semibold">Personal Information</h6>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-light">Full Name</label>
                            <input type="text" name="name" required 
                                   class="form-control bg-dark text-white border-secondary" 
                                   placeholder="Enter your full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Contact Number</label>
                            <input type="text" name="phone_number" required 
                                   class="form-control bg-dark text-white border-secondary" 
                                   placeholder="09123456789">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Email (Optional)</label>
                            <input type="email" name="email" 
                                   class="form-control bg-dark text-white border-secondary" 
                                   placeholder="your@email.com">
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="bg-primary rounded-circle me-2" style="width: 4px; height: 24px;"></span>
                        <h6 class="mb-0 text-primary fw-semibold">Vehicle Information</h6>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-light">Vehicle Type</label>
                            <select name="vehicle_type" required class="form-select bg-dark text-white border-secondary">
                                <option value="">Select type</option>
                                <option value="SUV">SUV</option>
                                <option value="Pickup_Truck">Pickup Truck</option>
                                <option value="Van">Van</option>
                                <option value="Motorcycle">Motorcycle</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Brand</label>
                            <input type="text" name="brand" required 
                                   class="form-control bg-dark text-white border-secondary" 
                                   placeholder="e.g., Toyota">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Model</label>
                            <input type="text" name="model" required 
                                   class="form-control bg-dark text-white border-secondary" 
                                   placeholder="e.g., Vios">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Plate Number</label>
                            <input type="text" name="plate_number" required 
                                   class="form-control bg-dark text-white border-secondary" 
                                   placeholder="ABC 1234">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-top custom-border-dark pt-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View/Edit Modal (Dynamic content) -->
<div class="modal fade" id="viewEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content custom-bg-dark border custom-border-dark" id="viewEditModalContent">
            <!-- Content loaded via JavaScript -->
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content custom-bg-dark border custom-border-dark" id="deleteModalContent">
            <!-- Content loaded via JavaScript -->
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    // Convert PHP data to JavaScript safely
    const customersData = <?php echo json_encode($customers); ?>;
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('.customer-row');
        
        rows.forEach(function(row) {
            const searchText = row.getAttribute('data-search') || '';
            row.style.display = searchText.includes(filter) ? '' : 'none';
        });
    });
    
    // View customer details
    function viewCustomer(id) {
        // In real app, you would fetch from API
        // For demo, we'll show a static modal
        const modalContent = `
            <div class="modal-header border-bottom custom-border-dark">
                <h5 class="modal-title text-white">Customer Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-center">View functionality would fetch customer data with ID: ${id}</p>
                <p class="text-center">In a real app, this would show customer details.</p>
            </div>
        `;
        
        document.getElementById('viewEditModalContent').innerHTML = modalContent;
        const modal = new bootstrap.Modal(document.getElementById('viewEditModal'));
        modal.show();
    }
    
    // Edit customer
    function editCustomer(id) {
        // In real app, you would fetch from API and populate form
        const modalContent = `
            <div class="modal-header border-bottom custom-border-dark">
                <h5 class="modal-title text-white">Edit Customer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-center">Edit functionality would load customer data with ID: ${id}</p>
                <p class="text-center">In a real app, this would show an edit form.</p>
            </div>
        `;
        
        document.getElementById('viewEditModalContent').innerHTML = modalContent;
        const modal = new bootstrap.Modal(document.getElementById('viewEditModal'));
        modal.show();
    }
    
    // Confirm delete
    function confirmDelete(id, name) {
        const modalContent = `
            <div class="modal-header border-bottom custom-border-dark">
                <h5 class="modal-title text-white">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                    <h5 class="text-white mb-3">Are you sure?</h5>
                    <p class="custom-text-muted">
                        Are you sure you want to delete <strong class="text-white">${name}</strong>? 
                        This action cannot be undone.
                    </p>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="<?php echo route('customers.destroy', ''); ?>/${id}" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </form>
                </div>
            </div>
        `;
        
        document.getElementById('deleteModalContent').innerHTML = modalContent;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
    
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection