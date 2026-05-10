<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Activity Logs - SariStore</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #334155; }
        .sidebar { width: 260px; height: 100vh; background: #1e293b; color: white; position: fixed; transition: 0.3s; z-index: 1000; display: flex; flex-direction: column; }
        .main-content { margin-left: 260px; padding: 30px; transition: 0.3s; }
        .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 8px; margin: 5px 15px; display: flex; align-items: center; text-decoration: none; transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background: #334155; color: white; }
        .nav-link i { width: 25px; font-size: 1.1rem; }
        
        .sidebar-bottom { margin-top: auto; padding-bottom: 20px; }
        .logout-nav-link { color: #f87171 !important; }
        .logout-nav-link:hover { background: #450a0a !important; color: white !important; }

        .top-nav { background: white; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 12px; }
        
        .table-container { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
        .badge-login { background: #dcfce7; color: #10b981; }
        .badge-logout { background: #fee2e2; color: #ef4444; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="p-4 text-center">
        <h4 class="fw-bold m-0"><i class="fas fa-store text-info me-2"></i><span>Arlin's Sari-Sari Store</span></h4>
        <small class="text-muted">Admin Dashboard</small>
    </div>
    <hr class="mx-3 opacity-25">
    <nav class="mt-3">
        <a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="fas fa-chart-line"></i> <span>Dashboard</span></a>
        <a href="<?= base_url('inventory') ?>" class="nav-link"><i class="fas fa-boxes"></i> <span>Inventory</span></a>
        <a href="<?= base_url('sales') ?>" class="nav-link"><i class="fas fa-shopping-cart"></i> <span>Sales Tracking</span></a>
        <a href="<?= base_url('customers') ?>" class="nav-link"><i class="fas fa-users"></i> <span>Customers</span></a>
        <a href="<?= base_url('utang') ?>" class="nav-link"><i class="fas fa-hand-holding-usd"></i> <span>Utang Tracking</span></a>
        <a href="<?= base_url('users') ?>" class="nav-link"><i class="fas fa-user-shield"></i> <span>User Management</span></a>
        <a href="<?= base_url('activity-logs') ?>" class="nav-link active"><i class="fas fa-history"></i> <span>Staff Logs</span></a>
    </nav>
    
    <div class="sidebar-bottom">
        <hr class="mx-3 opacity-25">
        <a href="<?= base_url('logout') ?>" class="nav-link logout-nav-link">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="top-nav">
        <h5 class="m-0 fw-semibold text-secondary">Staff Login/Logout Activity</h5>
        <div class="d-flex align-items-center">
            <span class="me-3 text-muted small">Welcome, <?= session()->get('name') ?></span>
        </div>
    </div>

    <div class="container-fluid">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fas fa-list text-primary me-2"></i>Activity History</h5>
                <span class="badge bg-primary rounded-pill"><?= count($logs) ?> Total Entries</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Staff Name</th>
                            <th>Email</th>
                            <th>Action</th>
                            <th>Time</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td class="fw-bold"><?= esc($log['user_name']) ?></td>
                                    <td class="text-muted"><?= esc($log['user_email']) ?></td>
                                    <td>
                                        <span class="badge px-3 py-2 <?= $log['action'] == 'login' ? 'badge-login' : 'badge-logout' ?>">
                                            <i class="fas <?= $log['action'] == 'login' ? 'fa-sign-in-alt' : 'fa-sign-out-alt' ?> me-1"></i>
                                            <?= strtoupper($log['action']) ?>
                                        </span>
                                    </td>
                                    <td class="small"><?= date('M j, Y h:i A', strtotime($log['timestamp'])) ?></td>
                                    <td class="text-muted small"><?= esc($log['ip_address']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-history fs-1 mb-3 d-block opacity-25"></i>
                                    No activity logs found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
