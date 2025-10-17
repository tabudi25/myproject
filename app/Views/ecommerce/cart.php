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

        .cart-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }

        .item-name {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .item-details {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .item-price {
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .quantity-input {
            width: 60px;
            height: 35px;
            border: 1px solid #ddd;
            text-align: center;
            border-radius: 8px;
        }

        .remove-btn {
            color: #dc3545;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: #dc3545;
            color: white;
        }

        .cart-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            position: sticky;
            top: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 15px 30px;
            font-weight: 600;
            width: 100%;
            margin-bottom: 15px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-cart i {
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

        @media (max-width: 768px) {
            .cart-container {
                padding: 20px;
                margin-top: 20px;
            }
            
            .cart-summary {
                margin-top: 30px;
                position: relative;
                top: auto;
            }
            
            .item-image {
                width: 80px;
                height: 80px;
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
                        <a class="nav-link active position-relative" href="/cart">
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
                <li class="breadcrumb-item"><a href="/shop">Shop</a></li>
                <li class="breadcrumb-item active">Shopping Cart</li>
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
        <?php if (!empty($cartItems)): ?>
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-container">
                        <h2 class="mb-4">
                            <i class="fas fa-shopping-cart me-2 text-primary"></i>
                            Your Shopping Cart (<?= count($cartItems) ?> items)
                        </h2>
                        
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-2">
                                        <img src="/uploads/<?= $item['image'] ?>" alt="<?= esc($item['name']) ?>" class="item-image" onerror="this.src='/web/default-pet.jpg'">
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="col-md-4">
                                        <div class="item-name"><?= esc($item['name']) ?></div>
                                        <div class="item-details">
                                            <i class="fas fa-tag me-1"></i><?= esc($item['category_name']) ?><br>
                                            <span class="badge bg-<?= $item['status'] === 'available' ? 'success' : 'danger' ?> mt-1">
                                                <?= ucfirst($item['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Quantity Controls -->
                                    <div class="col-md-3">
                                        <form method="POST" action="/update-cart" class="quantity-controls">
                                            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                            <button type="button" class="quantity-btn" onclick="updateQuantity(<?= $item['id'] ?>, -1)">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="1" class="quantity-input" id="qty-<?= $item['id'] ?>" readonly>
                                            <button type="button" class="quantity-btn" onclick="updateQuantity(<?= $item['id'] ?>, 1)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </form>
                                        <small class="text-muted">Max: 1 per pet</small>
                                    </div>
                                    
                                    <!-- Price and Actions -->
                                    <div class="col-md-3 text-end">
                                        <div class="item-price">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                                        <a href="/remove-from-cart/<?= $item['id'] ?>" class="remove-btn" onclick="return confirm('Remove this item from cart?')">
                                            <i class="fas fa-trash me-1"></i>Remove
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h4 class="mb-4">
                            <i class="fas fa-receipt me-2 text-primary"></i>Order Summary
                        </h4>
                        
                        <div class="summary-item">
                            <span>Subtotal (<?= count($cartItems) ?> items)</span>
                            <span>₱<?= number_format($total, 2) ?></span>
                        </div>
                        
                        <div class="summary-item">
                            <span>Delivery Fee</span>
                            <span class="text-muted">Calculated at checkout</span>
                        </div>
                        
                        <div class="summary-item">
                            <span>Total</span>
                            <span>₱<?= number_format($total, 2) ?></span>
                        </div>
                        
                        <a href="/checkout" class="btn btn-primary">
                            <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                        </a>
                        
                        <a href="/shop" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                        
                        <!-- Security Info -->
                        <div class="mt-4 p-3" style="background: #e8f5e8; border-radius: 10px;">
                            <div class="text-center text-success">
                                <i class="fas fa-shield-alt me-2"></i>
                                <small>Secure checkout guaranteed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty Cart -->
            <div class="cart-container">
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your cart is empty</h3>
                    <p>Looks like you haven't added any pets to your cart yet.</p>
                    <a href="/shop" class="btn btn-primary">
                        <i class="fas fa-store me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateQuantity(cartId, delta) {
            const input = document.getElementById('qty-' + cartId);
            let newValue = parseInt(input.value) + delta;
            
            // For pets, quantity is always 1 (each pet is unique)
            if (newValue < 1) newValue = 1;
            if (newValue > 1) newValue = 1;
            
            if (newValue !== parseInt(input.value)) {
                input.value = newValue;
                
                // Submit the form to update quantity
                const form = input.closest('form');
                const formData = new FormData(form);
                
                fetch('/update-cart', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // Reload the page to show updated cart
                        window.location.reload();
                    } else {
                        alert('Failed to update cart. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }

        // Auto-update cart when quantity changes
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const form = this.closest('form');
                const formData = new FormData(form);
                
                fetch('/update-cart', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            });
        });

        // Check for unavailable items
        document.addEventListener('DOMContentLoaded', function() {
            const unavailableItems = document.querySelectorAll('.badge.bg-danger');
            if (unavailableItems.length > 0) {
                showAlert('warning', 'Some items in your cart are no longer available. Please review your cart.');
            }
        });

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
