<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SariStore Admin</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #334155; }
        .sidebar { width: 260px; height: 100vh; background: #1e293b; color: white; position: fixed; transition: 0.3s; z-index: 1000; }
        .main-content { margin-left: 260px; padding: 30px; transition: 0.3s; }
        .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 8px; margin: 5px 15px; display: flex; align-items: center; text-decoration: none; transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background: #334155; color: white; }
        .nav-link i { width: 25px; font-size: 1.1rem; }
        
        .top-nav { background: white; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 12px; }
        .logout-btn { background: #fee2e2; color: #ef4444; border: none; padding: 8px 18px; border-radius: 10px; font-weight: 600; transition: 0.2s; text-decoration: none; }
        .logout-btn:hover { background: #fecaca; color: #dc2626; }

        .stat-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); transition: 0.3s; padding: 24px; background: white; height: 100%; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
        .stat-icon { width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; border-radius: 14px; margin-bottom: 16px; }
        
        .bg-profit { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .bg-sales { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .bg-lowstock { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .bg-products { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

        .dashboard-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: white; margin-bottom: 30px; }
        .card-header-custom { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .card-title-custom { font-weight: 700; font-size: 1.1rem; margin: 0; color: #1e293b; }
        
        .quick-action-btn { background: #f1f5f9; color: #475569; border: none; padding: 10px 16px; border-radius: 10px; font-weight: 600; margin-right: 10px; transition: 0.2s; text-decoration: none; display: inline-flex; align-items: center; }
        .quick-action-btn:hover { background: #e2e8f0; color: #1e293b; }
        .quick-action-btn i { margin-right: 8px; }

        @media (max-width: 992px) {
            .sidebar { width: 80px; }
            .sidebar .fw-bold, .sidebar small, .sidebar span { display: none; }
            .main-content { margin-left: 80px; }
            .nav-link { justify-content: center; margin: 5px; padding: 12px; }
            .nav-link i { margin: 0; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="p-4 text-center">
        <h4 class="fw-bold m-0"><i class="fas fa-store text-info me-2"></i><span>SariStore</span></h4>
        <small class="text-muted">Admin Dashboard</small>
    </div>
    <hr class="mx-3 opacity-25">
    <nav class="mt-3">
        <a href="<?= base_url('dashboard') ?>" class="nav-link active"><i class="fas fa-chart-line"></i> <span>Dashboard</span></a>
        <a href="<?= base_url('inventory') ?>" class="nav-link"><i class="fas fa-boxes"></i> <span>Inventory</span></a>
        <a href="#" class="nav-link"><i class="fas fa-shopping-cart"></i> <span>Sales Tracking</span></a>
        <a href="<?= base_url('customers') ?>" class="nav-link"><i class="fas fa-users"></i> <span>Customers</span></a>
        <a href="<?= base_url('utang') ?>" class="nav-link"><i class="fas fa-hand-holding-usd"></i> <span>Utang Tracking</span></a>
        <a href="<?= base_url('users') ?>" class="nav-link"><i class="fas fa-user-shield"></i> <span>User Management</span></a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="top-nav">
        <h5 class="m-0 fw-semibold text-secondary">Welcome back, <?= session()->get('name') ?>! 👋</h5>
        <div class="d-flex align-items-center">
            <div class="me-4 d-none d-md-block text-end">
                <div class="fw-bold small"><?= date('l, F j, Y') ?></div>
                <div class="text-muted smaller" style="font-size: 0.75rem;">System is running smooth</div>
            </div>
            <a href="<?= base_url('logout') ?>" class="logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-4">
        <a href="<?= base_url('sales') ?>" class="quick-action-btn"><i class="fas fa-plus-circle text-success"></i> New Sale (POS)</a>
        <a href="<?= base_url('inventory') ?>" class="quick-action-btn"><i class="fas fa-box-open text-primary"></i> Add Product</a>
        <a href="<?= base_url('customers') ?>" class="quick-action-btn"><i class="fas fa-user-plus text-info"></i> New Customer</a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon bg-profit">
                    <i class="fas fa-coins fs-4"></i>
                </div>
                <p class="text-muted small fw-semibold mb-1">Today's Profit</p>
                <h3 class="fw-bold mb-0">₱ <?= $todayProfit ?></h3>
                <div class="mt-2 smaller text-success"><i class="fas fa-arrow-up me-1"></i> +5.4% <span class="text-muted ms-1">vs yesterday</span></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon bg-sales">
                    <i class="fas fa-receipt fs-4"></i>
                </div>
                <p class="text-muted small fw-semibold mb-1">Total Sales Today</p>
                <h3 class="fw-bold mb-0"><?= $todaySalesCount ?></h3>
                <div class="mt-2 smaller text-success"><i class="fas fa-arrow-up me-1"></i> +12.3% <span class="text-muted ms-1">vs yesterday</span></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon bg-lowstock">
                    <i class="fas fa-exclamation-triangle fs-4"></i>
                </div>
                <p class="text-muted small fw-semibold mb-1">Low Stock Items</p>
                <h3 class="fw-bold mb-0 <?= $lowStockItems > 0 ? 'text-danger' : '' ?>"><?= $lowStockItems ?></h3>
                <div class="mt-2 smaller text-muted">Items below 10 units</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon bg-products">
                    <i class="fas fa-box-open fs-4"></i>
                </div>
                <p class="text-muted small fw-semibold mb-1">Total Products</p>
                <h3 class="fw-bold mb-0"><?= $totalProducts ?></h3>
                <div class="mt-2 smaller text-muted">Active in inventory</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sales Chart -->
        <div class="col-lg-8">
            <div class="dashboard-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold m-0 small text-uppercase tracking-wider text-muted">Weekly Sales</h6>
                    <span class="badge bg-light text-dark border fw-normal">Last 7 Days</span>
                </div>
                <div style="height: 180px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-lg-4">
            <div class="dashboard-card h-100">
                <div class="card-header-custom py-2 px-3">
                    <h6 class="card-title-custom small text-uppercase tracking-wider text-muted">Recent Activity</h6>
                    <a href="#" class="btn btn-sm btn-link text-decoration-none p-0 small">View All</a>
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="bg-light">
                                <tr class="smaller text-muted">
                                    <th class="ps-3 border-0 py-1">ID</th>
                                    <th class="border-0 py-1">Amount</th>
                                    <th class="pe-3 border-0 py-1 text-end">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($recentTransactions)): ?>
                                    <?php foreach($recentTransactions as $tx): ?>
                                        <tr>
                                            <td class="ps-3 fw-semibold">#<?= $tx['sale_id'] ?></td>
                                            <td class="fw-bold">₱ <?= number_format($tx['total_amount'], 2) ?></td>
                                            <td class="pe-3 text-muted text-end"><?= date('h:i A', strtotime($tx['date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted small">
                                            No transactions yet
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Sales Chart Implementation
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $chartLabels ?>,
            datasets: [{
                label: 'Sales (₱)',
                data: <?= $chartValues ?>,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3b82f6',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 10,
                    bottom: 0
                }
            },
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f8fafc', drawBorder: false },
                    ticks: {
                        callback: function(value) { return '₱' + value; },
                        font: { family: 'Inter', size: 10 },
                        maxTicksLimit: 5
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { font: { family: 'Inter', size: 10 } }
                }
            }
        }
    });
</script>

</body>
</html>
