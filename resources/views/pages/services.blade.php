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

    .tabs-trigger-active {
        background-color: #1e293b !important;
        color: white !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="mb-4">
        <h1 class="h2 fw-bold text-white">Service Requests</h1>
        <p class="custom-text-muted mb-4">Manage and process customer service requests</p>
    </div>

    <div class="d-flex bg-dark border border-secondary rounded-pill p-1 mb-4 w-fit">

        @php $statuses = ['all', 'pending', 'confirmed', 'cancelled']; @endphp

        @foreach($statuses as $f)
        <a href="{{ request()->fullUrlWithQuery(['filter' => $f]) }}"
            class="btn btn-sm px-3 py-1 me-1 rounded-pill text-decoration-none {{ ($filter ?? 'all') == $f ? 'tabs-trigger-active text-white' : 'custom-text-muted' }}">

            {{ ucfirst($f) }}
            ({{ $counts[$f] ?? 0 }})
        </a>
        @endforeach
    </div>

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
                        @forelse($customers as $customer)

                        @php
                        $badgeClass = match($customer->status) {
                        'pending' => 'bg-warning text-dark',
                        'confirmed' => 'bg-success text-white',
                        'cancelled' => 'bg-danger text-white',
                        'completed' => 'bg-info text-dark',
                        default => 'bg-secondary text-white'
                        };
                        $iconName = match($customer->status) {
                        'pending' => 'clock',
                        'confirmed' => 'check-circle',
                        'cancelled' => 'x-circle',
                        default => 'help-circle'
                        };
                        @endphp

                        <tr class="border-bottom custom-border-dark">
                            <td class="ps-4 py-3">
                                <span class="badge bg-dark border border-secondary text-white font-monospace">
                                    #{{ str_pad($customer->customer_id , 4, '0', STR_PAD_LEFT) }}
                                </span>
                            <td class="py-3">
                                <div class="fw-medium text-white">{{ $customer->name }}</div>
                                <div class="small custom-text-muted">
                                    Note: {{ Str::limit($customer->description, 30) }}
                                </div>
                            </td>
                            <td class="py-3 custom-text-muted">{{ $customer->brand }} {{ $customer->model }}</td>
                            <td class="py-3">
                                <span class="badge bg-dark text-light border border-secondary">{{ $customer->service_type }}</span>
                            </td>
                            <td class="py-3">
                                <div class="text-light">{{ $customer->preferred_date }}</div>
                                <div class="small custom-text-muted">{{ $customer->preferred_time }}</div>
                            </td>
                            <td class="py-3">
                                @if($customer->employee_name)
                                <div class="d-flex align-items-center">
                                    <i data-lucide="user" class="text-primary me-2" style="width: 12px; height: 12px;"></i>
                                    <span class="text-light">{{ $customer->employee_name }}</span>
                                </div>
                                @else
                                <span class="small fst-italic custom-text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="badge {{ $badgeClass }} px-3 py-2">
                                    <i data-lucide="{{ $iconName }}" class="me-1" style="width: 12px; height: 12px;"></i>
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-outline-light border-0"
                                        data-bs-toggle="modal" data-bs-target="#viewModal-{{ $customer->customer_id }}">
                                        <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                    </button>

                                    @if($customer->status === 'pending')
                                    <button class="btn btn-sm btn-outline-success border-0"
                                        data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $customer->customer_id }}">
                                        <i data-lucide="check-circle-2" style="width: 16px; height: 16px;"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger border-0"
                                        data-bs-toggle="modal" data-bs-target="#cancelModal-{{ $customer->customer_id }}">
                                        <i data-lucide="x-circle" style="width: 16px; height: 16px;"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="viewModal-{{ $customer->customer_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content custom-bg-dark border custom-border-dark">
                                    <div class="modal-header border-bottom custom-border-dark">
                                        <h5 class="modal-title text-white">Request #{{ $customer->customer_id }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <small class="text-uppercase custom-text-muted fw-bold d-block mb-2">Customer</small>
                                                <div class="h5 fw-bold text-white">{{ $customer->name }}</div>
                                                <div class="small custom-text-muted">{{ $customer->email ?? 'No Email' }}</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <small class="text-uppercase custom-text-muted fw-bold d-block mb-2">Vehicle</small>
                                                <div class="h5 fw-bold text-white">{{ $customer->brand }} {{ $customer->model }}</div>
                                                <div class="small custom-text-muted">{{ $customer->plate_number }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <small class="text-uppercase custom-text-muted fw-bold d-block mb-2">Customer Concern</small>
                                            <div class="bg-dark p-3 rounded border border-secondary text-light">
                                                {{ $customer->description }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top custom-border-dark">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="confirmModal-{{ $customer->customer_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content custom-bg-dark border custom-border-dark">
                                    <div class="modal-header border-bottom custom-border-dark">
                                        <h5 class="modal-title text-white">Approve Request</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('services.update', $customer->request_id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="confirmed">

                                        <div class="modal-body">
                                            <p class="custom-text-muted mb-3">Assign an employee to confirm this booking.</p>
                                            <div class="mb-3">
                                                <label class="form-label text-light">Assign Technician</label>
                                                <select name="employee_id" required class="form-select bg-dark text-white border-secondary">
                                                    <option value="">Select an employee...</option>
                                                    
                                                    @foreach($employees as $emp)
                                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }}</option>
                                                    @endforeach
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

                        <div class="modal fade" id="cancelModal-{{ $customer->customer_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content custom-bg-dark border custom-border-dark">
                                    <div class="modal-header border-bottom custom-border-dark">
                                        <h5 class="modal-title text-white">Reject Request</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('services.delete', $customer->request_id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="cancelled">
                                        <div class="modal-body">
                                            <p class="custom-text-muted">Are you sure you want to cancel this request?</p>
                                        </div>
                                        <div class="modal-footer border-top custom-border-dark">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-danger">Yes, Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 custom-text-muted">
                                <i data-lucide="inbox" class="mb-3" style="width: 48px; height: 48px;"></i>
                                <div>No requests found.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endsection