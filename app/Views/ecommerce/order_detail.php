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

        .order-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .order-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .order-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .order-date {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .status-badge {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
            margin-top: 15px;
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

        .info-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .section-title {
            color: var(--accent-color);
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--accent-color);
        }

        .info-value {
            color: #6c757d;
            text-align: right;
        }

        .order-items {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .item-row {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .item-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .item-price {
            text-align: right;
            font-weight: bold;
            color: var(--primary-color);
        }

        .order-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-top: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-row:last-child {
            border-bottom: 2px solid var(--primary-color);
            margin-bottom: 0;
            padding-bottom: 0;
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .order-tracking {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-top: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .tracking-step {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        .tracking-step:last-child {
            margin-bottom: 0;
        }

        .tracking-step::after {
            content: '';
            position: absolute;
            left: 20px;
            top: 50px;
            width: 2px;
            height: 40px;
            background: #dee2e6;
        }

        .tracking-step:last-child::after {
            display: none;
        }

        .tracking-step.completed::after {
            background: var(--primary-color);
        }

        .tracking-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            z-index: 1;
            position: relative;
        }

        .tracking-step.completed .tracking-icon {
            background: var(--primary-color);
            color: white;
        }

        .tracking-step.current .tracking-icon {
            background: var(--secondary-color);
            color: white;
        }

        .tracking-step.pending .tracking-icon {
            background: #dee2e6;
            color: #6c757d;
        }

        .tracking-content {
            flex: 1;
        }

        .tracking-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .tracking-time {
            color: #6c757d;
            font-size: 0.9rem;
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

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
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
            padding: 8px 23px;
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .order-container {
                padding: 20px;
                margin-top: 20px;
            }
            
            .order-header {
                padding: 20px;
            }
            
            .order-number {
                font-size: 1.5rem;
            }
            
            .item-row {
                flex-direction: column;
                text-align: center;
            }
            
            .item-image {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .info-value {
                text-align: left;
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
                        <a class="nav-link" href="/my-orders">
                            <i class="fas fa-box me-1"></i>Orders
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
                <li class="breadcrumb-item"><a href="/my-orders">My Orders</a></li>
                <li class="breadcrumb-item active">Order #<?= esc($order['order_number']) ?></li>
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
        <div class="order-container">
            <!-- Order Header -->
            <div class="order-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="order-number">Order #<?= esc($order['order_number']) ?></div>
                        <div class="order-date">
                            <i class="fas fa-calendar me-2"></i>
                            Placed on <?= 
                                !empty($order['created_at']) && $order['created_at'] !== '0000-00-00 00:00:00' 
                                    ? date('M d, Y at g:i A', strtotime($order['created_at'])) 
                                    : date('M d, Y at g:i A') 
                            ?>
                        </div>
                        <span class="status-badge status-<?= $order['status'] ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="h4 mb-0">₱<?= number_format($order['total_amount'], 2) ?></div>
                        <small>Total Amount</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Order Items -->
                    <div class="info-section">
                        <h4 class="section-title">
                            <i class="fas fa-shopping-bag"></i>
                            Order Items
                        </h4>
                        
                        <div class="order-items">
                            <?php foreach ($orderItems as $item): ?>
                                <div class="item-row">
                                    <img src="/uploads/<?= $item['image'] ?>" alt="<?= esc($item['name']) ?>" class="item-image" onerror="this.src='/web/default-pet.jpg'">
                                    <div class="item-details">
                                        <div class="item-name"><?= esc($item['name']) ?></div>
                                        <div class="item-meta">
                                            <i class="fas fa-tag me-1"></i><?= esc($item['category_name']) ?>
                                            <span class="ms-3">
                                                <i class="fas fa-cubes me-1"></i>Qty: <?= $item['quantity'] ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="item-price">
                                        <div>₱<?= number_format($item['price'], 2) ?></div>
                                        <small class="text-muted">per item</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary">
                        <h5 class="mb-3">
                            <i class="fas fa-receipt me-2 text-primary"></i>
                            Order Summary
                        </h5>
                        
                        <?php 
                        $subtotal = $order['total_amount'] - $order['delivery_fee'];
                        ?>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₱<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Delivery Fee</span>
                            <span><?= $order['delivery_fee'] > 0 ? '₱' . number_format($order['delivery_fee'], 2) : 'FREE' ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Total</span>
                            <span>₱<?= number_format($order['total_amount'], 2) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Order Information -->
                    <div class="info-section">
                        <h5 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Order Information
                        </h5>
                        
                        <div class="info-row">
                            <span class="info-label">Payment Method</span>
                            <span class="info-value">
                                <?php
                                $paymentMethods = [
                                    'cod' => 'Cash on Delivery',
                                    'gcash' => 'GCash',
                                    'bank_transfer' => 'Bank Transfer'
                                ];
                                echo $paymentMethods[$order['payment_method']] ?? ucfirst($order['payment_method']);
                                ?>
                            </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Payment Status</span>
                            <span class="info-value">
                                <span class="badge bg-<?= $order['payment_status'] === 'paid' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($order['payment_status']) ?>
                                </span>
                            </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Delivery Method</span>
                            <span class="info-value">
                                <?= $order['delivery_type'] === 'pickup' ? 'Store Pickup' : 'Home Delivery' ?>
                            </span>
                        </div>
                        
                        <?php if ($order['delivery_type'] === 'delivery' && !empty($order['delivery_address'])): ?>
                            <div class="info-row">
                                <span class="info-label">Delivery Address</span>
                                <span class="info-value"><?= nl2br(esc($order['delivery_address'])) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($order['notes'])): ?>
                            <div class="info-row">
                                <span class="info-label">Notes</span>
                                <span class="info-value"><?= nl2br(esc($order['notes'])) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Order Tracking -->
                    <div class="order-tracking">
                        <h5 class="section-title">
                            <i class="fas fa-truck"></i>
                            Order Tracking
                        </h5>
                        
                        <?php
                        $trackingSteps = [
                            'pending' => ['Order Placed', 'Your order has been received'],
                            'confirmed' => ['Order Confirmed', 'We have confirmed your order'],
                            'processing' => ['Preparing Pet', 'Your pet is being prepared'],
                            'shipped' => ['Ready for Pickup/Delivery', 'Your order is ready'],
                            'delivered' => ['Completed', 'Order has been completed']
                        ];
                        
                        $statusOrder = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                        $currentStatusIndex = array_search($order['status'], $statusOrder);
                        ?>
                        
                        <?php foreach ($statusOrder as $index => $status): ?>
                            <?php if (isset($trackingSteps[$status])): ?>
                                <div class="tracking-step <?= $index < $currentStatusIndex ? 'completed' : ($index === $currentStatusIndex ? 'current' : 'pending') ?>">
                                    <div class="tracking-icon">
                                        <?php if ($index < $currentStatusIndex): ?>
                                            <i class="fas fa-check"></i>
                                        <?php elseif ($index === $currentStatusIndex): ?>
                                            <i class="fas fa-clock"></i>
                                        <?php else: ?>
                                            <i class="fas fa-circle"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="tracking-content">
                                        <div class="tracking-title"><?= $trackingSteps[$status][0] ?></div>
                                        <div class="tracking-time"><?= $trackingSteps[$status][1] ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <a href="/my-orders" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to Orders
                        </a>
                        <?php if ($order['status'] === 'pending'): ?>
                            <button class="btn btn-outline-danger w-100" onclick="cancelOrder(<?= $order['id'] ?>)">
                                <i class="fas fa-times me-2"></i>Cancel Order
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
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
    </script>
</body>
</html>
