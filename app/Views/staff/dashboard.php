<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Fluffy Planet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="/js/realtime.js"></script>
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #FF8C42;
            --dark-orange: #FF4500;
            --black: #000000;
            --dark-black: #1a1a1a;
            --light-black: #2d2d2d;
            --accent-color: #1a1a1a;
            --sidebar-bg: #000000;
            --sidebar-hover: #FF6B35;
            --cream-bg: #FFF8E7;
            --warm-beige: #F5E6D3;
            --light-gray: #f5f5f5;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--cream-bg);
            min-height: 100vh;
        }

        .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            border-bottom: 2px solid var(--primary-color);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .sidebar {
            background: var(--black);
            min-height: calc(100vh - 76px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            padding: 20px 0;
        }

        .sidebar-item {
            padding: 12px 20px;
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            background: var(--primary-color);
            color: var(--black);
            border-left-color: var(--primary-color);
            font-weight: 600;
        }

        .notification-icon {
            position: relative;
            font-size: 1.3rem;
            color: var(--black);
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
            background: var(--dark-orange);
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
            border: 2px solid white;
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

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-left-color: var(--secondary-color);
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
            margin: 0 auto 15px;
        }

        .stat-icon.orange { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }
        .stat-icon.blue { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }
        .stat-icon.green { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }
        .stat-icon.purple { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }
        .stat-icon.red { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }
        .stat-icon.yellow { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--black);
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

        .dashboard-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-top: 4px solid var(--primary-color);
            margin-bottom: 25px;
        }

        .section-title {
            color: var(--black);
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        /* Custom Button Styles - Orange & Black Theme */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--dark-orange);
            border-color: var(--dark-orange);
            color: white;
        }

        .btn-secondary {
            background-color: var(--light-black);
            border-color: var(--light-black);
            color: white;
        }

        .btn-secondary:hover {
            background-color: var(--black);
            border-color: var(--black);
            color: white;
        }

        .btn-outline-danger {
            color: var(--dark-orange);
            border-color: var(--dark-orange);
        }

        .btn-outline-danger:hover {
            background-color: var(--dark-orange);
            border-color: var(--dark-orange);
            color: white;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        .main-content {
            padding: 30px;
            background-color: var(--cream-bg);
            min-height: calc(100vh - 76px);
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
            z-index: 1000;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            padding: 10px 18px;
            background: var(--primary-color);
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            font-weight: 600;
            border: 2px solid var(--primary-color);
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            min-width: 120px;
            justify-content: center;
        }

        .profile-trigger:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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
            background: var(--primary-color);
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .notification-title {
            font-weight: 600;
            color: var(--black);
            margin-bottom: 5px;
        }

        .notification-message {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .notification-time {
            color: #adb5bd;
            font-size: 0.8rem;
        }

        .notification-empty {
            text-align: center;
            padding: 40px 20px;
            color: #adb5bd;
        }

        .sidebar-item i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .sidebar {
                min-height: auto;
            }

            .stat-card {
                margin-bottom: 15px;
            }

            .dashboard-section {
                padding: 15px;
                margin-bottom: 15px;
            }
        }
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
                    <span>Pets</span>
                </a>
                <a href="/staff/orders" class="sidebar-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Adoptions</span>
                </a>
                <a href="/staff/payments" class="sidebar-item">
                    <i class="fas fa-credit-card"></i>
                    <span>Payment</span>
                </a>
                <a href="/staff/sales-report" class="sidebar-item">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Sales Report</span>
                </a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <h2 class="mb-4" style="color: Black;">
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
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon orange">
                                <i class="fas fa-paw"></i>
                            </div>
                            <div class="stat-value"><?= $stats['available_animals'] ?></div>
                            <div class="stat-label">Available Pets</div>
                            <small class="text-success d-block mt-1">
                                <i class="fas fa-check-circle me-1"></i>
                                <?= $stats['total_animals'] ?> Total
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-value">
                                <span class="pending-orders-count"><?= $stats['pending_orders'] ?></span>
                            </div>
                            <div class="stat-label">Pending Adoptions</div>
                            <small class="text-info d-block mt-1">
                                <i class="fas fa-calendar-day me-1"></i>
                                <span class="today-orders-count"><?= $stats['today_orders'] ?></span> Today
                            </small>
                            <small class="text-warning d-block">
                                <i class="fas fa-cog me-1"></i>
                                <?= $stats['processing_orders'] ?> Processing
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-value">₱<?= number_format($stats['total_payments'] ?? 0, 2) ?></div>
                            <div class="stat-label">Total Payments</div>
                            <small class="text-success d-block mt-1">
                                <i class="fas fa-check-circle me-1"></i>
                                <?= $stats['completed_orders'] ?> Completed
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon yellow">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-value"><?= $stats['pending_animals'] ?></div>
                            <div class="stat-label">Proposed Pets</div>
                            <small class="text-warning d-block mt-1">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Needs Review
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon red">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="stat-value"><?= $stats['shipped_orders'] ?></div>
                            <div class="stat-label">Shipped Adoptions</div>
                            <small class="text-info d-block mt-1">
                                <i class="fas fa-shipping-fast me-1"></i>
                                In Transit
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Sales Overview -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="dashboard-section" onclick="window.location.href='/staff/sales-report'" style="cursor: pointer;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="section-title mb-0">
                                    <i class="fas fa-chart-line"></i>
                                    Sales Overview
                                </h4>
                                <select id="salesPeriod" class="form-select" style="width: 150px;">
                                    <option value="day">Today</option>
                                    <option value="week" selected>This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="year">This Year</option>
                                </select>
                            </div>
                            <canvas id="salesChart" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <!-- <div class="row mt-4">
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
                                    <i class="fas fa-check"></i> Confirm Adoptions
                                </a>
                                <a href="/staff/sales-report" class="btn btn-warning">
                                    <i class="fas fa-file-download"></i> Generate Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div> -->
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

        // ==================== SALES CHART ====================
        let currentChart = null;
        
        function initSalesChart(period = 'week') {
            fetch('/staff/api/sales-data?period=' + period)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        createSalesChart(data.labels, data.sales, data.orders);
                    }
                })
                .catch(error => {
                    console.error('Error loading sales data:', error);
                    // Use sample data if API fails
                    const labels = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Today'];
                    const sales = [0, 0, 0, 0, 0, 0, 0];
                    const orders = [0, 0, 0, 0, 0, 0, 0];
                    createSalesChart(labels, sales, orders);
                });
        }
        
        // Handle period change
        const salesPeriodSelect = document.getElementById('salesPeriod');
        if (salesPeriodSelect) {
            salesPeriodSelect.addEventListener('change', function() {
                initSalesChart(this.value);
            });
        }

        function createSalesChart(labels, salesData, ordersData) {
            // Destroy previous chart if it exists
            if (currentChart) {
                currentChart.destroy();
            }
            
            const ctx = document.getElementById('salesChart');
            if (!ctx) return;
            
            currentChart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Sales (₱)',
                            data: salesData,
                            borderColor: '#ff6b35',
                            backgroundColor: 'rgba(255, 107, 53, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointBackgroundColor: '#ff6b35',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Orders',
                            data: ordersData,
                            borderColor: '#f7931e',
                            backgroundColor: 'rgba(247, 147, 30, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointBackgroundColor: '#f7931e',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        if (context.datasetIndex === 0) {
                                            label += '₱' + context.parsed.y.toLocaleString();
                                        } else {
                                            label += context.parsed.y + ' orders';
                                        }
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                },
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            },
                            ticks: {
                                callback: function(value) {
                                    return value;
                                },
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize sales chart on page load
        document.addEventListener('DOMContentLoaded', function() {
            initSalesChart('week');
        });

        // Auto-refresh adoption updates every 10 seconds
        setInterval(() => {
            refreshOrderUpdates();
        }, 10000);

        // Function to refresh adoption updates
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

        // Update adoption statistics
        function updateOrderStats(data) {
            // Update pending adoptions count with animation
            const pendingElements = document.querySelectorAll('.pending-orders-count');
            pendingElements.forEach(el => {
                const oldValue = parseInt(el.textContent) || 0;
                const newValue = data.pendingCount;
                
                if (newValue > oldValue) {
                    // Add pulsing animation for new adoptions
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
                    // Add pulsing animation for new adoptions
                    el.style.animation = 'pulse 1s ease-in-out';
                    setTimeout(() => {
                        el.style.animation = '';
                    }, 1000);
                }
                
                el.textContent = data.todayCount;
            });
        }

        // Update recent adoptions section
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

        // Real-time stat updates
        function updateStats() {
            fetch('/api/dashboard-stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update stat cards
                        document.querySelector('.pending-orders-count').textContent = data.stats.pending_orders || 0;
                        document.querySelector('.today-orders-count').textContent = data.stats.today_orders || 0;
                        
                        // Update other stats if elements exist
                        const statElements = {
                            'available-animals': data.stats.available_animals,
                            'total-animals': data.stats.total_animals,
                            'total-payments': data.stats.total_payments,
                            'pending-reservations': data.stats.pending_reservations,
                            'pending-animals': data.stats.pending_animals,
                            'shipped-orders': data.stats.shipped_orders,
                            'processing-orders': data.stats.processing_orders,
                            'completed-orders': data.stats.completed_orders
                        };
                        
                        Object.keys(statElements).forEach(key => {
                            const element = document.querySelector(`[data-stat="${key}"]`);
                            if (element) {
                                element.textContent = statElements[key] || 0;
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error updating stats:', error);
                });
        }

        // Initial load
        refreshOrderUpdates();
        updateStats();

        // Auto-refresh stats every 30 seconds
        setInterval(updateStats, 30000);

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

