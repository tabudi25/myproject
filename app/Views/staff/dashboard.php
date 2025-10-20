<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Fluffy Planet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="/js/realtime.js"></script>
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
            --accent-color: #004e89;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
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

        .notification-icon {
            position: relative;
            font-size: 1.3rem;
            color: var(--accent-color);
            cursor: pointer;
            margin-right: 20px;
            transition: color 0.3s ease;
        }

        .notification-icon:hover {
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            padding: 2px 6px;
            font-size: 0.7rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .notification-dropdown {
            position: absolute;
            top: 70px;
            right: 20px;
            width: 400px;
            max-height: 500px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            display: none;
            z-index: 1000;
            overflow: hidden;
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

        .notification-header {
            padding: 15px 20px;
            background: var(--primary-color);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: background 0.2s;
            position: relative;
        }

        .notification-item:hover {
            background: #f8f9fa;
        }

        .notification-item.unread {
            background: #e3f2fd;
        }

        .notification-item.unread::before {
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

        .notification-title {
            font-weight: 600;
            color: var(--accent-color);
            margin-bottom: 5px;
        }

        .notification-message {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #adb5bd;
        }

        .notification-empty {
            padding: 40px 20px;
            text-align: center;
            color: #6c757d;
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

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); color: #ff6b35; }
            100% { transform: scale(1); }
        }

        /* Profile Dropdown Styles */
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #333;
            font-weight: 500;
        }

        .profile-trigger:hover {
            background: #e9ecef;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .profile-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            min-width: 220px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .profile-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-header {
            padding: 16px 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .profile-header small {
            color: rgba(255, 255, 255, 0.8);
        }

        .profile-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .profile-item:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }

        .profile-item.logout-item {
            color: #dc3545;
        }

        .profile-item.logout-item:hover {
            background: #f8d7da;
            color: #721c24;
        }

        .profile-divider {
            height: 1px;
            background: #e9ecef;
            margin: 8px 0;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }

        .stat-icon.orange { background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); }
        .stat-icon.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-icon.green { background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); }
        .stat-icon.purple { background: linear-gradient(135deg, #a044ff 0%, #6a3093 100%); }
        .stat-icon.red { background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); }
        .stat-icon.yellow { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-confirmed { background: #d4edda; color: #155724; }
        .badge-completed { background: #d1ecf1; color: #0c5460; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }
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
                <!-- Real-time Clock -->
                <div class="realtime-clock me-4">
                    <div class="clock-time" style="font-size: 1.1rem; font-weight: bold; color: #004e89;"></div>
                    <div class="clock-date" style="font-size: 0.8rem; color: #666;"></div>
                </div>
                
                
                
                <!-- Notification Icon -->
                <div class="notification-icon" id="notificationIcon" onclick="toggleNotifications()">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                </div>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown">
                    <div class="profile-trigger" onclick="toggleProfileDropdown()">
                        <i class="fas fa-user-circle me-2"></i>
                        <span><?= esc(session()->get('name')) ?></span>
                        <i class="fas fa-chevron-down ms-2"></i>
                    </div>
                    <div class="profile-menu" id="profileMenu">
                        <div class="profile-header">
                            <i class="fas fa-user-circle me-2"></i>
                            <span><?= esc(session()->get('name')) ?></span>
                            <small class="d-block text-muted">Staff Member</small>
                        </div>
                        <div class="profile-divider"></div>
                        <a href="/profile" class="profile-item">
                            <i class="fas fa-user me-2"></i>
                            <span>Profile</span>
                        </a>
                        <a href="/settings" class="profile-item">
                            <i class="fas fa-cog me-2"></i>
                            <span>Settings</span>
                        </a>
                        <div class="profile-divider"></div>
                        <a href="/logout" class="profile-item logout-item">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Notification Dropdown -->
    <div class="notification-dropdown" id="notificationDropdown">
        <div class="notification-header">
            <span><i class="fas fa-bell me-2"></i>Notifications</span>
            <button class="btn btn-sm btn-light" onclick="markAllAsRead()">Mark all as read</button>
        </div>
        <div class="notification-body" id="notificationBody">
            <div class="notification-empty">
                <i class="fas fa-bell-slash fa-2x mb-3"></i>
                <p>No notifications yet</p>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <a href="/staff-dashboard" class="sidebar-item active">
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
                <a href="/staff/orders" class="sidebar-item">
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
                <h2 class="mb-4" style="color: white;">
                    <i class="fas fa-chart-line"></i> Staff Dashboard
                </h2>

                <?php if (session()->getFlashdata('msg')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= session()->getFlashdata('msg') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics Grid -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon orange">
                                <i class="fas fa-paw"></i>
                            </div>
                            <div class="stat-value"><?= $stats['available_animals'] ?></div>
                            <div class="stat-label">Available Animals</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-value">
                                <span class="pending-orders-count"><?= $stats['pending_orders'] ?></span>
                            </div>
                            <div class="stat-label">Pending Orders</div>
                            <small class="text-info d-block mt-1">
                                <i class="fas fa-calendar-day me-1"></i>
                                <span class="today-orders-count"><?= $stats['today_orders'] ?></span> Today
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-value">₱<?= number_format($stats['total_payments'] ?? 0, 2) ?></div>
                            <div class="stat-label">Total Payments</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon purple">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-value"><?= $stats['pending_reservations'] ?></div>
                            <div class="stat-label">Pending Reservations</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon yellow">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-value"><?= $stats['pending_animals'] ?></div>
                            <div class="stat-label">Awaiting Admin Approval</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon red">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="stat-value"><?= $stats['total_animals'] ?></div>
                            <div class="stat-label">Total Animals</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="stat-card">
                            <h5 class="mb-3">
                                <i class="fas fa-bolt"></i> Quick Actions
                            </h5>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="/staff/add-animal" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add New Animal
                                </a>
                                <a href="/staff/orders" class="btn btn-success">
                                    <i class="fas fa-check"></i> Confirm Orders
                                </a>
                                <a href="/staff/sales-report" class="btn btn-warning">
                                    <i class="fas fa-file-download"></i> Generate Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Highlight active sidebar item
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-item').forEach(item => {
            if (item.getAttribute('href') === currentPath) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });

        // ==================== NOTIFICATION SYSTEM ====================
        
        // Toggle notification dropdown
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
            
            if (dropdown.classList.contains('show')) {
                loadNotifications();
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const icon = document.getElementById('notificationIcon');
            
            if (!dropdown.contains(event.target) && !icon.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Load notifications
        function loadNotifications() {
            fetch('/staff/api/notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayNotifications(data.notifications);
                        updateNotificationBadge(data.unreadCount);
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        // Display notifications
        function displayNotifications(notifications) {
            const body = document.getElementById('notificationBody');
            
            if (!notifications || notifications.length === 0) {
                body.innerHTML = `
                    <div class="notification-empty">
                        <i class="fas fa-bell-slash fa-2x mb-3"></i>
                        <p>No notifications yet</p>
                    </div>
                `;
                return;
            }

            body.innerHTML = notifications.map(notif => `
                <div class="notification-item ${notif.is_read ? '' : 'unread'}" onclick="markAsRead(${notif.id}, ${notif.order_id})">
                    <div class="notification-title">${escapeHtml(notif.title)}</div>
                    <div class="notification-message">${escapeHtml(notif.message)}</div>
                    <div class="notification-time">
                        <i class="fas fa-clock me-1"></i>${timeAgo(notif.created_at)}
                    </div>
                </div>
            `).join('');
        }

        // Update notification badge
        function updateNotificationBadge(count) {
            const badge = document.getElementById('notificationBadge');
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Mark notification as read
        function markAsRead(notificationId, orderId) {
            fetch(`/staff/api/notifications/${notificationId}/read`, {
                method: 'PUT'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    
                    // Redirect to orders page if applicable
                    if (orderId) {
                        window.location.href = '/staff/orders';
                    }
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        // Mark all notifications as read
        function markAllAsRead() {
            fetch('/staff/api/notifications/mark-all-read', {
                method: 'PUT'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error marking all as read:', error));
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

        // Load notifications on page load
        loadNotifications();

        // Refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);

        // Auto-refresh order updates every 10 seconds
        setInterval(() => {
            refreshOrderUpdates();
        }, 10000);

        // Function to refresh order updates
        function refreshOrderUpdates() {
            fetch('/staff/api/order-updates')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateOrderStats(data);
                        updateRecentOrders(data.recentOrders);
                    }
                })
                .catch(error => {
                    console.error('Error refreshing order updates:', error);
                });
        }

        // Update order statistics
        function updateOrderStats(data) {
            // Update pending orders count with animation
            const pendingElements = document.querySelectorAll('.pending-orders-count');
            pendingElements.forEach(el => {
                const oldValue = parseInt(el.textContent) || 0;
                const newValue = data.pendingCount;
                
                if (newValue > oldValue) {
                    // Add pulsing animation for new orders
                    el.style.animation = 'pulse 1s ease-in-out';
                    setTimeout(() => {
                        el.style.animation = '';
                    }, 1000);
                }
                
                el.textContent = data.pendingCount;
            });

            // Update today's orders count with animation
            const todayElements = document.querySelectorAll('.today-orders-count');
            todayElements.forEach(el => {
                const oldValue = parseInt(el.textContent) || 0;
                const newValue = data.todayCount;
                
                if (newValue > oldValue) {
                    // Add pulsing animation for new orders
                    el.style.animation = 'pulse 1s ease-in-out';
                    setTimeout(() => {
                        el.style.animation = '';
                    }, 1000);
                }
                
                el.textContent = data.todayCount;
            });
        }

        // Update recent orders section
        function updateRecentOrders(orders) {
            const recentOrdersSection = document.querySelector('.recent-orders-section');
            if (recentOrdersSection && orders.length > 0) {
                const ordersHtml = orders.slice(0, 5).map(order => `
                    <tr>
                        <td><strong>#${order.order_number}</strong></td>
                        <td>${order.customer_name}</td>
                        <td>₱${parseFloat(order.total_amount).toLocaleString('en-PH', {minimumFractionDigits: 2})}</td>
                        <td><span class="badge bg-${order.status === 'pending' ? 'warning' : (order.status === 'delivered' ? 'success' : 'primary')}">${order.status}</span></td>
                        <td>${new Date(order.created_at).toLocaleDateString()}</td>
                    </tr>
                `).join('');

                const tbody = recentOrdersSection.querySelector('tbody');
                if (tbody) {
                    tbody.innerHTML = ordersHtml;
                }
            }
        }

        // Initial load
        refreshOrderUpdates();

        // Profile dropdown functionality
        function toggleProfileDropdown() {
            const profileMenu = document.getElementById('profileMenu');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            // Close notification dropdown if open
            if (notificationDropdown.classList.contains('show')) {
                notificationDropdown.classList.remove('show');
            }
            
            // Toggle profile menu
            profileMenu.classList.toggle('show');
        }

        // Close profile dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.querySelector('.profile-dropdown');
            const profileMenu = document.getElementById('profileMenu');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            if (!profileDropdown.contains(event.target)) {
                profileMenu.classList.remove('show');
            }
            
            if (!document.getElementById('notificationIcon').contains(event.target)) {
                notificationDropdown.classList.remove('show');
            }
        });
    </script>
</body>
</html>

