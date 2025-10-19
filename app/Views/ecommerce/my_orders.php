<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
            --accent-color: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .orders-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .order-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .order-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .order-number {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .order-date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #cce5ff;
            color: #004085;
        }

        .status-processing {
            background: #e2e3e5;
            color: #383d41;
        }

        .status-shipped {
            background: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .order-info {
            flex: 1;
        }

        .order-amount {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .order-items {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .order-delivery {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .order-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 25px;
            padding: 6px 18px;
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .empty-orders {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-orders i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }

        .breadcrumb {
            background: none;
            padding: 20px 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: var(--primary-color);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filter-tabs {
            margin-bottom: 30px;
        }

        .filter-tab {
            background: none;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 10px 20px;
            margin-right: 10px;
            margin-bottom: 10px;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .filter-tab.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .filter-tab:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }


         @media (max-width: 768px) {
             .orders-container {
                 padding: 20px;
                 margin-top: 20px;
             }
             
             .order-header {
                 flex-direction: column;
                 align-items: flex-start;
                 gap: 10px;
             }
             
             .order-details {
                 flex-direction: column;
                 align-items: flex-start;
             }
             
             .order-actions {
                 width: 100%;
                 justify-content: center;
             }

             .tracking-timeline {
                 padding-left: 10px;
             }
             
             .timeline-icon {
                 width: 35px;
                 height: 35px;
                 font-size: 12px;
             }
             
             .timeline-title {
                 font-size: 14px;
             }
             
             .timeline-description {
                 font-size: 12px;
             }
         }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-paw me-2"></i>Fluffy Planet
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/shop">
                            <i class="fas fa-store me-1"></i>Shop
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/cart">
                            <i class="fas fa-shopping-cart me-1"></i>Cart
                            <?php if ($cartCount > 0): ?>
                                <span class="cart-badge"><?= $cartCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/my-orders">
                            <i class="fas fa-box me-1"></i>Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/notifications">
                            <i class="fas fa-bell me-1"></i>Notifications
                            <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?= esc($userName) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if ($userRole === 'admin'): ?>
                                <li><a class="dropdown-item" href="/fluffy-admin">
                                    <i class="fas fa-cog me-2"></i>Admin Panel
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">My Orders</li>
            </ol>
        </nav>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('msg')): ?>
        <div class="container">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('msg') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="orders-container">
            <h2 class="mb-4">
                <i class="fas fa-box me-2 text-primary"></i>
                My Orders
            </h2>
            
            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterOrders('all')">All Orders</button>
                <button class="filter-tab" onclick="filterOrders('pending')">Pending</button>
                <button class="filter-tab" onclick="filterOrders('confirmed')">Confirmed</button>
                <button class="filter-tab" onclick="filterOrders('processing')">Processing</button>
                <button class="filter-tab" onclick="filterOrders('shipped')">Shipped</button>
                <button class="filter-tab" onclick="filterOrders('delivered')">Delivered</button>
            </div>
            
            <?php if (!empty($orders)): ?>
                <div id="orders-list">
                    <?php foreach ($orders as $order): ?>
                        <div class="order-card" data-status="<?= $order['status'] ?>">
                            <div class="order-header">
                                <div>
                                    <div class="order-number">#<?= esc($order['order_number']) ?></div>
                                    <div class="order-date">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= 
                                            !empty($order['created_at']) && $order['created_at'] !== '0000-00-00 00:00:00' 
                                                ? date('M d, Y - g:i A', strtotime($order['created_at'])) 
                                                : date('M d, Y - g:i A') 
                                        ?>
                                    </div>
                                </div>
                                <div>
                                    <span class="status-badge status-<?= $order['status'] ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="order-details">
                                <div class="order-info">
                                    <div class="order-amount">₱<?= number_format($order['total_amount'], 2) ?></div>
                                    <div class="order-delivery">
                                        <i class="fas fa-truck me-1"></i>
                                        <?= $order['delivery_type'] === 'pickup' ? 'Store Pickup' : 'Home Delivery' ?>
                                    </div>
                                    <div class="order-items">
                                        <i class="fas fa-credit-card me-1"></i>
                                        <?php
                                        $paymentMethods = [
                                            'cod' => 'Cash on Delivery',
                                            'gcash' => 'GCash',
                                            'bank_transfer' => 'Bank Transfer'
                                        ];
                                        echo $paymentMethods[$order['payment_method']] ?? ucfirst($order['payment_method']);
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="order-actions">
                                    <a href="/order/<?= $order['id'] ?>" class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                    <?php if ($order['status'] === 'pending'): ?>
                                        <button class="btn btn-outline-danger btn-sm" onclick="cancelOrder(<?= $order['id'] ?>)">
                                            <i class="fas fa-times me-1"></i>Cancel
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Empty Orders -->
                <div class="empty-orders">
                    <i class="fas fa-box-open"></i>
                    <h3>No orders yet</h3>
                    <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                    <a href="/shop" class="btn btn-primary">
                        <i class="fas fa-store me-2"></i>Start Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterOrders(status) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Filter orders
            const orders = document.querySelectorAll('.order-card');
            orders.forEach(order => {
                if (status === 'all' || order.dataset.status === status) {
                    order.style.display = 'block';
                } else {
                    order.style.display = 'none';
                }
            });
        }

        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                // Here you would make an AJAX request to cancel the order
                fetch(`/order/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', 'Order cancelled successfully');
                        // Reload the page to update the order status
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showAlert('danger', data.message || 'Failed to cancel order');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'An error occurred. Please try again.');
                });
            }
        }

        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            const container = document.querySelector('.container');
            container.insertAdjacentHTML('afterbegin', alertHtml);
            
            setTimeout(() => {
                const alert = container.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
        // Real-time notification updates
        function updateNotificationBadge() {
            fetch('/api/notifications/unread-count', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('notificationBadge');
                    if (badge) {
                        if (data.unread_count > 0) {
                            badge.textContent = data.unread_count;
                            badge.style.display = 'inline';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                }
            })
            .catch(error => console.error('Error updating notification badge:', error));
        }

        // Update notification badge every 30 seconds
        setInterval(updateNotificationBadge, 30000);
        
        // Initial load
        updateNotificationBadge();
    </script>
    <script src="/js/realtime.js"></script>
</body>
</html>
