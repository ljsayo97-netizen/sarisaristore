<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - SariStore</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 260px; height: 100vh; background: #1e293b; color: white; position: fixed; transition: 0.3s; z-index: 1000; }
        .main-content { margin-left: 260px; padding: 30px; }
        .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 8px; margin: 5px 15px; display: flex; align-items: center; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: #334155; color: white; }
        .nav-link i { width: 25px; font-size: 1.1rem; }
        .top-nav { background: white; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 10px; }
        .logout-btn { background: #fee2e2; color: #ef4444; border: none; padding: 8px 15px; border-radius: 8px; font-weight: 600; }
        
        /* Inventory specific styles */
        .card-inventory { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
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
        <h4 class="fw-bold m-0"><i class="fas fa-store text-info me-2"></i>SariStore</h4>
        <small class="text-muted">Admin Dashboard</small>
    </div>
    <hr class="mx-3 opacity-25">
    <nav class="mt-3">
        <a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="<?= base_url('inventory') ?>" class="nav-link active"><i class="fas fa-boxes"></i> Inventory</a>
        <a href="#" class="nav-link"><i class="fas fa-shopping-cart"></i> Sales Tracking</a>
        <a href="<?= base_url('customers') ?>" class="nav-link"><i class="fas fa-users"></i> Customers</a>
        <a href="<?= base_url('users') ?>" class="nav-link"><i class="fas fa-user-shield"></i> User Management</a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="top-nav">
        <h5 class="m-0 fw-semibold text-secondary">Inventory Management</h5>
        <div class="d-flex align-items-center">
            <span class="me-3 text-muted small">Welcome, <?= session()->get('name') ?></span>
            <a href="<?= base_url('logout') ?>" class="logout-btn text-decoration-none">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
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

        <!-- Add Product Form -->
        <div class="card card-inventory mb-4 p-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-plus-circle text-success me-2"></i>Add New Product</h5>
            <form action="<?= base_url('inventory/store') ?>" method="POST" class="row g-3 align-items-end">
                <?= csrf_field() ?>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Product Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter product name" required value="<?= old('name') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Price (₱)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required min="0.01" value="<?= old('price') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Stock Quantity</label>
                    <input type="number" name="stock" class="form-control" placeholder="0" required min="0" value="<?= old('stock') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-add w-100">
                        <i class="fas fa-save me-2"></i>Save Product
                    </button>
                </div>
            </form>
        </div>

        <!-- Product Table -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fas fa-list text-primary me-2"></i>Product Inventory</h5>
                <span class="badge bg-primary rounded-pill"><?= count($products) ?> Total Items</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="30%">Product Name</th>
                            <th width="15%">Price (₱)</th>
                            <th width="15%">Stock</th>
                            <th width="35%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <form action="<?= base_url('inventory/update/' . $product['product_id']) ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <td class="text-muted small">#<?= $product['product_id'] ?></td>
                                        <td>
                                            <input type="text" name="name" class="form-control form-control-sm" value="<?= esc($product['name']) ?>" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="price" class="form-control form-control-sm" value="<?= esc($product['price']) ?>" required min="0.01">
                                        </td>
                                        <td>
                                            <input type="number" name="stock" class="form-control form-control-sm" value="<?= esc($product['stock']) ?>" required min="0">
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group shadow-sm">
                                                <button type="submit" class="btn btn-sm btn-update px-3">
                                                    <i class="fas fa-sync-alt me-1"></i> Update
                                                </button>
                                                <a href="<?= base_url('inventory/delete/' . $product['product_id']) ?>" 
                                                   class="btn btn-sm btn-delete px-3"
                                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-box-open fs-1 mb-3 d-block"></i>
                                    No products found in inventory.
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
