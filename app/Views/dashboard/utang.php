<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utang Management - SariStore</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #334155; }
        .sidebar { width: 260px; height: 100vh; background: #1e293b; color: white; position: fixed; transition: 0.3s; z-index: 1000; }
        .main-content { margin-left: 260px; padding: 30px; }
        .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 8px; margin: 5px 15px; display: flex; align-items: center; text-decoration: none; transition: 0.2s; }
        .nav-link:hover, .nav-link.active { background: #334155; color: white; }
        .nav-link i { width: 25px; font-size: 1.1rem; }
        
        .top-nav { background: white; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 12px; }
        .logout-btn { background: #fee2e2; color: #ef4444; border: none; padding: 8px 18px; border-radius: 10px; font-weight: 600; text-decoration: none; }
        
        .dashboard-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: white; margin-bottom: 30px; padding: 24px; }
        .table-container { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
        
        .badge-unpaid { background: #fee2e2; color: #ef4444; }
        .badge-paid { background: #dcfce7; color: #10b981; }
        
        .btn-status { padding: 5px 12px; font-size: 0.8rem; font-weight: 600; border-radius: 8px; border: none; }
        .btn-paid { background: #10b981; color: white; }
        .btn-unpaid { background: #ef4444; color: white; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="p-4 text-center">
        <h4 class="fw-bold m-0"><i class="fas fa-store text-info me-2"></i>SariStore</h4>
        <small class="text-muted">Admin Dashboard</small>
    </div>
    <hr class="mx-3 opacity-25">
    <nav class="mt-3">
        <a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="<?= base_url('inventory') ?>" class="nav-link"><i class="fas fa-boxes"></i> Inventory</a>
        <a href="#" class="nav-link"><i class="fas fa-shopping-cart"></i> Sales Tracking</a>
        <a href="<?= base_url('customers') ?>" class="nav-link"><i class="fas fa-users"></i> Customers</a>
        <a href="<?= base_url('utang') ?>" class="nav-link active"><i class="fas fa-hand-holding-usd"></i> Utang Tracking</a>
        <a href="<?= base_url('users') ?>" class="nav-link"><i class="fas fa-user-shield"></i> User Management</a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="top-nav">
        <h5 class="m-0 fw-semibold text-secondary">Utang (Debit) Tracking</h5>
        <div class="d-flex align-items-center">
            <span class="me-3 text-muted small">Welcome, <?= session()->get('name') ?></span>
            <a href="<?= base_url('logout') ?>" class="logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Messages -->
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('message') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Add Utang Form -->
        <div class="dashboard-card">
            <h5 class="fw-bold mb-4"><i class="fas fa-plus-circle text-success me-2"></i>Record New Utang</h5>
            <form action="<?= base_url('utang/store') ?>" method="POST" class="row g-3">
                <?= csrf_field() ?>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Customer</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">Select Customer</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['customer_id'] ?>"><?= esc($customer['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Product</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">Select Product</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['product_id'] ?>"><?= esc($product['name']) ?> (₱<?= $product['price'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Amount (₱)</label>
                    <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                        <i class="fas fa-save me-2"></i>Save Record
                    </button>
                </div>
            </form>
        </div>

        <!-- Utang Table -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fas fa-history text-primary me-2"></i>Utang Records</h5>
                <span class="badge bg-primary rounded-pill"><?= count($utangList) ?> Total Records</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($utangList)): ?>
                            <?php foreach ($utangList as $utang): ?>
                                <tr>
                                    <td class="small text-muted"><?= date('M j, Y h:i A', strtotime($utang['date'])) ?></td>
                                    <td class="fw-bold"><?= esc($utang['customer_name']) ?></td>
                                    <td><?= esc($utang['product_name']) ?></td>
                                    <td class="fw-bold text-primary">₱<?= number_format($utang['amount'], 2) ?></td>
                                    <td>
                                        <span class="badge px-3 py-2 <?= $utang['status'] == 'paid' ? 'badge-paid' : 'badge-unpaid' ?>">
                                            <?= strtoupper($utang['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm">
                                            <?php if ($utang['status'] == 'unpaid'): ?>
                                                <a href="<?= base_url('utang/update-status/' . $utang['utang_id'] . '/paid') ?>" class="btn btn-sm btn-status btn-paid">
                                                    Mark Paid
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('utang/update-status/' . $utang['utang_id'] . '/unpaid') ?>" class="btn btn-sm btn-status btn-unpaid">
                                                    Mark Unpaid
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('utang/delete/' . $utang['utang_id']) ?>" 
                                               class="btn btn-sm btn-outline-danger ms-2"
                                               onclick="return confirm('Delete this record?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-hand-holding-usd fs-1 mb-3 d-block opacity-25"></i>
                                    No utang records found.
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
