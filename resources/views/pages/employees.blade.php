@extends('layouts.app')

@section('content')

{{-- CONFIGURATION --}}
<style>
    /* Essential Dark Mode Styles */
    body {
        background-color: #0b1120 !important;
        color: #f8fafc !important;
    }

    .custom-bg-dark {
        background-color: #1e293b !important;
    }

    .custom-bg-darker {
        background-color: #0f172a !important;
    }

    .custom-border-dark {
        border-color: #334155 !important;
    }

    .custom-text-muted {
        color: #94a3b8 !important;
    }

    /* Inputs & Buttons */
    .form-select,
    .form-control {
        background-color: #0f172a !important;
        color: #ffffff !important;
        border-color: #475569 !important;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
    }

    /* Tabs */
    .nav-tabs-custom .nav-link {
        color: #94a3b8 !important;
        background: transparent !important;
        border: none !important;
        padding: 10px 20px;
        border-radius: 8px;
    }

    .nav-tabs-custom .nav-link.active {
        background-color: #1e293b !important;
        color: white !important;
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

    .work-card {
        background-color: #020617;
        border: 1px solid #1e293b;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status-box {
        border: 1px solid #334155;
        border-radius: 8px;
        padding: 8px 16px;
        text-align: center;
        background: rgba(255, 255, 255, 0.03);
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

    .text-light-head {
        color: #e2e8f0 !important;
    }

    /* Input Styling helper */
    .focus-ring:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
    }

    .bg-darker {
        background-color: #0f172a !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="mb-4">
        <h1 class="h2 fw-bold text-white">Management</h1>
        <p class="custom-text-muted">Manage your workforce and shop locations</p>
    </div>

    <ul class="nav nav-tabs nav-tabs-custom mb-4" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#employees-panel" type="button">Employees</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#shops-panel" type="button">Shops</button>
        </li>
    </ul>

    <div class="tab-content">

        <div class="tab-pane fade show active" id="employees-panel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="position-relative">
                    <input id="employeeSearch" type="text" placeholder="Search employees..." class="form-control ps-5" style="width: 300px;">
                    <i data-lucide="search" class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="width:18px"></i>
                </div>
                <button type="button" class="btn btn-primary fw-bold d-flex align-items-center text-nowrap" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i data-lucide="plus" class="w-4 h-4 me-2"></i>
                    <span>Add Employee</span>
                </button>
            </div>

            <div class="card custom-bg-dark border custom-border-dark shadow-lg">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead class="custom-bg-darker">
                                <tr>
                                    <th class="py-3 ps-4 text-light-head">Name</th>
                                    <th class="py-3 text-light-head">Position</th>
                                    <th class="py-3 text-light-head">Contact</th>
                                    <th class="py-3 text-light-head">Shop</th>
                                    <th class="py-3 text-light-head">Status</th>
                                    <th class="py-3 text-end pe-4 text-light-head">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="employeesTable">
                                @forelse($employees as $emp)
                                @php $empId = $emp->id ?? $emp->employee_id; @endphp

                                <tr class="employee-row border-bottom custom-border-dark"
                                    data-search="{{ strtolower($emp->full_name . ' ' . $emp->role) }}">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-20 d-flex align-items-center justify-content-center me-3 text-white fw-bold" style="width: 36px; height: 36px;">
                                                {{ strtoupper(substr($emp->full_name, 0, 1)) }}
                                            </div>
                                            <div class="fw-medium text-white">{{ $emp->full_name }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 custom-text-muted">{{ $emp->role }}</td>
                                    <td class="py-3 custom-text-muted">{{ $emp->employee_phone_number }}</td>
                                    <td class="py-3 custom-text-muted">{{ $emp->name}}</td>
                                    <td class="py-3">
                                        @if($emp->is_active === 'active')
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill px-3">Active</span>
                                        @else
                                        <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-25 rounded-pill px-3">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-primary fw-bold"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewEmpModal-{{ $empId }}">
                                                View
                                            </button>
                                            <button class="btn btn-sm btn-danger fw-bold"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteEmpModal-{{ $empId }}">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="viewEmpModal-{{ $empId }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content custom-bg-dark border custom-border-dark shadow-lg">
                                            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                                            <div class="modal-body p-4 pt-5">
                                                <div class="d-flex align-items-center mb-5">
                                                    <div class="profile-circle me-3">{{ strtoupper(substr($emp->full_name, 0, 1)) }}</div>
                                                    <div>
                                                        <h4 class="text-white fw-bold mb-0">{{ $emp->full_name }}</h4>
                                                        <div class="text-primary">{{ $emp->role }}</div>
                                                    </div>
                                                </div>

                                                <div class="mb-5">
                                                    <span class="section-label"><i class="fas fa-id-card me-2"></i>Contact Information</span>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <small class="text-secondary d-block mb-1">Phone Number</small>
                                                            <div class="text-white fs-5">{{ $emp->employee_phone_number }}</div>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-secondary d-block mb-1">Employee ID</small>
                                                            <span class="badge bg-dark border border-secondary font-monospace px-3 py-2 fs-6 text-white">
                                                                #{{ str_pad($empId, 4, '0', STR_PAD_LEFT) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <span class="section-label"><i class="fas fa-briefcase me-2"></i>Work Details</span>
                                                    <div class="work-card">
                                                        <div>
                                                            <small class="text-secondary d-block mb-1">Assigned Location</small>
                                                            <h5 class="text-white fw-bold mb-1">{{ $emp->name ?? 'Unassigned' }}</h5>
                                                        </div>
                                                        <div class="status-box">
                                                            <span class="section-label mb-0">STATUS</span>
                                                            <div class="text-white fw-bold">{{ ucfirst($emp->is_active) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top custom-border-dark p-3 bg-dark bg-opacity-25">
                                                <button type="button" class="btn btn-light text-dark px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary px-4 fw-bold"
                                                    data-bs-toggle="modal" data-bs-target="#editEmpModal-{{ $empId }}">
                                                    <i class="far fa-edit me-2"></i>Edit Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="editEmpModal-{{ $empId }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content custom-bg-dark border custom-border-dark shadow-lg">

                                            <div class="modal-header border-bottom custom-border-dark py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                                        <i data-lucide="user-cog" class="text-primary w-6 h-6"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="modal-title text-white fw-bold mb-0">Edit Employee Profile</h5>
                                                        <p class="text-light small mb-0">Update details for {{ $emp->full_name }}</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>

                                            <form action="{{ route('employees.update', $empId) }}" method="POST">
                                                @csrf @method('PUT')

                                                <div class="modal-body p-4">
                                                    <div class="mb-4">
                                                        <h6 class="text-uppercase text-white fw-bold small mb-3 border-bottom custom-border-dark pb-2">
                                                            <i data-lucide="user" class="w-4 h-4 me-1 d-inline"></i> Personal Details
                                                        </h6>
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label text-light small fw-medium">First Name</label>
                                                                <input type="text" name="first_name" value="{{ $emp->first_name }}"
                                                                    class="form-control bg-darker text-white border-secondary focus-ring" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label text-light small fw-medium">Last Name</label>
                                                                <input type="text" name="last_name" value="{{ $emp->last_name }}"
                                                                    class="form-control bg-darker text-white border-secondary focus-ring" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label text-light small fw-medium">Contact Number</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-dark border-secondary text-muted"><i data-lucide="phone" class="w-4 h-4"></i></span>
                                                                    <input type="text" name="phone_number" value="{{ $emp->employee_phone_number }}"
                                                                        class="form-control bg-darker text-white border-secondary focus-ring">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label text-light small fw-medium">Email</label>
                                                                <input type="email" name="email" value="{{ $emp->email }}"
                                                                    class="form-control bg-darker text-white border-secondary focus-ring" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <h6 class="text-uppercase text-white fw-bold small mb-3 border-bottom custom-border-dark pb-2">
                                                            <i data-lucide="briefcase" class="w-4 h-4 me-1 d-inline"></i> Employment Details
                                                        </h6>
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label text-light small fw-medium">Position / Role</label>
                                                                <input type="text" name="role" value="{{ $emp-> role }}"
                                                                    class="form-control bg-darker text-white border-secondary focus-ring" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label text-light small fw-medium">Employment Status</label>
                                                                <select name="is_active" class="form-select bg-darker text-white border-secondary focus-ring">
                                                                    <option value="active" {{ $emp->is_active == 'active' ? 'selected' : '' }}>🟢 Active</option>
                                                                    <option value="inactive" {{ $emp->is_active == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label text-light small fw-medium">Assigned Location</label>
                                                                <select name="shop" class="form-select bg-darker text-white border-secondary focus-ring">
                                                                    <option value="">-- No Shop Assigned --</option>
                                                                    @foreach($shops as $s)
                                                                    <option value="{{ $s->shop_id }}" {{ ($emp->shop ?? '') == $s->name ? 'selected' : '' }}>
                                                                        🏢 {{ $s->name }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer border-top custom-border-dark bg-dark bg-opacity-50 py-3 px-4">
                                                    <button type="button" class="btn btn-outline-light border-0 fw-medium" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary fw-bold px-4">
                                                        <i data-lucide="save" class="w-4 h-4 me-2"></i> Save Changes
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="deleteEmpModal-{{ $empId }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content custom-bg-dark border custom-border-dark">
                                            <div class="modal-body p-4 text-center">
                                                <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                                                <h5 class="text-white mb-2">Confirm Deletion</h5>
                                                <p class="custom-text-muted">Are you sure you want to remove <strong class="text-white">{{ $emp->full_name }}</strong>? This cannot be undone.</p>
                                                <div class="d-flex justify-content-center gap-3 mt-4">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('employees.delete', $empId) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger fw-bold">Yes, Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 custom-text-muted">
                                        <i data-lucide="users" class="mx-auto mb-2" style="width:32px; height:32px"></i>
                                        <div>No employees found</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="shops-panel">
            <div class="d-flex justify-content-end mb-4">
                <button type="button" class="btn btn-primary fw-bold d-flex align-items-center text-nowrap" data-bs-toggle="modal" data-bs-target="#addShopModal">
                    <i data-lucide="plus" class="w-4 h-4 me-2"></i> Add Shop
                </button>
            </div>
            <div class="row">
                @forelse($shops as $shop)
                @php $shopId = $shop->id ?? $shop->shop_id; @endphp
                <div class="col-md-6 mb-4">
                    <div class="card custom-bg-dark border custom-border-dark h-100 shadow-sm hover-shadow transition">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-dark p-3 rounded me-3 border border-secondary border-opacity-25">
                                        <i data-lucide="store" class="text-primary w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-white mb-1">{{ $shop->name }}</h6>
                                        <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary border-opacity-25 rounded-pill px-2">
                                            {{ ucfirst($shop->city) }}
                                        </span>
                                    </div>
                                </div>
                                {{-- Simple delete for shop --}}
                                <form action="{{ route('shops.delete', $shopId) }}" method="POST" onsubmit="return confirm('Delete shop?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm text-danger border-0 p-2 hover-bg-danger-subtle rounded"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                                </form>
                            </div>
                            <div class="custom-text-muted small mt-3 pt-3 border-top custom-border-dark">
                                <div class="mb-2 d-flex align-items-center">
                                    <i data-lucide="map-pin" class="w-4 h-4 me-2 text-primary"></i> {{ $shop->address }}
                                </div>
                                <div class="d-flex align-items-center">
                                    <i data-lucide="phone" class="w-4 h-4 me-2 text-primary"></i> {{ $shop->phone_number }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 custom-text-muted">No shops available</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content custom-bg-dark border custom-border-dark shadow-lg">

            <div class="modal-header border-bottom custom-border-dark py-3 px-4">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                        <i data-lucide="user-plus" class="text-success w-6 h-6"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0">Add New Employee</h5>
                        <p class="text-light small mb-0">Create a new employee profile</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <h6 class="text-uppercase text-white fw-bold small mb-3 border-bottom custom-border-dark pb-2">
                            <i data-lucide="user" class="w-4 h-4 me-1 d-inline"></i> Personal Details
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-light small fw-medium">First Name</label>
                                <input type="text" name="first_name" 
                                    class="form-control bg-darker text-white border-secondary focus-ring" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-light small fw-medium">Last Name</label>
                                <input type="text" name="last_name" 
                                    class="form-control bg-darker text-white border-secondary focus-ring" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-light small fw-medium">Contact Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-muted"><i data-lucide="phone" class="w-4 h-4"></i></span>
                                    <input type="text" name="phone_number" class="form-control bg-darker text-white border-secondary focus-ring">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-light small fw-medium">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-muted"><i data-lucide="phone" class="w-4 h-4"></i></span>
                                    <input type="email" name="email" class="form-control bg-darker text-white border-secondary focus-ring">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-uppercase text-white fw-bold small mb-3 border-bottom custom-border-dark pb-2">
                            <i data-lucide="briefcase" class="w-4 h-4 me-1 d-inline"></i> Employment Details
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-light small fw-medium">Position / Role</label>
                                <input type="text" name="role" class="form-control bg-darker text-white border-secondary focus-ring" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-light small fw-medium">Employment Status</label>
                                <select name="is_active" class="form-select bg-darker text-white border-secondary focus-ring">
                                    <option value="active" selected>🟢 Active</option>
                                    <option value="inactive">🔴 Inactive</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-light small fw-medium">Assigned Location</label>
                                <select name="shop" class="form-select bg-darker text-white border-secondary focus-ring">
                                    <option value="">-- No Shop Assigned --</option>
                                    @foreach($shops as $s)
                                    <option value="{{ $s->shop_id}}">🏢 {{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top custom-border-dark bg-dark bg-opacity-50 py-3 px-4">
                    <button type="button" class="btn btn-outline-light border-0 fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4 d-flex align-items-center">
    <i data-lucide="check" class="w-4 h-4 me-2"></i>
    <span>Save Employee</span>
</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addShopModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-bg-dark border custom-border-dark shadow-lg">

            <div class="modal-header border-bottom custom-border-dark py-3 px-4">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3">
                        <i data-lucide="store" class="text-info w-6 h-6"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0">Add New Shop</h5>
                        <p class="text-light small mb-0">Register a new branch location</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('shops.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-light small fw-medium">Shop Name</label>
                            <input type="text" name="name" class="form-control bg-darker text-white border-secondary focus-ring" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-light small fw-medium">Email</label>
                            <input type="email" name="email" class="form-control bg-darker text-white border-secondary focus-ring" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-light small fw-medium">Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-muted"><i data-lucide="map-pin" class="w-4 h-4"></i></span>
                                <input type="text" name="address" class="form-control bg-darker text-white border-secondary focus-ring">
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <label class="form-label text-light small fw-medium">Contact Number</label>
                            <input type="text" name="phone_number" class="form-control bg-darker text-white border-secondary focus-ring">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-light small fw-medium">City</label>
                            <input type="text" name="city" class="form-control bg-darker text-white border-secondary focus-ring">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top custom-border-dark bg-dark bg-opacity-50 py-3 px-4">
                    <button type="button" class="btn btn-outline-light border-0 fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Save Shop</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });

    // Simple Client-side Search
    document.getElementById('employeeSearch').addEventListener('keyup', function() {
        const value = this.value.toLowerCase();
        document.querySelectorAll('.employee-row').forEach(row => {
            const text = row.getAttribute('data-search');
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });
</script>
@endsection