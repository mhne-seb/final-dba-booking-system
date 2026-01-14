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

    .stat-card:hover {
        border-color: #008ecc !important;
    }
</style>

<div class="container-fluid py-4">
    <header class="mb-5">
        <h1 class="h1 fw-bold text-white">Dashboard</h1>
        <p class="custom-text-muted">Welcome back! Here's an overview of today's activities.</p>
    </header>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card custom-bg-dark border custom-border-dark shadow-lg stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase small custom-text-muted fw-bold mb-2">Total Bookings Today</p>
                            <h3 class="fw-bold text-white mb-0"><?php echo $stats['today_bookings']; ?></h3>
                            <p class="small text-primary mt-2">+3 from yesterday</p>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i data-lucide="calendar" class="text-primary" style="width: 24px; height: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card custom-bg-dark border custom-border-dark shadow-lg h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase small custom-text-muted fw-bold mb-2">Pending Requests</p>
                            <h3 class="fw-bold text-white mb-0"><?php echo $stats['pending_requests']; ?></h3>
                            <p class="small text-warning mt-2">Needs attention</p>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i data-lucide="clock" class="text-warning" style="width: 24px; height: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card custom-bg-dark border custom-border-dark shadow-lg h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase small custom-text-muted fw-bold mb-2">Completed Today</p>
                            <h3 class="fw-bold text-white mb-0"><?php echo $stats['completed_today']; ?></h3>
                            <p class="small text-success mt-2">+2 from yesterday</p>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i data-lucide="check-circle" class="text-success" style="width: 24px; height: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card custom-bg-dark border custom-border-dark shadow-lg h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase small custom-text-muted fw-bold mb-2">Total Customers</p>
                            <h3 class="fw-bold text-white mb-0"><?php echo $stats['total_customers']; ?></h3>
                            <p class="small text-purple mt-2">+15 this month</p>
                        </div>
                        <div class="bg-purple bg-opacity-10 p-3 rounded-circle">
                            <i data-lucide="users" class="text-purple" style="width: 24px; height: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card custom-bg-dark border custom-border-dark shadow-lg h-100">
                <div class="card-header border-bottom custom-border-dark">
                    <div class="d-flex align-items-center">
                        <i data-lucide="car" class="text-primary me-2" style="width: 20px; height: 20px;"></i>
                        <h5 class="card-title mb-0 text-uppercase text-white fw-bold">Today's Bookings</h5>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (count($todayBookings) > 0): ?>
                        <?php foreach ($todayBookings as $booking): ?>
                            <div class="card custom-bg-darker border custom-border-dark mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-dark d-flex align-items-center justify-content-center me-3"
                                                style="width: 48px; height: 48px;">
                                                <span class="text-white fw-bold">
                                                    <?php echo strtoupper(substr($booking['customer'], 0, 2)); ?>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-white mb-1"><?php echo $booking['customer']; ?></h6>
                                                <p class="small custom-text-muted mb-0">
                                                    <?php echo $booking['vehicle']; ?> • <?php echo $booking['service']; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-4">
                                            <span class="font-monospace small custom-text-muted"><?php echo $booking['time']; ?></span>

                                            {{-- Logic: Update status badge colors based on new status strings --}}
                                            <?php if ($booking['status'] === 'Confirmed' || $booking['status'] === 'In Queue'): ?>
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-3 py-2">
                                                    <?php echo $booking['status']; ?>
                                                </span>
                                            <?php elseif ($booking['status'] === 'Pending'): ?>
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 px-3 py-2">
                                                    <?php echo $booking['status']; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-dark text-light border border-secondary px-3 py-2">
                                                    <?php echo $booking['status']; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5 custom-text-muted">
                            <i data-lucide="calendar" class="mb-3" style="width: 48px; height: 48px;"></i>
                            <div>No bookings for today</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card custom-bg-dark border custom-border-dark shadow-lg mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <i data-lucide="trending-up" class="text-primary me-2" style="width: 16px; height: 16px;"></i>
                        <h6 class="card-title mb-0 text-uppercase text-white fw-bold">Weekly Overview</h6>
                    </div>

                    {{-- Logic: Use the dynamic $weeklyStats from controller --}}
                    <?php foreach ($weeklyStats as $day): ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small custom-text-muted mb-1">
                                <span><?php echo $day['day']; ?></span>
                                <span><?php echo round($day['percent']); ?>%</span>
                            </div>
                            <div class="progress bg-dark" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar"
                                    style="width: <?php echo $day['percent']; ?>%;"
                                    aria-valuenow="<?php echo $day['percent']; ?>"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card custom-bg-dark border custom-border-dark shadow-lg">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <i data-lucide="zap" class="text-warning me-2" style="width: 16px; height: 16px;"></i>
                        <h6 class="card-title mb-0 text-uppercase text-white fw-bold">Popular Services</h6>
                    </div>

                    {{-- Logic: Use dynamic $popularServices from controller --}}
                    <?php foreach ($popularServices as $service): ?>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-light"><?php echo $service['name']; ?></span>
                                <span class="fw-bold text-white"><?php echo $service['percent']; ?>%</span>
                            </div>
                            <div class="progress bg-dark" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar"
                                    style="width: <?php echo $service['percent']; ?>%;"
                                    aria-valuenow="<?php echo $service['percent']; ?>"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide && lucide.createIcons) {
            lucide.createIcons();
        }
    });
</script>
@endsection