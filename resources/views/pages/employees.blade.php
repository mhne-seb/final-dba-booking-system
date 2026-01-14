@extends('layouts.app')

@section('content')

@php
    // Use real data from controller
    $employees = $employees ?? [];
    $shops = $shops ?? [];
    
    $shopNames = array_map(function($s) { return $s['name']; }, $shops);
    $counts = [
        'employees' => count($employees),
        'shops' => count($shops),
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
    
    .custom-bg-darkest {
        background-color: #1e293b !important;
    }
    
    .custom-border-dark {
        border-color: #475569 !important;
    }
    
    .custom-text-muted {
        color: #94a3b8 !important;
    }
    
    .badge-active {
        background-color: #064e3b !important;
        color: #bbf7d0 !important;
        padding: 4px 12px !important;
        border-radius: 20px !important;
        font-size: 12px !important;
        display: inline-block !important;
    }
    
    .badge-inactive {
        background-color: #0f172a !important;
        color: #94a3b8 !important;
        padding: 4px 12px !important;
        border-radius: 20px !important;
        font-size: 12px !important;
        display: inline-block !important;
    }
    
    .modal-active {
        overflow: hidden !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-white">Employees & Shop Management</h1>
            <p class="custom-text-muted mb-0">Manage your workforce and shop locations</p>
        </div>
    </div>
    
    <!-- Tabs -->
    <ul class="nav nav-tabs nav-tabs-custom mb-4" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="employees-tab" data-bs-toggle="tab" data-bs-target="#employees-panel" 
                    type="button" role="tab" aria-controls="employees" aria-selected="true">
                Employees
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="shops-tab" data-bs-toggle="tab" data-bs-target="#shops-panel" 
                    type="button" role="tab" aria-controls="shops" aria-selected="false">
                Shops
            </button>
        </li>
    </ul>
    
    <div class="tab-content" id="myTabContent">
        <!-- Employees Panel -->
        <div class="tab-pane fade show active" id="employees-panel" role="tabpanel" aria-labelledby="employees-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-semibold text-white mb-1">Employee List</h5>
                    <p class="small custom-text-muted mb-0">Manage technicians and staff</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                        <input id="employeeSearch" type="text" placeholder="Search employees..." 
                               class="form-control bg-dark text-white border-secondary ps-4" 
                               style="width: 250px;">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                        <i class="fas fa-plus me-2"></i> Add Employee
                    </button>
                </div>
            </div>
            
            <div class="card custom-bg-dark border custom-border-dark shadow-lg">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead class="custom-bg-darker">
                                <tr>
                                    <th class="border-bottom custom-border-dark py-3">Name</th>
                                    <th class="border-bottom custom-border-dark py-3">Position</th>
                                    <th class="border-bottom custom-border-dark py-3">Contact</th>
                                    <th class="border-bottom custom-border-dark py-3">Assigned Shop</th>
                                    <th class="border-bottom custom-border-dark py-3">Status</th>
                                    <th class="border-bottom custom-border-dark py-3 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="employeesTable">
                                <?php if(count($employees) > 0): ?>
                                    <?php foreach($employees as $emp): ?>
                                        <tr class="employee-row border-bottom custom-border-dark" 
                                            data-search="<?php echo strtolower($emp['full_name'] . ' ' . $emp['position'] . ' ' . ($emp['shop'] ?? '')); ?>">
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary bg-opacity-20 border border-primary border-opacity-30 d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 36px; height: 36px;">
                                                        <span class="text-primary fw-bold">
                                                            <?php echo strtoupper(substr($emp['full_name'], 0, 1)); ?>
                                                        </span>
                                                    </div>
                                                    <div class="fw-medium text-white"><?php echo htmlspecialchars($emp['full_name']); ?></div>
                                                </div>
                                            </td>
                                            <td class="py-3 text-light"><?php echo htmlspecialchars($emp['position']); ?></td>
                                            <td class="py-3 text-light"><?php echo htmlspecialchars($emp['contact']); ?></td>
                                            <td class="py-3 text-light"><?php echo htmlspecialchars($emp['shop'] ?: '—'); ?></td>
                                            <td class="py-3">
                                                <?php if($emp['status'] === 'active'): ?>
                                                    <span class="badge-active">Active</span>
                                                <?php else: ?>
                                                    <span class="badge-inactive">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end py-3">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button class="btn btn-sm btn-outline-light border-0" 
                                                            onclick="openEditEmployee('<?php echo $emp['id']; ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger border-0" 
                                                            onclick="openDeleteEmployee('<?php echo $emp['id']; ?>')">
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
                                            <div>No employees found</div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Shops Panel -->
        <div class="tab-pane fade" id="shops-panel" role="tabpanel" aria-labelledby="shops-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-semibold text-white mb-1">Shops</h5>
                    <p class="small custom-text-muted mb-0">Manage shop locations</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addShopModal">
                    <i class="fas fa-plus me-2"></i> Add Shop
                </button>
            </div>
            
            <div class="row">
                <?php if(count($shops) > 0): ?>
                    <?php foreach($shops as $shop): ?>
                        <?php
                            // Count employees assigned to this shop
                            $assigned = array_values(array_filter($employees, function($e) use ($shop) {
                                return $e['shop'] === $shop['name'];
                            }));
                        ?>
                        <div class="col-md-6 mb-4">
                            <div class="card custom-bg-dark border custom-border-dark shadow-lg h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-dark p-3 rounded me-3">
                                                <i data-lucide="home" class="text-light" style="width: 20px; height: 20px;"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-white mb-1"><?php echo htmlspecialchars($shop['name']); ?></h6>
                                                <span class="badge <?php echo $shop['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                                    <?php echo ucfirst($shop['status']); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-light border-0" type="button" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end bg-dark border border-secondary">
                                                <li>
                                                    <button class="dropdown-item text-light" 
                                                            onclick="openEditShop('<?php echo $shop['id']; ?>')">
                                                        <i class="fas fa-edit me-2"></i> Edit
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item text-danger" 
                                                            onclick="openDeleteShop('<?php echo $shop['id']; ?>')">
                                                        <i class="fas fa-trash me-2"></i> Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="bg-dark p-3 rounded border border-secondary mb-2">
                                            <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                            <span class="text-light"><?php echo htmlspecialchars($shop['address']); ?></span>
                                        </div>
                                        <div class="bg-dark p-3 rounded border border-secondary mb-2">
                                            <i class="fas fa-phone me-2 text-muted"></i>
                                            <span class="text-light"><?php echo htmlspecialchars($shop['phone']); ?></span>
                                        </div>
                                        <div class="bg-dark p-3 rounded border border-secondary">
                                            <i class="fas fa-users me-2 text-muted"></i>
                                            <span class="text-light"><?php echo count($assigned); ?> employees assigned</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="card custom-bg-dark border custom-border-dark shadow-lg">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-store fa-3x mb-3 text-muted"></i>
                                <div class="text-muted">No shops configured.</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Employee Modals -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content custom-bg-dark border custom-border-dark">
            <div class="modal-header border-bottom custom-border-dark">
                <h5 class="modal-title text-white">Add New Employee</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo route('employees.store'); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-light">Full Name</label>
                        <input type="text" name="full_name" required 
                               class="form-control bg-dark text-white border-secondary" 
                               placeholder="e.g. Juan dela Cruz">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-light">Position</label>
                            <input type="text" name="position" 
                                   class="form-control bg-dark text-white border-secondary" 
                                   placeholder="e.g. Technician">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Contact No.</label>
                            <input type="text" name="contact" 
                                   class="form-control bg-dark text-white border-secondary" 
                                   placeholder="09XX..">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Assigned Shop</label>
                        <select name="shop" class="form-select bg-dark text-white border-secondary">
                            <option value="">Select Shop</option>
                            <?php foreach($shopNames as $sname): ?>
                                <option value="<?php echo htmlspecialchars($sname); ?>">
                                    <?php echo htmlspecialchars($sname); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Status</label>
                        <select name="status" class="form-select bg-dark text-white border-secondary">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top custom-border-dark">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Shop Modal -->
<div class="modal fade" id="addShopModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content custom-bg-dark border custom-border-dark">
            <div class="modal-header border-bottom custom-border-dark">
                <h5 class="modal-title text-white">Add Shop</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo route('shops.store'); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-light">Shop Name</label>
                        <input type="text" name="name" required 
                               class="form-control bg-dark text-white border-secondary">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Address</label>
                        <input type="text" name="address" 
                               class="form-control bg-dark text-white border-secondary">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Contact Number</label>
                        <input type="text" name="phone" 
                               class="form-control bg-dark text-white border-secondary">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Status</label>
                        <select name="status" class="form-select bg-dark text-white border-secondary">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top custom-border-dark">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Shop</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    // Convert PHP data to JavaScript
    const employees = <?php echo json_encode($employees); ?>;
    const shops = <?php echo json_encode($shops); ?>;
    
    // Search filter for employees
    document.getElementById('employeeSearch').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.employee-row').forEach(function(row) {
            const searchText = row.getAttribute('data-search') || '';
            row.style.display = searchText.includes(query) ? '' : 'none';
        });
    });
    
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
    
    // Employee functions
    function openEditEmployee(id) {
        const emp = employees.find(function(x) { return x.id === id; });
        if (!emp) {
            alert('Employee not found');
            return;
        }
        
        alert('Edit employee: ' + emp.full_name + '\nIn real app, this would open edit modal.');
        // In real app, open modal and populate form with emp data
    }
    
    function openDeleteEmployee(id) {
        if (confirm('Are you sure you want to delete this employee?')) {
            // Submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo route("employees.destroy", ""); ?>/' + id;
            form.innerHTML = `<?php echo csrf_field(); ?><?php echo method_field('DELETE'); ?>`;
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    function openEditShop(id) {
        const shop = shops.find(function(x) { return x.id === id; });
        if (!shop) {
            alert('Shop not found');
            return;
        }
        
        alert('Edit shop: ' + shop.name + '\nIn real app, this would open edit modal.');
    }
    
    function openDeleteShop(id) {
        if (confirm('Delete this shop? Employees assigned will be unassigned.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo route("shops.destroy", ""); ?>/' + id;
            form.innerHTML = `<?php echo csrf_field(); ?><?php echo method_field('DELETE'); ?>`;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<style>
    .nav-tabs-custom .nav-link {
        color: #94a3b8 !important;
        background-color: transparent !important;
        border: none !important;
        padding: 10px 20px !important;
        border-radius: 8px !important;
    }
    
    .nav-tabs-custom .nav-link.active {
        background-color: #1e293b !important;
        color: white !important;
        border-radius: 8px !important;
    }
</style>
@endsection