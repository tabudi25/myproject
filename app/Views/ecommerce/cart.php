<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        /* Notification Dropdown Styles */
        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 400px;
            max-height: 500px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            display: none;
            z-index: 1000;
            overflow: hidden;
            margin-top: 10px;
        }

        .notification-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-dropdown-header {
            padding: 15px 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-dropdown-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-dropdown-item {
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: background 0.2s;
            position: relative;
        }

        .notification-dropdown-item:hover {
            background: #f8f9fa;
        }

        .notification-dropdown-item.unread {
            background: #e3f2fd;
        }

        .notification-dropdown-item.unread::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background: var(--primary-color);
            border-radius: 50%;
        }

        .notification-dropdown-title {
            font-weight: 600;
            color: var(--accent-color);
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .notification-dropdown-message {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .notification-dropdown-time {
            font-size: 0.75rem;
            color: #adb5bd;
        }

        .notification-dropdown-empty {
            padding: 40px 20px;
            text-align: center;
            color: #6c757d;
        }

        .notification-dropdown-footer {
            padding: 10px 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        .notification-icon-wrapper {
            position: relative;
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
                    <li class="nav-item notification-icon-wrapper">
                        <a class="nav-link position-relative" href="#" onclick="toggleNotificationDropdown(event)" title="Notifications">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                        </a>
                        <!-- Notification Dropdown -->
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-dropdown-header">
                                <span><i class="fas fa-bell me-2"></i>Notifications</span>
                                <button class="btn btn-sm btn-light" onclick="markAllNotificationsAsRead()">Mark all as read</button>
                            </div>
                            <div class="notification-dropdown-body" id="notificationDropdownBody">
                                <div class="notification-dropdown-empty">
                                    <i class="fas fa-bell-slash fa-2x mb-3"></i>
                                    <p>No notifications yet</p>
                                </div>
                            </div>
                            <div class="notification-dropdown-footer">
                                <a href="/notifications" class="text-primary text-decoration-none">
                                    <small>View All Notifications</small>
                                </a>
                            </div>
                        </div>
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
                            Your Shopping Cart (<?= count($cartItems) ?> <?= count($cartItems) == 1 ? 'pet' : 'pets' ?>)
                        </h2>
                        
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-2">
                                        <img src="/uploads/<?= $item['image'] ?>" alt="<?= esc($item['name']) ?>" class="item-image" onerror="this.src='/web/default-pet.jpg'">
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="col-md-6">
                                        <div class="item-name"><?= esc($item['name']) ?></div>
                                        <div class="item-details">
                                            <i class="fas fa-tag me-1"></i><?= esc($item['category_name']) ?><br>
                                            <span class="badge bg-<?= $item['status'] === 'available' ? 'success' : 'danger' ?> mt-1">
                                                <?= ucfirst($item['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Price and Actions -->
                                    <div class="col-md-4 text-end">
                                        <div class="item-price">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                                        <a href="/remove-from-cart/<?= $item['id'] ?>" class="remove-btn" onclick="return removeFromCart(event, <?= $item['id'] ?>)">
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
                            <span>Subtotal (<?= count($cartItems) ?> <?= count($cartItems) == 1 ? 'pet' : 'pets' ?>)</span>
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
        function removeFromCart(event, cartId) {
            event.preventDefault();
            Swal.fire({
                title: 'Remove Item?',
                text: 'Remove this item from cart?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/remove-from-cart/' + cartId;
                }
            });
            return false;
        }

        // Check for unavailable items
        document.addEventListener('DOMContentLoaded', function() {
            const unavailableItems = document.querySelectorAll('.badge.bg-danger');
            if (unavailableItems.length > 0) {
                showAlert('warning', 'Some pets in your cart are no longer available. Please review your cart.');
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

        // Toggle notification dropdown
        function toggleNotificationDropdown(event) {
            event.preventDefault();
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
            
            if (dropdown.classList.contains('show')) {
                loadNotifications();
            }
        }

        // Close notification dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const iconWrapper = document.querySelector('.notification-icon-wrapper');
            
            if (dropdown && iconWrapper && !iconWrapper.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Load notifications
        function loadNotifications() {
            fetch('/api/notifications/recent?limit=10')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayNotifications(data.notifications || []);
                        updateNotificationBadge();
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        // Display notifications in dropdown
        function displayNotifications(notifications) {
            const body = document.getElementById('notificationDropdownBody');
            
            if (!notifications || notifications.length === 0) {
                body.innerHTML = `
                    <div class="notification-dropdown-empty">
                        <i class="fas fa-bell-slash fa-2x mb-3"></i>
                        <p>No notifications yet</p>
                    </div>
                `;
                return;
            }

            body.innerHTML = notifications.map(notif => `
                <div class="notification-dropdown-item ${notif.is_read ? '' : 'unread'}" onclick="markNotificationAsRead(${notif.id})">
                    <div class="notification-dropdown-title">${escapeHtml(notif.title)}</div>
                    <div class="notification-dropdown-message">${escapeHtml(notif.message)}</div>
                    <div class="notification-dropdown-time">
                        <i class="fas fa-clock me-1"></i>${timeAgo(notif.created_at)}
                    </div>
                </div>
            `).join('');
        }

        // Mark notification as read
        function markNotificationAsRead(notificationId) {
            fetch(`/notifications/mark-read/${notificationId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Mark all notifications as read
        function markAllNotificationsAsRead() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Helper: Time ago function
        function timeAgo(dateString) {
            const date = new Date(dateString);
            const seconds = Math.floor((new Date() - date) / 1000);
            
            const intervals = {
                year: 31536000,
                month: 2592000,
                week: 604800,
                day: 86400,
                hour: 3600,
                minute: 60
            };
            
            for (let [name, seconds_in] of Object.entries(intervals)) {
                const interval = Math.floor(seconds / seconds_in);
                if (interval >= 1) {
                    return interval === 1 ? `1 ${name} ago` : `${interval} ${name}s ago`;
                }
            }
            
            return 'Just now';
        }

        // Helper: Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
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
