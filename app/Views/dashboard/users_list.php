<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Sari-Sari Store</title>
    <!-- Bootstrap 5, Icons & Fonts -->
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
        
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .top-nav { background: white; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 15px; }
        
        /* Premium Table Styling */
        .table thead th { background: #f8fafc; color: #64748b; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 15px; border: none; }
        .table tbody td { padding: 18px 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #334155; font-weight: 500; }
        .table tbody tr:hover { background-color: #fdfdfd; }
        
        .btn-action { width: 35px; height: 35px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; transition: 0.2s; border: none; }
        .btn-edit { background: #eff6ff; color: #3b82f6; }
        .btn-edit:hover { background: #3b82f6; color: white; }
        .btn-delete { background: #fef2f2; color: #ef4444; }
        .btn-delete:hover { background: #ef4444; color: white; }
        
        .badge-admin { background: #ccfbf1; color: #0d9488; }
        .badge-staff { background: #f3e8ff; color: #9333ea; }
        .modal-content { border: none; border-radius: 20px; overflow: hidden; }
        .modal-header { border-bottom: 1px solid #f1f5f9; background: #fcfcfc; }
    </style>
</head>
<body>

<!-- Sidebar (Integrated from Dashboard) -->
<div class="sidebar">
    <div class="p-4 text-center">
        <h4 class="fw-bold m-0 text-info"><i class="fas fa-store me-2"></i>SariStore</h4>
        <small class="text-muted">Admin System</small>
    </div>
    <hr class="mx-3 opacity-25">
    <nav class="mt-3">
        <a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="<?= base_url('inventory') ?>" class="nav-link"><i class="fas fa-boxes"></i> Inventory</a>
        <a href="<?= base_url('sales') ?>" class="nav-link"><i class="fas fa-shopping-cart"></i> Sales Tracking</a>
        <a href="<?= base_url('customers') ?>" class="nav-link"><i class="fas fa-users"></i> Customers</a>
        <a href="<?= base_url('utang') ?>" class="nav-link"><i class="fas fa-hand-holding-usd"></i> Utang Tracking</a>
        <a href="<?= base_url('users') ?>" class="nav-link active"><i class="fas fa-user-shield"></i> User Management</a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="top-nav">
        <h5 class="m-0 fw-bold"><i class="fas fa-users-cog text-primary me-2"></i> Staff & Admin Accounts</h5>
        <button class="btn btn-primary px-4 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-plus-circle me-2"></i> Add New User
        </button>
    </div>

    <!-- Alert Success -->
    <?php if(session()->getFlashdata('msg')): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif; ?>

    <div class="card p-2">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>User Role</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3 text-secondary">
                                    <i class="fas fa-user"></i>
                                </div>
                                <?= esc($user['name']) ?>
                            </div>
                        </td>
                        <td class="text-muted"><?= esc($user['email']) ?></td>
                        <td>
                            <span class="badge rounded-pill px-3 py-2 <?= $user['role'] == 'admin' ? 'badge-admin' : 'badge-staff' ?>">
                                <?= strtoupper(esc($user['role'])) ?>
                            </span>
                        </td>
                        <td class="text-muted small"><?= date('M d, Y', strtotime($user['created_at'] ?? 'now')) ?></td>
                        <td class="text-center">
                            <!-- Edit Button -->
                            <button class="btn-action btn-edit me-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $user['id'] ?>" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            <!-- Delete Button -->
                            <a href="<?= base_url('users/delete/'.$user['id']) ?>" class="btn-action btn-delete" onclick="return confirm('Permanently remove this user?')" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- --- EDIT USER MODAL --- -->
                    <div class="modal fade" id="editModal<?= $user['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="<?= base_url('users/update/'.$user['id']) ?>" method="POST" class="modal-content">
                                <div class="modal-header py-3">
                                    <h6 class="modal-title fw-bold"><i class="fas fa-user-edit me-2"></i> Update User Information</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Full Name</label>
                                        <input type="text" name="name" class="form-control form-control-lg border-light bg-light" value="<?= esc($user['name']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Email Address</label>
                                        <input type="email" name="email" class="form-control form-control-lg border-light bg-light" value="<?= esc($user['email']) ?>" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-bold text-muted">Role</label>
                                            <select name="role" class="form-select form-control-lg border-light bg-light">
                                                <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
                                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-bold text-muted">New Password</label>
                                            <input type="password" name="password" class="form-control form-control-lg border-light bg-light" placeholder="Leave blank to keep">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-4 pt-0">
                                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- --- ADD USER MODAL --- -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('users/store') ?>" method="POST" class="modal-content">
            <div class="modal-header py-3">
                <h6 class="modal-title fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i> Register New Staff</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Full Name</label>
                    <input type="text" name="name" class="form-control form-control-lg border-light bg-light" placeholder="e.g. Maria Clara" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg border-light bg-light" placeholder="maria@email.com" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Role</label>
                        <select name="role" class="form-select form-control-lg border-light bg-light">
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg border-light bg-light" placeholder="Min. 6 chars" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Register User</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>