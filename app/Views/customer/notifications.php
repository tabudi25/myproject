<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="/js/realtime.js"></script>
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

        .notification-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 30px;
        }

        .notification-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px;
            text-align: center;
        }

        .notification-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #e3f2fd;
            border-left: 4px solid var(--primary-color);
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 15px;
        }

        .notification-icon.delivery-ready {
            background-color: #4caf50;
            color: white;
        }

        .notification-icon.delivery-confirmed {
            background-color: #2196f3;
            color: white;
        }

        .notification-icon.delivery-rejected {
            background-color: #f44336;
            color: white;
        }

        .notification-icon.order-status {
            background-color: #ff9800;
            color: white;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #666;
        }

        .unread-badge {
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
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
                <!-- Real-time Clock -->
                <div class="realtime-clock me-4">
                    <div class="clock-time" style="font-size: 1rem; font-weight: bold; color: white;"></div>
                    <div class="clock-date" style="font-size: 0.8rem; color: rgba(255,255,255,0.8);"></div>
                </div>
                
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/shop">
                            <i class="fas fa-store me-1"></i>Shop
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/cart">
                            <i class="fas fa-shopping-cart me-1"></i>Cart
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/my-orders">
                            <i class="fas fa-box me-1"></i>Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active position-relative" href="/notifications">
                            <i class="fas fa-bell me-1"></i>Notifications
                            <?php if ($unread_count > 0): ?>
                                <span class="unread-badge"><?= $unread_count ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">
                            <i class="fas fa-user me-1"></i>Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="notification-container">
            <div class="notification-header">
                <h2><i class="fas fa-bell me-2"></i>Notifications</h2>
                <p class="mb-0">Stay updated with your order and delivery status</p>
                <?php if ($unread_count > 0): ?>
                    <button class="btn btn-light btn-sm mt-2" onclick="markAllAsRead()">
                        <i class="fas fa-check-double me-1"></i>Mark All as Read
                    </button>
                <?php endif; ?>
            </div>

            <div class="notification-body">
                <?php if (empty($notifications)): ?>
                    <div class="empty-state">
                        <i class="fas fa-bell-slash"></i>
                        <h5>No notifications yet</h5>
                        <p>You'll receive notifications about your orders and deliveries here.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item <?= $notification['is_read'] ? '' : 'unread' ?>" 
                             onclick="markAsRead(<?= $notification['id'] ?>)">
                            <div class="d-flex align-items-start">
                                <div class="notification-icon <?= $notification['type'] ?>">
                                    <?php
                                    $icons = [
                                        'delivery_ready' => 'fas fa-truck',
                                        'delivery_confirmed' => 'fas fa-check-circle',
                                        'delivery_rejected' => 'fas fa-exclamation-triangle',
                                        'order_status' => 'fas fa-info-circle'
                                    ];
                                    $icon = $icons[$notification['type']] ?? 'fas fa-bell';
                                    ?>
                                    <i class="<?= $icon ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?= esc($notification['title']) ?></h6>
                                    <p class="mb-2"><?= esc($notification['message']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="notification-time">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= 
                                                !empty($notification['created_at']) && $notification['created_at'] !== '0000-00-00 00:00:00' 
                                                    ? date('M d, Y g:i A', strtotime($notification['created_at'])) 
                                                    : date('M d, Y g:i A') 
                                            ?>
                                        </small>
                                        <?php if (!$notification['is_read']): ?>
                                            <span class="unread-badge">New</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mark notification as read
        function markAsRead(notificationId) {
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
                    // Remove unread styling
                    const notification = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                    if (notification) {
                        notification.classList.remove('unread');
                        const badge = notification.querySelector('.unread-badge');
                        if (badge) badge.remove();
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Mark all notifications as read
        function markAllAsRead() {
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
                    // Remove all unread styling
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        const badge = item.querySelector('.unread-badge');
                        if (badge) badge.remove();
                    });
                    
                    // Hide mark all button
                    const markAllBtn = document.querySelector('[onclick="markAllAsRead()"]');
                    if (markAllBtn) markAllBtn.style.display = 'none';
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
