<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management - SariStore</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 260px; height: 100vh; background: #1e293b; color: white; position: fixed; transition: 0.3s; z-index: 1000; display: flex; flex-direction: column; }
        .main-content { margin-left: 260px; padding: 30px; }
        .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 8px; margin: 5px 15px; display: flex; align-items: center; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: #334155; color: white; }
        .nav-link i { width: 25px; font-size: 1.1rem; }
        
        .sidebar-bottom { margin-top: auto; padding-bottom: 20px; }
        .logout-nav-link { color: #f87171 !important; }
        .logout-nav-link:hover { background: #450a0a !important; color: white !important; }

        .top-nav { background: white; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 10px; }
        
        /* Customer specific styles */
        .card-customer { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .table-container { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-add { background: #10b981; color: white; border: none; }
        .btn-add:hover { background: #059669; color: white; }
        .btn-update { background: #3b82f6; color: white; border: none; }
        .btn-update:hover { background: #2563eb; color: white; }
        .btn-delete { background: #ef4444; color: white; border: none; }
        .btn-delete:hover { background: #dc2626; color: white; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="p-4 text-center">
        <h4 class="fw-bold m-0"><i class="fas fa-store text-info me-2"></i>Arlin's Sari-Sari Store</h4>
        <small class="text-muted">Admin Dashboard</small>
    </div>
    <hr class="mx-3 opacity-25">
    <nav class="mt-3">
        <a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="<?= base_url('inventory') ?>" class="nav-link"><i class="fas fa-boxes"></i> Inventory</a>
        <a href="<?= base_url('sales') ?>" class="nav-link"><i class="fas fa-shopping-cart"></i> Sales Tracking</a>
        <a href="<?= base_url('customers') ?>" class="nav-link active"><i class="fas fa-users"></i> Customers</a>
        <a href="<?= base_url('utang') ?>" class="nav-link"><i class="fas fa-hand-holding-usd"></i> Utang Tracking</a>
        <a href="<?= base_url('users') ?>" class="nav-link"><i class="fas fa-user-shield"></i> User Management</a>
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
        <h5 class="m-0 fw-semibold text-secondary">Customer Management</h5>
        <div class="d-flex align-items-center">
            <span class="me-3 text-muted small">Welcome, <?= session()->get('name') ?></span>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Messages -->
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('message') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Add Customer Form -->
        <div class="card card-customer mb-4 p-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-user-plus text-success me-2"></i>Add New Customer</h5>
            <form action="<?= base_url('customers/store') ?>" method="POST" class="row g-3 align-items-end">
                <?= csrf_field() ?>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter customer name" required value="<?= old('name') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Email (Optional)</label>
                    <input type="email" name="email" class="form-control" placeholder="customer@example.com" value="<?= old('email') ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Phone Number</label>
                    <input type="text" name="phone" class="form-control" placeholder="09123456789" required value="<?= old('phone') ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Address</label>
                    <input type="text" name="address" class="form-control" placeholder="Street, City" value="<?= old('address') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-add w-100">
                        <i class="fas fa-save me-2"></i>Save Customer
                    </button>
                </div>
            </form>
        </div>

        <!-- Customer Table -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fas fa-users text-primary me-2"></i>Customer List</h5>
                <span class="badge bg-primary rounded-pill"><?= count($customers) ?> Total Customers</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Name</th>
                            <th width="20%">Email</th>
                            <th width="15%">Phone</th>
                            <th width="20%">Address</th>
                            <th width="20%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customers)): ?>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <form action="<?= base_url('customers/update/' . $customer['customer_id']) ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <td class="text-muted small">#<?= $customer['customer_id'] ?></td>
                                        <td>
                                            <input type="text" name="name" class="form-control form-control-sm" value="<?= esc($customer['name']) ?>" required>
                                        </td>
                                        <td>
                                            <input type="email" name="email" class="form-control form-control-sm" value="<?= esc($customer['email']) ?>">
                                        </td>
                                        <td>
                                            <input type="text" name="phone" class="form-control form-control-sm" value="<?= esc($customer['phone']) ?>" required>
                                        </td>
                                        <td>
                                            <input type="text" name="address" class="form-control form-control-sm" value="<?= esc($customer['address']) ?>">
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group shadow-sm">
                                                <button type="submit" class="btn btn-sm btn-update px-3">
                                                    <i class="fas fa-sync-alt me-1"></i> Update
                                                </button>
                                                <a href="<?= base_url('customers/delete/' . $customer['customer_id']) ?>" 
                                                   class="btn btn-sm btn-delete px-3"
                                                   onclick="return confirm('Are you sure you want to delete this customer?')">
                                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-users-slash fs-1 mb-3 d-block"></i>
                                    No customers found in database.
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
