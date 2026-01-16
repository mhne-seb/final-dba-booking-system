@extends('layouts.app')

@section('content')

<style>
    /* Essential Dark Mode Styles */
    body { background-color: #0b1120 !important; color: #f8fafc !important; }
    .custom-bg-dark { background-color: #1e293b !important; }
    .custom-bg-darker { background-color: #0f172a !important; }
    .custom-border-dark { border-color: #334155 !important; }
    .custom-text-muted { color: #94a3b8 !important; }
    
    /* Table & Modal Helpers */
    .text-light-head { color: #e2e8f0 !important; }
    .form-control, .form-select { 
        background-color: #0f172a !important; 
        color: #ffffff !important; 
        border-color: #475569 !important; 
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-white">History & Reports</h1>
            <p class="custom-text-muted mb-0">View completed service records</p>
        </div>
    </div>

    <div class="card custom-bg-dark border custom-border-dark shadow-lg">
        <div class="card-header border-bottom custom-border-dark d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-semibold text-white">Completed Bookings</h5>
            <span class="badge bg-dark border border-secondary text-light">Total: {{ $total ?? 0 }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead class="custom-bg-darker">
                        <tr>
                            <th class="border-bottom custom-border-dark py-3 ps-4 text-light-head">Date</th>
                            <th class="border-bottom custom-border-dark py-3 text-light-head">Customer</th>
                            <th class="border-bottom custom-border-dark py-3 text-light-head">Vehicle</th>
                            <th class="border-bottom custom-border-dark py-3 text-light-head">Service</th>
                            <th class="border-bottom custom-border-dark py-3 text-light-head">Shop</th>
                            <th class="border-bottom custom-border-dark py-3 text-light-head">Duration</th>
                            <th class="border-bottom custom-border-dark py-3 text-light-head">Transaction Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $row)
                            <tr class="border-bottom custom-border-dark align-middle">
                                <td class="ps-4 py-3 custom-text-muted">{{ $row->finished_date ?? '-'}}</td>
                                <td class="py-3">
                                    <div class="fw-medium text-white">{{ $row->full_name ?? 'Customer ID: ' . $row->customer_id }}</div>
                                </td> 
                                <td class="py-3 custom-text-muted">{{ $row->brand_model ?? '-'}}</td>
                                <td class="py-3 text-light">{{ $row->service_type ?? '-'}}</td>
                                <td class="py-3 custom-text-muted">
                                    {{-- Assuming shop name is available in your query result --}}
                                    {{ $row->shop_name ?? '-' }} 
                                </td>
                                <td class="py-3 custom-text-muted">{{ $row->duration ?? '-'}}</td>
                                </td>
                                <td class="py-3 custom-text-muted">{{ $row->transaction_type ?? '-'}}</td>
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 custom-text-muted">
                                    <i data-lucide="inbox" class="mb-3 mx-auto" style="width: 48px; height: 48px;"></i>
                                    <div>No history records found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-bg-dark border custom-border-dark text-white">
            <div class="modal-header border-bottom custom-border-dark">
                <h5 class="modal-title fw-bold">Service Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-6 mb-3">
                        <small class="text-uppercase custom-text-muted fw-bold">Completion Date</small>
                        <div class="h5 text-white" id="modalDate"></div>
                    </div>
                    <div class="col-6 mb-3">
                        <small class="text-uppercase custom-text-muted fw-bold">Duration</small>
                        <div class="h5 text-white" id="modalDuration"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <small class="text-uppercase custom-text-muted fw-bold">Customer</small>
                        <div class="h4 text-primary fw-bold" id="modalCustomer"></div>
                    </div>
                </div>
                
                <div class="p-3 bg-dark bg-opacity-50 rounded border border-secondary mb-3">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <small class="custom-text-muted d-block">Vehicle</small>
                            <span class="fw-bold" id="modalVehicle"></span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="custom-text-muted d-block">Technician</small>
                            <span class="fw-bold" id="modalEmployee"></span>
                        </div>
                    </div>
                </div>

                <div class="p-3 custom-bg-darker rounded border border-secondary">
                    <small class="text-uppercase custom-text-muted fw-bold d-block mb-1">Service Performed</small>
                    <span id="modalService" class="fw-bold fs-5 text-white"></span>
                </div>
            </div>
            <div class="modal-footer border-top custom-border-dark">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>

    // 2. Open Modal Logic using Find()
    function openViewModal(id) {
        // Find the record by ID (String conversion for safety)
        const h = histories.find(x => String(x.history_id) === String(id));

        if (!h) {
            console.error('History record not found:', id);
            return;
        }

        // Populate Fields
        document.getElementById('modalDate').innerText     = h.finished_date;
        document.getElementById('modalDuration').innerText = h.duration;
        document.getElementById('modalCustomer').innerText = h.full_name;
        document.getElementById('modalVehicle').innerText  = h.brand_model;
        document.getElementById('modalEmployee').innerText = h.employee ?? 'N/A'; // Use default if null
        document.getElementById('modalService').innerText  = h.service_type;

        new bootstrap.Modal(document.getElementById('viewHistoryModal')).show();
    }
</script>
@endsection