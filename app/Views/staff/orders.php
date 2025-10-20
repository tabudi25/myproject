<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .sidebar {
            background: white;
            min-height: calc(100vh - 76px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            padding: 20px 0;
        }

        .sidebar-item {
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover, .sidebar-item.active {
            background: linear-gradient(90deg, rgba(255,107,53,0.1) 0%, rgba(255,107,53,0.05) 100%);
            border-left-color: var(--primary-color);
            color: var(--primary-color);
        }

        .sidebar-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            padding: 30px;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .badge-pending { background: #ffc107; color: #000; }
        .badge-confirmed { background: #28a745; }
        .badge-processing { background: #17a2b8; }
        .badge-shipped { background: #6610f2; }
        .badge-delivered { background: #198754; }
        .badge-cancelled { background: #dc3545; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/staff-dashboard">
                <i class="fas fa-paw"></i> Fluffy Planet Staff
            </a>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3">
                    <i class="fas fa-user-circle"></i>
                    <?= esc(session()->get('name')) ?>
                </span>
                <a href="/logout" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <a href="/staff-dashboard" class="sidebar-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/staff/animals" class="sidebar-item">
                    <i class="fas fa-paw"></i>
                    <span>Manage Animals</span>
                </a>
                <a href="/staff/add-animal" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add New Animal</span>
                </a>
                <a href="/staff/delivery-confirmations" class="sidebar-item">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="/staff/orders" class="sidebar-item active">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="/staff/sales-report" class="sidebar-item">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Sales Report</span>
                </a>
                <a href="/staff/payments" class="sidebar-item">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="content-card">
                    <h3 class="mb-4">
                        <i class="fas fa-shopping-cart"></i> Customer Orders
                    </h3>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Delivery Type</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        loadOrders();

        function loadOrders() {
            fetch('/staff/api/orders')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderOrders(data.data);
                    } else {
                        document.getElementById('ordersTableBody').innerHTML = 
                            '<tr><td colspan="9" class="text-center text-danger">Failed to load orders</td></tr>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('ordersTableBody').innerHTML = 
                        '<tr><td colspan="9" class="text-center text-danger">Network error</td></tr>';
                });
        }

        function renderOrders(orders) {
            const tbody = document.getElementById('ordersTableBody');
            
            if (orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center">No orders found</td></tr>';
                return;
            }

            tbody.innerHTML = orders.map(order => `
                <tr>
                    <td><strong>#${order.order_number}</strong></td>
                    <td>
                        ${order.customer_name}<br>
                        <small class="text-muted">${order.customer_email}</small>
                    </td>
                    <td>₱${parseFloat(order.total_amount).toLocaleString()}</td>
                    <td>${order.delivery_type}</td>
                    <td>${order.payment_method}</td>
                    <td><span class="badge badge-${order.payment_status}">${order.payment_status}</span></td>
                    <td><span class="badge badge-${order.status}">${order.status}</span></td>
                    <td>${new Date(order.created_at).toLocaleDateString()}</td>
                    <td>
                        <div class="btn-group" role="group">
                            ${order.status === 'pending' ? `
                                <button class="btn btn-sm btn-success" onclick="confirmOrder(${order.id})">
                                    <i class="fas fa-check"></i> Confirm
                                </button>
                            ` : ''}
                            ${order.status === 'confirmed' ? `
                                <button class="btn btn-sm btn-info" onclick="startPreparation(${order.id})">
                                    <i class="fas fa-paw"></i> Start Prep
                                </button>
                            ` : ''}
                            ${order.status === 'processing' ? `
                                <button class="btn btn-sm btn-warning" onclick="markReadyForDelivery(${order.id})">
                                    <i class="fas fa-box"></i> Ready
                                </button>
                            ` : ''}
                            ${order.status === 'shipped' ? `
                                <button class="btn btn-sm btn-primary" onclick="markDelivered(${order.id})">
                                    <i class="fas fa-truck"></i> Delivered
                                </button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function confirmOrder(id) {
            if (!confirm('Confirm this order?')) return;
            
            const formData = new URLSearchParams();
            formData.append('_method', 'PUT');
            
            fetch('/staff/api/orders/' + id + '/confirm', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    loadOrders();
                    alert('Order confirmed successfully!');
                } else {
                    alert(res.message || 'Failed to confirm order');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Network error. Please try again.');
            });
        }

        function startPreparation(id) {
            if (!confirm('Start preparing this pet for delivery?')) return;
            
            const formData = new URLSearchParams();
            formData.append('status', 'processing');
            
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting...';
            button.disabled = true;
            
            fetch('/staff/api/orders/' + id + '/update-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    loadOrders();
                    showSuccessNotification('Pet preparation started! Customer has been notified and their order tracking page will update automatically.');
                } else {
                    alert(res.message || 'Failed to start preparation');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Network error. Please try again.');
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        function markReadyForDelivery(id) {
            if (!confirm('Mark this order as ready for delivery?')) return;
            
            const formData = new URLSearchParams();
            formData.append('status', 'shipped');
            
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marking...';
            button.disabled = true;
            
            fetch('/staff/api/orders/' + id + '/update-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    loadOrders();
                    showSuccessNotification('Order marked as ready for delivery! Customer has been notified and their order tracking page will update automatically.');
                } else {
                    alert(res.message || 'Failed to mark as ready');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Network error. Please try again.');
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        function markDelivered(id) {
            if (!confirm('Mark this order as delivered?')) return;
            
            const formData = new URLSearchParams();
            formData.append('status', 'delivered');
            
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marking...';
            button.disabled = true;
            
            fetch('/staff/api/orders/' + id + '/update-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    loadOrders();
                    showSuccessNotification('Order marked as delivered! Customer has been notified and their order tracking page will update automatically.');
                } else {
                    alert(res.message || 'Failed to mark as delivered');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Network error. Please try again.');
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        // Show success notification
        function showSuccessNotification(message) {
            const notification = document.createElement('div');
            notification.innerHTML = `
                <div style="display: flex; align-items: center;">
                    <i class="fas fa-check-circle" style="margin-right: 10px; color: #28a745;"></i>
                    <div>
                        <strong>Success!</strong><br>
                        ${message}
                    </div>
                </div>
            `;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #28a745, #20c997);
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                font-size: 14px;
                z-index: 1000;
                box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                max-width: 350px;
                animation: slideInRight 0.5s ease-out;
            `;
            
            // Add animation keyframes
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
            
            document.body.appendChild(notification);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.animation = 'slideInRight 0.5s ease-out reverse';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 500);
                }
            }, 5000);
        }
    </script>
</body>
</html>

