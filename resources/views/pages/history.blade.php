@extends('layouts.app')

@section('content')


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
    
    .toast-custom {
        position: fixed !important;
        top: 20px !important;
        right: 20px !important;
        z-index: 1060 !important;
        min-width: 300px !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-white">History & Reports</h1>
            <p class="custom-text-muted mb-0">View completed bookings and generate reports</p>
        </div>
        <button id="exportBtn" class="btn btn-primary">
            <i data-lucide="download" class="me-2" style="width: 16px; height: 16px;"></i>
            Export Report
        </button>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <?php foreach($stats as $stat): ?>
            <div class="col-md-4">
                <div class="card custom-bg-dark border custom-border-dark shadow-lg h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="small custom-text-muted fw-bold mb-2"><?php echo $stat['title']; ?></p>
                                <h3 class="fw-bold text-white mb-0"><?php echo $stat['value']; ?></h3>
                                <p class="small custom-text-muted mt-1"><?php echo $stat['period']; ?></p>
                            </div>
                            <div class="<?php echo $stat['color']; ?> p-3 rounded-circle">
                                <i data-lucide="check-circle-2" style="width: 24px; height: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Filters -->
    <div class="card custom-bg-dark border custom-border-dark shadow-lg mb-4">
        <div class="card-body">
            <form id="filterForm" method="GET" action="<?php echo url()->current(); ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small custom-text-muted">Start Date</label>
                    <input type="date" name="startDate" value="<?php echo $startDate; ?>" 
                           class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-3">
                    <label class="form-label small custom-text-muted">End Date</label>
                    <input type="date" name="endDate" value="<?php echo $endDate; ?>" 
                           class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-3">
                    <label class="form-label small custom-text-muted">Service Type</label>
                    <input type="text" name="service" placeholder="All services" value="<?php echo htmlspecialchars($serviceFilter); ?>" 
                           class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark border-secondary w-100">
                        <i data-lucide="calendar" class="me-2" style="width: 16px; height: 16px;"></i>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Completed Bookings Table -->
    <div class="card custom-bg-dark border custom-border-dark shadow-lg">
        <div class="card-header border-bottom custom-border-dark">
            <h5 class="card-title mb-0 fw-semibold text-white">Completed Bookings History</h5>
            <p class="small custom-text-muted mb-0">Showing <?php echo count($filteredData); ?> records</p>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead class="custom-bg-darker">
                        <tr>
                            <th class="border-bottom custom-border-dark py-3 ps-4">Date</th>
                            <th class="border-bottom custom-border-dark py-3">Customer</th>
                            <th class="border-bottom custom-border-dark py-3">Vehicle</th>
                            <th class="border-bottom custom-border-dark py-3">Service</th>
                            <th class="border-bottom custom-border-dark py-3">Technician</th>
                            <th class="border-bottom custom-border-dark py-3">Duration</th>
                            <th class="border-bottom custom-border-dark py-3 pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($filteredData) > 0): ?>
                            <?php foreach($filteredData as $b): ?>
                                <tr class="border-bottom custom-border-dark">
                                    <td class="ps-4 py-3 custom-text-muted"><?php echo htmlspecialchars($b['date']); ?></td>
                                    <td class="py-3">
                                        <div class="fw-medium text-white"><?php echo htmlspecialchars($b['customer']); ?></div>
                                    </td>
                                    <td class="py-3 custom-text-muted"><?php echo htmlspecialchars($b['vehicle']); ?></td>
                                    <td class="py-3 text-light"><?php echo htmlspecialchars($b['service']); ?></td>
                                    <td class="py-3 custom-text-muted"><?php echo htmlspecialchars($b['employee']); ?></td>
                                    <td class="py-3 custom-text-muted"><?php echo htmlspecialchars($b['duration']); ?></td>
                                    <td class="pe-4 py-3">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-2">
                                            <i data-lucide="check-circle-2" class="me-1" style="width: 12px; height: 12px;"></i>
                                            Completed
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 custom-text-muted">
                                    <i data-lucide="inbox" class="mb-3" style="width: 48px; height: 48px;"></i>
                                    <div>No records found for the selected filters.</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Export Success Toast -->
<div class="toast align-items-center text-white bg-success border-0 toast-custom" id="exportToast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body">
            <i data-lucide="check" class="me-2"></i>
            <strong>Export Successful!</strong>
            <div class="small">Report has been downloaded to your device.</div>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Convert PHP data to JavaScript
    const historyData = <?php echo json_encode($filteredData); ?>;
    const startDate = '<?php echo $startDate; ?>';
    const endDate = '<?php echo $endDate; ?>';
    
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
    
    // Export to CSV
    function exportCsv() {
        if (!Array.isArray(historyData) || historyData.length === 0) {
            alert('No records to export for the selected filters.');
            return;
        }
        
        const headers = ["Date", "Customer", "Vehicle", "Service", "Technician", "Duration", "Status"];
        const rows = historyData.map(function(r) {
            return [
                r.date,
                r.customer,
                r.vehicle,
                r.service,
                r.employee,
                r.duration,
                'Completed'
            ];
        });
        
        const csvContent = [headers, ...rows].map(function(r) {
            return r.map(function(c) {
                return '"' + c + '"';
            }).join(",");
        }).join("\n");
        
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'AutoCare_Report_' + startDate + '_to_' + endDate + '.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        // Show toast
        const toast = new bootstrap.Toast(document.getElementById('exportToast'));
        toast.show();
    }
    
    // Attach export button
    document.getElementById('exportBtn').addEventListener('click', exportCsv);
</script>
@endsection