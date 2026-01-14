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
        border-color: #334155 !important;
    }

    .custom-text-muted {
        color: #94a3b8 !important;
    }

    /* Profile & Card Styles */
    .profile-circle {
        width: 60px;
        height: 60px;
        background-color: #0ea5e9;
        color: white;
        font-size: 24px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .vehicle-card {
        background-color: #020617;
        border: 1px solid #1e293b;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .plate-box {
        border: 1px solid #334155;
        border-radius: 8px;
        padding: 8px 16px;
        text-align: center;
        background: rgba(255, 255, 255, 0.03);
    }

    .plate-label {
        font-size: 10px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 2px;
    }

    .plate-number {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        font-size: 18px;
        color: white;
        letter-spacing: 2px;
    }

    .section-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 12px;
        display: block;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-white">Customer Information</h1>
            <p class="custom-text-muted mb-0">Manage customer records and vehicle information</p>
        </div>

    </div>

    <div class="card custom-bg-dark border custom-border-dark shadow-lg mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0 fw-semibold text-white">All Customers</h5>
                <div class="position-relative w-25">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="searchInput" placeholder="Search..." class="form-control bg-dark text-white border-secondary ps-5">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead class="custom-bg-darker">
                        <tr>
                            <th class="py-3">Customer ID</th>
                            <th class="py-3">Name</th>
                            <th class="py-3">Contact</th>
                            <th class="py-3">Vehicle</th>
                            <th class="py-3">Plate Number</th>
                            <th class="py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customerTableBody">
                        @forelse($customers as $customer)
                        @php
                        // Get the first vehicle safely
                        $vehicle = $customer->vehicles->first();
                        @endphp

                        <tr class="customer-row border-bottom custom-border-dark">
                            <td class="py-3">
                                <span class="badge bg-dark border border-secondary text-white font-monospace px-3 py-2">
                                    #{{ str_pad($customer->customer_id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-20 border border-primary border-opacity-30 d-flex align-items-center justify-content-center me-3"
                                        style="width: 36px; height: 36px;">
                                        <span class="text-white fw-bold">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium text-white">{{ $customer->name }}</div>
                                        <div class="small custom-text-muted">{{ $customer->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-light">{{ $customer->phone_number }}</td>
                            <td class="py-3 text-light">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-car me-2 text-muted"></i>
                                    <span>
                                        {{ $vehicle->brand ?? '' }} {{ $vehicle->model ?? 'No Vehicle' }}
                                        <div class="small custom-text-muted">{{ ucfirst($vehicle->vehicle_type ?? '') }}</div>
                                    </span>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-dark border border-secondary text-light font-monospace">
                                    {{ $vehicle->plate_number ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-end py-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-primary fw-bold"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewModal-{{ $customer->customer_id }}">
                                        View
                                    </button>



                                    <button class="btn btn-sm btn-danger fw-bold"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal-{{ $customer->customer_id }}">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="viewModal-{{ $customer->customer_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content custom-bg-dark border custom-border-dark shadow-lg">
                                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                                    <div class="modal-body p-4 pt-5">
                                        <div class="d-flex align-items-center mb-5">
                                            <div class="profile-circle me-3">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                                            <div>
                                                <h4 class="text-white fw-bold mb-0">{{ $customer->name }}</h4>
                                                <div class="custom-text-muted">{{ $customer->email }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-5">
                                            <span class="section-label"><i class="far fa-user me-2"></i>Personal Details</span>
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-secondary d-block mb-1">Contact Number</small>
                                                    <div class="text-white fs-5">{{ $customer->phone_number }}</div>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-secondary d-block mb-1">Customer ID</small>
                                                    <span class="badge bg-dark border border-secondary font-monospace px-3 py-2 fs-6 text-white">
                                                        #{{ str_pad($customer->customer_id, 4, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="section-label"><i class="fas fa-car me-2"></i>Vehicle Information</span>
                                            <div class="vehicle-card">
                                                <div>
                                                    <small class="text-secondary d-block mb-1">Vehicle Model</small>
                                                    <h5 class="text-white fw-bold mb-1">{{ $vehicle->brand ?? '' }} {{ $vehicle->model ?? 'No Vehicle' }}</h5>
                                                    <div class="text-primary small">{{ $vehicle->vehicle_type ?? '' }}</div>
                                                </div>
                                                <div class="plate-box">
                                                    <span class="plate-label">PLATE NO.</span>
                                                    <div class="plate-number">{{ $vehicle->plate_number ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top custom-border-dark p-3 bg-dark bg-opacity-25">
                                        <button type="button" class="btn btn-light text-dark px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary px-4 fw-bold"
                                            data-bs-toggle="modal" data-bs-target="#editModal-{{ $customer->customer_id }}">
                                            <i class="far fa-edit me-2"></i>Edit Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="editModal-{{ $customer->customer_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content custom-bg-dark border custom-border-dark shadow-lg">

                                    <div class="position-absolute top-0 end-0 m-3 z-3">
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>

                                    <form action="{{ route('customers.update', $customer->customer_id) }}" method="POST" class="modal-body p-4 pt-5">
                                        @csrf
                                        @method('PUT')

                                        <div class="d-flex align-items-start mb-5">
                                            <div class="profile-circle me-3 flex-shrink-0">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="mb-2">
                                                    <input type="text" name="nameplace" value="{{ $customer->name }}"
                                                        class="form-control bg-transparent border-0 text-white fw-bold fs-4 p-0 shadow-none focus-ring-0">
                                                </div>
                                                <div>
                                                    <input type="text" name="emailplace" value="{{ $customer->email }}"
                                                        class="form-control bg-transparent border-0 text-white p-0 shadow-none focus-ring-0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-5">
                                            <span class="section-label"><i class="far fa-user me-2"></i>Personal Details</span>
                                            <div class="row g-3">

                                                <div class="col-12">
                                                    <label class="small text-secondary mb-1">Full Name</label>
                                                    <input type="text" name="name" value="{{ $customer->name }}"
                                                        class="form-control bg-dark text-white border-secondary py-2" required>
                                                </div>

                                                <div class="col-12">
                                                    <label class="small text-secondary mb-1">Email Address</label>
                                                    <input type="email" name="email" value="{{ $customer->email }}"
                                                        class="form-control bg-dark text-white border-secondary py-2">
                                                </div>

                                                <div class="col-7">
                                                    <label class="small text-secondary mb-1">Contact Number</label>
                                                    <input type="text" name="phone_number" value="{{ $customer->phone_number }}"
                                                        class="form-control bg-dark text-white border-secondary py-2" required>
                                                </div>

                                                <div class="col-5">
                                                    <label class="small text-secondary mb-1">Customer ID</label>
                                                    <input type="text" value="#{{ str_pad($customer->customer_id, 4, '0', STR_PAD_LEFT) }}"
                                                        class="form-control bg-dark border-secondary text-white text-center font-monospace py-2"
                                                        readonly style="cursor: not-allowed; opacity: 0.7;">
                                                </div>

                                            </div>
                                        </div>

                                        <div>
                                            <span class="section-label"><i class="fas fa-car me-2"></i>Vehicle Information</span>
                                            <div class="vehicle-card p-3">
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <label class="small text-secondary mb-1">Brand</label>
                                                        <input type="text" name="brand" value="{{ $vehicle->brand ?? '' }}"
                                                            class="form-control bg-dark text-white border-secondary" placeholder="Toyota">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="small text-secondary mb-1">Plate Number</label>
                                                        <input type="text" name="plate_number" value="{{ $vehicle->plate_number ?? '' }}"
                                                            class="form-control bg-dark text-white border-secondary font-monospace" placeholder="ABC 1234">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="small text-secondary mb-1">Model</label>
                                                        <input type="text" name="model" value="{{ $vehicle->model ?? '' }}"
                                                            class="form-control bg-dark text-white border-secondary" placeholder="Vios">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="small text-secondary mb-1">Type</label>
                                                        <select name="vehicle_type" class="form-select bg-dark text-white border-secondary">
                                                            <option value="SUV" {{ ($vehicle->vehicle_type ?? '') == 'SUV' ? 'selected' : '' }}>SUV</option>
                                                            <option value="Sedan" {{ ($vehicle->vehicle_type ?? '') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                                                            <option value="Van" {{ ($vehicle->vehicle_type ?? '') == 'Van' ? 'selected' : '' }}>Van</option>
                                                            <option value="Motorcycle" {{ ($vehicle->vehicle_type ?? '') == 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                                            <option value="Pickup_Truck" {{ ($vehicle->vehicle_type ?? '') == 'Pickup_Truck' ? 'selected' : '' }}>Pickup_Truck</option>
                                                            <option value="Other" {{ ($vehicle->vehicle_type ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-5 d-flex gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-2"></i>Cancel
                                            </button>
                                            <button type="submit" class="btn btn-success px-4 fw-bold">
                                                <i class="fas fa-save me-2"></i>Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteModal-{{ $customer->customer_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content custom-bg-dark border custom-border-dark">
                                    <div class="modal-header border-bottom custom-border-dark">
                                        <h5 class="modal-title text-white">Confirm Deletion</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4 text-center">
                                        <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                                        <p class="custom-text-muted">Are you sure you want to delete <strong class="text-white">{{ $customer->name }}</strong>? This cannot be undone.</p>
                                        <div class="d-flex justify-content-center gap-3">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('customers.delete', $customer->customer_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 custom-text-muted">No customers found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content custom-bg-dark border custom-border-dark">
            <div class="modal-header border-bottom custom-border-dark">
                <h5 class="modal-title text-white">Add New Customer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('customers.store') }}" method="POST" class="modal-body p-4">
                @csrf
                <div class="row g-3">
                    <div class="col-12"><label class="form-label text-light">Full Name</label><input type="text" name="name" class="form-control bg-dark text-white border-secondary"></div>
                    <div class="col-6"><label class="form-label text-light">Contact</label><input type="text" name="phone_number" class="form-control bg-dark text-white border-secondary"></div>
                    <div class="col-6"><label class="form-label text-light">Email</label><input type="email" name="email" class="form-control bg-dark text-white border-secondary"></div>
                    <div class="col-12 mt-4">
                        <h6 class="text-primary fw-semibold">Vehicle</h6>
                    </div>
                    <div class="col-4"><input type="text" name="brand" placeholder="Brand" class="form-control bg-dark text-white border-secondary"></div>
                    <div class="col-4"><input type="text" name="model" placeholder="Model" class="form-control bg-dark text-white border-secondary"></div>
                    <div class="col-4"><input type="text" name="plate_number" placeholder="Plate" class="form-control bg-dark text-white border-secondary"></div>
                </div>
                <div class="mt-4 text-end"><button type="submit" class="btn btn-primary">Save Customer</button></div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Simple Client-Side Search (Optional - remove if you want only server-side)
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('.customer-row').forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection