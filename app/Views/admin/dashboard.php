<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet">
    <script src="/js/realtime.js"></script>
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
            --accent-color: #2c3e50;
            --sidebar-bg: #343a40;
            --sidebar-hover: #495057;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #495057;
            text-align: center;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            text-decoration: none;
        }

        .sidebar-menu {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .sidebar-menu li {
            border-bottom: 1px solid #495057;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--sidebar-hover);
            color: white;
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 70px;
        }

        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--accent-color);
            cursor: pointer;
        }

        .admin-user {
            display: flex;
            align-items: center;
            margin-left: auto;
            gap: 20px;
            z-index: 100;
        }

        .notification-icon {
            position: relative;
            font-size: 1.3rem;
            color: var(--accent-color);
            cursor: pointer;
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
            top: 60px;
            right: 30px;
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

        /* Profile Dropdown Styles */
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

        .mark-all-read {
            padding: 10px 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
            cursor: pointer;
            color: var(--primary-color);
            font-weight: 500;
            transition: background 0.2s;
        }

        .mark-all-read:hover {
            background: #e9ecef;
        }

        .content-area {
            padding: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); color: #ff6b35; }
            100% { transform: scale(1); }
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-color);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .dashboard-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            color: var(--accent-color);
            font-weight: 600;
        }

        .badge {
            font-size: 0.8rem;
            padding: 6px 12px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
        }

        .quick-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .quick-action {
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 12px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .quick-action:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .content-area {
                padding: 15px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="/fluffy-admin" class="sidebar-brand">
                    <i class="fas fa-paw me-2"></i>
                    <span class="brand-text">Fluffy Admin</span>
                </a>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="/fluffy-admin" class="active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/users">
                        <i class="fas fa-users"></i>
                        <span class="menu-text">Users</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/categories">
                        <i class="fas fa-tags"></i>
                        <span class="menu-text">Categories</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/animals">
                        <i class="fas fa-paw"></i>
                        <span class="menu-text">Pets</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/orders">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="menu-text">Adoptions</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/payments">
                        <i class="fas fa-credit-card"></i>
                        <span class="menu-text">Payments</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/sales-report">
                        <i class="fas fa-chart-line"></i>
                        <span class="menu-text">Sales Report</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Top Navbar -->
            <div class="top-navbar">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                
                <!-- Real-time Clock -->
                <div class="realtime-clock me-4">
                    <div class="clock-time" style="font-size: 1.2rem; font-weight: bold; color: #2c3e50;"></div>
                    <div class="clock-date" style="font-size: 0.9rem; color: #666;"></div>
                </div>
                
                
                
                <div class="admin-user">
                    <!-- Notification Icon -->
                    <div class="notification-icon" id="notificationIcon" onclick="toggleNotifications()">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </div>
                    
                    <!-- Profile Dropdown -->
                    <div class="profile-dropdown" style="display: inline-block;">
                        <div class="profile-trigger" onclick="toggleProfileDropdown()" style="display: flex; align-items: center; background: #ff6b35; color: white; padding: 10px 18px; border-radius: 25px; cursor: pointer; font-weight: 600; box-shadow: 0 3px 10px rgba(0,0,0,0.15);">
                            <i class="fas fa-user-shield me-2"></i>
                            <span><?= esc($userName) ?></span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </div>
                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <i class="fas fa-user-shield me-2"></i>
                                <span><?= esc($userName) ?></span>
                                <small class="d-block text-muted">Administrator</small>
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

            <!-- Content Area -->
            <div class="content-area">
                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('msg')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('msg') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>


                <!-- Statistics Grid -->
                <div class="stats-grid">
                    <div class="stat-card" onclick="window.location.href='/fluffy-admin/animals'" style="cursor: pointer;">
                        <div class="stat-icon">
                            <i class="fas fa-paw"></i>
                        </div>
                        <div class="stat-number" data-stat="total-animals"><?= $stats['total_animals'] ?></div>
                        <div class="stat-label">Total Pets</div>
                        <small class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            <span data-stat="available-animals"><?= $stats['available_animals'] ?></span> Available
                        </small>
                    </div>
                    
                    <div class="stat-card" onclick="window.location.href='/fluffy-admin/orders'" style="cursor: pointer;">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-number" data-stat="total-orders"><?= $stats['total_orders'] ?></div>
                        <div class="stat-label">Total Adoptions</div>
                        <small class="text-warning">
                            <i class="fas fa-clock me-1"></i>
                            <span class="pending-orders-count" data-stat="pending-orders"><?= $stats['pending_orders'] ?></span> Pending
                        </small>
                        <small class="text-info d-block mt-1">
                            <i class="fas fa-calendar-day me-1"></i>
                            <span class="today-orders-count" data-stat="today-orders"><?= $stats['today_orders'] ?></span> Today
                        </small>
                    </div>
                    
                    <div class="stat-card" onclick="window.location.href='/fluffy-admin/users'" style="cursor: pointer;">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number" data-stat="total-users"><?= $stats['total_users'] ?></div>
                        <div class="stat-label">Total Users</div>
                        <small class="text-info">
                            <i class="fas fa-user me-1"></i>
                            <span data-stat="customer-users"><?= $stats['customer_users'] ?></span> Customers
                        </small>
                    </div>
                    
                    <div class="stat-card" onclick="window.location.href='/fluffy-admin/payments'" style="cursor: pointer;">
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-number" data-stat="total-payments">₱<?= number_format($stats['total_payments'] ?? 0, 2) ?></div>
                        <div class="stat-label">Total Payments</div>
                        <small class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            <span data-stat="completed-orders"><?= $stats['completed_orders'] ?></span> Completed
                        </small>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-peso-sign"></i>
                        </div>
                        <div class="stat-number">₱<?= number_format($monthlyRevenue, 0) ?></div>
                        <div class="stat-label">Monthly Revenue</div>
                        <small class="text-success">
                            <i class="fas fa-check me-1"></i>
                            <?= $stats['completed_orders'] ?> Completed
                        </small>
                    </div>
                    
                    <div class="stat-card" onclick="window.location.href='/fluffy-admin/orders'" style="cursor: pointer;">
                        <div class="stat-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="stat-number pending-deliveries-count"><?= $stats['pending_deliveries'] ?? 0 ?></div>
                        <div class="stat-label">Pending Deliveries</div>
                        <small class="text-warning">
                            <i class="fas fa-clock me-1"></i>
                            Awaiting Review
                        </small>
                    </div>
                </div>

                <!-- Sales Graph -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="dashboard-section" onclick="window.location.href='/fluffy-admin/sales-report'" style="cursor: pointer;"></div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="section-title mb-0" >
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

                <div class="row">
                    <!-- Recent Orders -->
                    <div class="col-lg-8">
                        <div class="dashboard-section" onclick="window.location.href='/fluffy-admin/orders'" style="cursor: pointer;">
                            <h4 class="section-title">
                                <i class="fas fa-shopping-cart"></i>
                                Recent Adoptions
                            </h4>
                            
                            <?php if (!empty($recentOrders)): ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentOrders as $order): ?>
                                                <tr>
                                                    <td><strong>#<?= esc($order['order_number']) ?></strong></td>
                                                    <td><?= esc($order['customer_name']) ?></td>
                                                    <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= $order['status'] === 'pending' ? 'warning' : ($order['status'] === 'delivered' ? 'success' : 'primary') ?>">
                                                            <?= ucfirst($order['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= 
                                                        !empty($order['created_at']) && $order['created_at'] !== '0000-00-00 00:00:00' 
                                                            ? date('M d, Y', strtotime($order['created_at'])) 
                                                            : date('M d, Y') 
                                                    ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No recent orders found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Recent Pets -->
                    <div class="col-lg-4">
                        <div class="dashboard-section" onclick="window.location.href='/fluffy-admin/animals'" style="cursor: pointer;">
                            <h4 class="section-title">
                                <i class="fas fa-paw"></i>
                                Recent Pets
                            </h4>
                            
                            <?php if (!empty($recentAnimals)): ?>
                                <?php foreach ($recentAnimals as $animal): ?>
                                    <div class="d-flex align-items-center mb-3 p-2 border rounded">
                                        <img src="/uploads/<?= $animal['image'] ?>" alt="<?= esc($animal['name']) ?>" 
                                             class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;"
                                             onerror="this.src='/web/default-pet.jpg'">
                                        <div class="flex-grow-1">
                                            <div class="fw-bold"><?= esc($animal['name']) ?></div>
                                            <small class="text-muted"><?= esc($animal['category_name']) ?> • ₱<?= number_format($animal['price'], 2) ?></small>
                                        </div>
                                        <span class="badge bg-<?= $animal['status'] === 'available' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($animal['status']) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No recent pets found.</p>
                            <?php endif; ?>
                            
                            <div class="text-center mt-3">
                                <button class="btn btn-primary" onclick="loadSection('animals')">
                                    View All Pets
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        function loadSection(section) {
            // Update active menu item
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.classList.remove('active');
            });
            
            // Add active class to clicked menu item
            event.target.classList.add('active');
            
            // Load section content
            switch(section) {
                case 'animals':
                    loadAnimalsSection();
                    break;
                case 'categories':
                    loadCategoriesSection();
                    break;
                case 'orders':
                    loadOrdersSection();
                    break;
                case 'users':
                    loadUsersSection();
                    break;
                default:
                    showAlert('info', `Loading ${section} section...`);
            }
        }

        function loadAnimalsSection() {
            fetch('/admin/animals')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayAnimalsTable(data.data);
                    } else {
                        showAlert('danger', 'Failed to load animals: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Error loading animals');
                });
        }

        function displayAnimalsTable(animals) {
            const contentArea = document.querySelector('.content-area');
            let tableHtml = `
                <div class="dashboard-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="section-title">
                            <i class="fas fa-paw"></i>
                            Manage Pets
                        </h4>
                        <button class="btn btn-primary" onclick="showAddAnimalModal()">
                            <i class="fas fa-plus me-2"></i>Add Pet
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Age</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
            `;
            
            animals.forEach(animal => {
                tableHtml += `
                    <tr>
                        <td><img src="/uploads/${animal.image}" alt="${animal.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" onerror="this.src='/web/default-pet.jpg'"></td>
                        <td>${animal.name}</td>
                        <td>${animal.category_name || 'N/A'}</td>
                        <td>${animal.age} months</td>
                        <td>₱${parseFloat(animal.price).toLocaleString('en-PH', {minimumFractionDigits: 2})}</td>
                        <td><span class="badge bg-${animal.status === 'available' ? 'success' : 'secondary'}">${animal.status}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editAnimal(${animal.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            tableHtml += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            contentArea.innerHTML = tableHtml;
        }

        function showAddAnimalModal() {
            // Create modal HTML
            const modalHtml = `
                <div class="modal fade" id="addAnimalModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Pet</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="addAnimalForm" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" required placeholder="Enter pet name">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-control" name="category_id" required>
                                            <option value="">Select Category</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Age (months)</label>
                                                <input type="number" class="form-control" name="age" required placeholder="Enter age in months">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Gender</label>
                                                <select class="form-control" name="gender" required>
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" name="price" required placeholder="0.00">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3" placeholder="Describe the pet (optional)"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Image</label>
                                        <input type="file" class="form-control" name="image" accept="image/*" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Add Pet</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            const existingModal = document.getElementById('addAnimalModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Load categories for dropdown
            loadCategoriesForDropdown();
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('addAnimalModal'));
            modal.show();
            
            // Handle form submission
            document.getElementById('addAnimalForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('/fluffy-admin/api/animals', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modal.hide();
                        showAlert('success', data.message);
                        loadAnimalsSection(); // Reload pets table
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'An error occurred while adding the pet');
                });
            });
        }

        function loadCategoriesForDropdown() {
            fetch('/fluffy-admin/api/categories')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.querySelector('#addAnimalModal select[name="category_id"]');
                        data.data.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            select.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error loading categories:', error));
        }

        function showAddCategoryModal() {
            showAlert('info', 'Category management coming soon...');
        }

        function showAddUserModal() {
            showAlert('info', 'User management coming soon...');
        }

        function loadCategoriesSection() {
            showAlert('info', 'Categories section coming soon...');
        }

        function loadOrdersSection() {
            showAlert('info', 'Orders section coming soon...');
        }

        function loadUsersSection() {
            showAlert('info', 'Users section coming soon...');
        }

        function editAnimal(id) {
            showAlert('info', `Edit pet ${id} coming soon...`);
        }


        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            const contentArea = document.querySelector('.content-area');
            contentArea.insertAdjacentHTML('afterbegin', alertHtml);
            
            setTimeout(() => {
                const alert = contentArea.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 3000);
        }

        // Real-time stat updates
        function updateDashboardStats() {
            fetch('/api/dashboard-stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update all stat elements with data attributes
                        const statElements = {
                            'total-animals': data.stats.total_animals,
                            'available-animals': data.stats.available_animals,
                            'total-orders': data.stats.total_orders,
                            'pending-orders': data.stats.pending_orders,
                            'today-orders': data.stats.today_orders,
                            'total-users': data.stats.total_users,
                            'customer-users': data.stats.customer_users,
                            'total-payments': data.stats.total_payments,
                            'completed-orders': data.stats.completed_orders,
                            'pending-deliveries': data.stats.pending_deliveries
                        };
                        
                        Object.keys(statElements).forEach(key => {
                            const elements = document.querySelectorAll(`[data-stat="${key}"]`);
                            elements.forEach(element => {
                                const oldValue = element.textContent;
                                const newValue = statElements[key] || 0;
                                
                                // Add animation for increased values
                                if (parseInt(newValue) > parseInt(oldValue)) {
                                    element.style.animation = 'pulse 1s ease-in-out';
                                    setTimeout(() => {
                                        element.style.animation = '';
                                    }, 1000);
                                }
                                
                                // Format currency for payment values
                                if (key === 'total-payments') {
                                    element.textContent = '₱' + parseFloat(newValue).toLocaleString('en-PH', {minimumFractionDigits: 2});
                                } else {
                                    element.textContent = newValue;
                                }
                            });
                        });
                    }
                })
                .catch(error => {
                    console.error('Error updating dashboard stats:', error);
                });
        }

        // Auto-refresh dashboard data every 10 seconds
        setInterval(() => {
            refreshOrderUpdates();
            updateDashboardStats();
        }, 10000);

        // Function to refresh order updates
        function refreshOrderUpdates() {
            fetch('/fluffy-admin/api/order-updates')
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
        updateDashboardStats();

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

        // Initialize Sales Chart
        let currentChart = null;
        
        function initSalesChart(period = 'week') {
            fetch('/fluffy-admin/api/sales-data?period=' + period)
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
        document.getElementById('salesPeriod').addEventListener('change', function() {
            initSalesChart(this.value);
        });

        function createSalesChart(labels, salesData, ordersData) {
            // Destroy previous chart if it exists
            if (currentChart) {
                currentChart.destroy();
            }
            
            const ctx = document.getElementById('salesChart').getContext('2d');
            currentChart = new Chart(ctx, {
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

        // Initialize chart on page load
        initSalesChart();

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
            fetch('/fluffy-admin/api/notifications')
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
            fetch(`/fluffy-admin/api/notifications/${notificationId}/read`, {
                method: 'PUT'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    
                    // Redirect to order page if applicable
                    if (orderId) {
                        window.location.href = '/fluffy-admin/orders';
                    }
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        // Mark all notifications as read
        function markAllAsRead() {
            fetch('/fluffy-admin/api/notifications/mark-all-read', {
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

        // Load pending pets count
        function loadPendingAnimalsCount() {
            fetch('/fluffy-admin/api/pending-animals')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const pendingCount = data.data.filter(animal => animal.status === 'pending').length;
                        const sidebarCount = document.getElementById('sidebarPendingCount');
                        if (sidebarCount) {
                            sidebarCount.textContent = pendingCount;
                            sidebarCount.style.display = pendingCount > 0 ? 'inline' : 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading pending animals count:', error);
                });
        }

        // Initialize real-time manager
        document.addEventListener('DOMContentLoaded', function() {
            if (window.realtimeManager) {
                window.realtimeManager.init();
            }
            loadPendingAnimalsCount();
        });
    </script>
</body>
</html>
