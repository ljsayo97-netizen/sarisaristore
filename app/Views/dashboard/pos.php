<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - SariStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; color: #334155; }
        .sidebar { width: 260px; height: 100vh; background: #1e293b; color: white; position: fixed; z-index: 1000; }
        .main-content { margin-left: 260px; padding: 25px; min-height: 100vh; }
        .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 8px; margin: 5px 15px; display: flex; align-items: center; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: #334155; color: white; }
        .nav-link i { width: 25px; font-size: 1.1rem; }
        
        .pos-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: white; height: calc(100vh - 120px); display: flex; flex-direction: column; }
        .product-grid { overflow-y: auto; flex-grow: 1; padding: 15px; }
        .cart-section { border-left: 1px solid #f1f5f9; height: 100%; display: flex; flex-direction: column; }
        .cart-items { overflow-y: auto; flex-grow: 1; padding: 15px; }
        
        .product-item { cursor: pointer; transition: 0.2s; border: 1px solid #f1f5f9; border-radius: 12px; padding: 15px; margin-bottom: 15px; }
        .product-item:hover { border-color: #3b82f6; background: #eff6ff; transform: translateY(-2px); }
        .product-name { font-weight: 700; font-size: 0.95rem; margin-bottom: 4px; }
        .product-price { color: #3b82f6; font-weight: 600; }
        
        .cart-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .cart-total { padding: 20px; background: #f8fafc; border-radius: 0 0 16px 0; }
        
        .btn-checkout { background: #10b981; color: white; border: none; padding: 15px; border-radius: 12px; font-weight: 700; width: 100%; transition: 0.2s; }
        .btn-checkout:hover { background: #059669; }
        
        .search-bar { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 20px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="p-4 text-center">
        <h4 class="fw-bold m-0"><i class="fas fa-store text-info me-2"></i>SariStore</h4>
        <small class="text-muted">Admin Dashboard</small>
    </div>
    <hr class="mx-3 opacity-25">
    <nav class="mt-3">
        <a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="<?= base_url('inventory') ?>" class="nav-link"><i class="fas fa-boxes"></i> Inventory</a>
        <a href="<?= base_url('sales') ?>" class="nav-link active"><i class="fas fa-shopping-cart"></i> Sales Tracking</a>
        <a href="<?= base_url('customers') ?>" class="nav-link"><i class="fas fa-users"></i> Customers</a>
        <a href="<?= base_url('utang') ?>" class="nav-link"><i class="fas fa-hand-holding-usd"></i> Utang Tracking</a>
        <a href="<?= base_url('users') ?>" class="nav-link"><i class="fas fa-user-shield"></i> User Management</a>
    </nav>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0 text-slate-800">Point of Sale (POS)</h4>
        <div class="text-muted small"><?= date('l, M j, Y') ?></div>
    </div>

    <div class="row g-4">
        <!-- Product Selection -->
        <div class="col-lg-8">
            <div class="pos-card">
                <div class="p-3 border-bottom">
                    <div class="input-group search-bar mb-0">
                        <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="productSearch" class="form-control border-0 shadow-none" placeholder="Search products...">
                    </div>
                </div>
                <div class="product-grid" id="productGrid">
                    <div class="row g-3">
                        <?php foreach($products as $product): ?>
                        <div class="col-md-4 product-container" data-name="<?= strtolower(esc($product['name'])) ?>">
                            <div class="product-item" onclick="addToCart(<?= $product['product_id'] ?>, '<?= esc($product['name']) ?>', <?= $product['price'] ?>, <?= $product['stock'] ?>)">
                                <div class="product-name text-truncate"><?= esc($product['name']) ?></div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="product-price">₱<?= number_format($product['price'], 2) ?></span>
                                    <span class="badge bg-light text-muted fw-normal">Stock: <?= $product['stock'] ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="col-lg-4">
            <div class="pos-card">
                <div class="p-3 border-bottom bg-light rounded-top-4">
                    <h6 class="fw-bold m-0"><i class="fas fa-shopping-basket text-primary me-2"></i>Current Order</h6>
                </div>
                <div class="cart-items" id="cartItems">
                    <!-- Cart items will appear here -->
                </div>
                <div class="text-center text-muted mt-5 py-5" id="emptyCartMsg">
                    <i class="fas fa-cart-plus fs-1 mb-3 opacity-25"></i>
                    <p>Cart is empty</p>
                </div>
                <div class="cart-total">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Customer (Required for Utang)</label>
                        <select id="customerId" class="form-select border-2">
                            <option value="">Walk-in Customer</option>
                            <?php foreach($customers as $c): ?>
                                <option value="<?= $c['customer_id'] ?>"><?= esc($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold" id="subtotal">₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="fw-bold">Total</h5>
                        <h5 class="fw-bold text-primary" id="totalAmount">₱0.00</h5>
                    </div>
                    <div id="cashInputArea">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Cash Amount (₱)</label>
                            <input type="number" id="cashAmount" class="form-control form-control-lg fw-bold text-success border-2" placeholder="0.00">
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Change</span>
                            <span class="fw-bold text-danger" id="changeAmount">₱0.00</span>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <button class="btn btn-outline-danger w-100 py-3 fw-bold rounded-3" onclick="processCheckout(true)">
                                <i class="fas fa-hand-holding-usd me-1"></i> UTANG
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-success w-100 py-3 fw-bold rounded-3" onclick="processCheckout(false)">
                                <i class="fas fa-check-circle me-1"></i> CASH
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let cart = [];

    function addToCart(id, name, price, stock) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            if (existing.qty >= stock) {
                Swal.fire('Out of Stock', 'No more units available', 'warning');
                return;
            }
            existing.qty++;
        } else {
            cart.push({ id, name, price, qty: 1, stock });
        }
        renderCart();
    }

    function updateQty(id, delta) {
        const item = cart.find(item => item.id === id);
        if (item) {
            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            } else if (item.qty > item.stock) {
                item.qty = item.stock;
                Swal.fire('Stock Limit', 'Cannot add more than available stock', 'info');
            }
        }
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        const emptyMsg = document.getElementById('emptyCartMsg');
        
        if (cart.length === 0) {
            container.innerHTML = '';
            emptyMsg.classList.remove('d-none');
            updateTotals(0);
            return;
        }

        emptyMsg.classList.add('d-none');
        container.innerHTML = cart.map(item => `
            <div class="cart-item">
                <div>
                    <div class="fw-bold small text-truncate" style="max-width: 150px;">${item.name}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">₱${item.price.toFixed(2)} x ${item.qty}</div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="input-group input-group-sm me-2" style="width: 100px;">
                        <button class="btn btn-outline-secondary" onclick="updateQty(${item.id}, -1)">-</button>
                        <input type="text" class="form-control text-center bg-white" value="${item.qty}" readonly>
                        <button class="btn btn-outline-secondary" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                    <div class="fw-bold text-end" style="min-width: 70px;">₱${(item.price * item.qty).toFixed(2)}</div>
                </div>
            </div>
        `).join('');

        const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        updateTotals(total);
    }

    function updateTotals(total) {
        document.getElementById('subtotal').innerText = '₱' + total.toFixed(2);
        document.getElementById('totalAmount').innerText = '₱' + total.toFixed(2);
        calculateChange();
    }

    function calculateChange() {
        const total = parseFloat(document.getElementById('totalAmount').innerText.replace('₱', ''));
        const cash = parseFloat(document.getElementById('cashAmount').value) || 0;
        const change = Math.max(0, cash - total);
        document.getElementById('changeAmount').innerText = '₱' + change.toFixed(2);
    }

    document.getElementById('cashAmount').addEventListener('input', calculateChange);

    function processCheckout(isUtang) {
        if (cart.length === 0) return Swal.fire('Error', 'Cart is empty', 'error');
        
        const total = parseFloat(document.getElementById('totalAmount').innerText.replace('₱', ''));
        const cash = parseFloat(document.getElementById('cashAmount').value) || 0;
        const customerId = document.getElementById('customerId').value;

        if (isUtang && !customerId) {
            return Swal.fire('Error', 'Please select a customer for Utang', 'error');
        }

        if (!isUtang && cash < total) {
            return Swal.fire('Error', 'Insufficient cash amount', 'error');
        }

        const title = isUtang ? 'Mark as Utang?' : 'Complete Cash Sale?';
        const text = isUtang ? `This will be recorded as debt for the selected customer.` : `Total: ₱${total.toFixed(2)} | Change: ₱${(cash-total).toFixed(2)}`;

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                cart.forEach((item, index) => {
                    formData.append(`cart[${index}][id]`, item.id);
                    formData.append(`cart[${index}][qty]`, item.qty);
                });
                formData.append('cash', cash);
                formData.append('total_amount', total);
                formData.append('is_utang', isUtang);
                formData.append('customer_id', customerId);

                fetch('<?= base_url('sales/store') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        let successMsg = data.message;
                        if (!isUtang) successMsg += '\nChange: ₱' + data.change;
                        
                        Swal.fire('Success', successMsg, 'success')
                        .then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }

    // Search Logic
    document.getElementById('productSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.product-container').forEach(el => {
            if (el.dataset.name.includes(term)) {
                el.classList.remove('d-none');
            } else {
                el.classList.add('d-none');
            }
        });
    });
</script>

</body>
</html>
