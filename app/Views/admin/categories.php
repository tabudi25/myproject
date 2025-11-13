<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <style>
        :root {
            --primary-color: #4DD0E1;
            --secondary-color: #FF8A65;
            --dark-orange: #FF7043;
            --black: #444444;
            --dark-black: #333333;
            --light-black: #555555;
            --accent-color: #FF8A65;
            --sidebar-bg: #37474F;
            --sidebar-hover: #4DD0E1;
            --cream-bg: #F9F9F9;
            --warm-beige: #F5E6D3;
            --light-gray: #f5f5f5;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--cream-bg);
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

        .sidebar-header {
            padding: 20px;
            border-bottom: 2px solid var(--primary-color);
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
            border-bottom: 1px solid var(--light-black);
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #ffffff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--sidebar-hover);
            color: var(--black);
            font-weight: 600;
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

        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            border-bottom: 2px solid var(--primary-color);
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--black);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .sidebar-toggle:hover {
            color: var(--primary-color);
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
            color: var(--black);
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
            color: var(--black);
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
            background: var(--primary-color);
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

        .page-card { 
            background:#fff; 
            border-radius:12px; 
            padding:20px; 
            box-shadow:0 2px 10px rgba(0,0,0,.1);
            border-top: 4px solid var(--primary-color);
        }
        .category-image { 
            width:50px; 
            height:50px; 
            object-fit:cover; 
            border-radius:8px; 
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

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
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

        .btn-outline-success {
            color: #28a745;
            border-color: #28a745;
        }

        .btn-outline-success:hover {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .nav-tabs .nav-link {
            color: var(--black);
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-color: var(--primary-color) var(--primary-color) white;
            font-weight: 600;
        }

        .nav-tabs {
            border-bottom-color: var(--primary-color);
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
                    <span class="brand-text">Fluffy Planet Admin</span>
                </a>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="/fluffy-admin">
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
                    <a href="/fluffy-admin/categories" class="active">
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
                
                <div class="admin-user">
                    <!-- Notification Icon -->
                    <div class="notification-icon" id="notificationIcon" onclick="toggleNotifications()">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </div>
                    
                    <!-- Profile Dropdown -->
                    <div class="profile-dropdown" style="display: inline-block;">
                        <div class="profile-trigger" onclick="toggleProfileDropdown()" style="display: flex; align-items: center; background: #4DD0E1; color: white; padding: 10px 18px; border-radius: 25px; cursor: pointer; font-weight: 600; box-shadow: 0 3px 10px rgba(0,0,0,0.15);">
                            <i class="fas fa-user-shield me-2"></i>
                            <span>Admin</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </div>
                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <i class="fas fa-user-shield me-2"></i>
                                <span>Admin</span>
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
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-3" id="categoriesTab" role="tablist" style="border-bottom: 2px solid #dee2e6;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="categories-list-tab" data-bs-toggle="tab" data-bs-target="#categories-list" type="button" role="tab" aria-controls="categories-list" aria-selected="true">
                            <i class="fas fa-list me-2"></i>List of Categories
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="prices-list-tab" data-bs-toggle="tab" data-bs-target="#prices-list" type="button" role="tab" aria-controls="prices-list" aria-selected="false">
                            <i class="fas fa-peso-sign me-2"></i>List of Prices
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="categoriesTabContent">
                    <!-- Categories List Tab -->
                    <div class="tab-pane fade show active" id="categories-list" role="tabpanel" aria-labelledby="categories-list-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">List of Categories</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="fas fa-plus me-2"></i>Add Category
                            </button>
                        </div>
                        <div class="page-card">
                            <div class="table-responsive">
                                <table class="table align-middle" id="categoriesTable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Animals</th>
                                            <th style="width:130px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="6" class="text-center py-4">Loading...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Prices List Tab -->
                    <div class="tab-pane fade" id="prices-list" role="tabpanel" aria-labelledby="prices-list-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">List of Prices for Pet Categories</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPriceModal">
                                <i class="fas fa-plus me-2"></i>Add Price
                            </button>
                        </div>
                        <div class="page-card">
                            <div class="table-responsive">
                                <table class="table align-middle" id="pricesTable">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Price Type</th>
                                            <th>Price (₱)</th>
                                            <th style="width:150px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="4" class="text-center py-4">Loading...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="addCategoryForm" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control" name="name" required placeholder="Enter category name">
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3" placeholder="Describe the category (optional)"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <!-- <option value="">Select Status</option> -->
                    <option value="active">Active</option>
                    <!-- <option value="inactive">Inactive</option> -->
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add/Edit Price Modal -->
<div class="modal fade" id="addPriceModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Price</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="addPriceForm">
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Category *</label>
                <select class="form-control" name="category_id" id="price_category_id" required>
                    <option value="">Select Category</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Price Type *</label>
                <select class="form-control" name="price_type" id="price_type" required disabled>
                    <option value="">Please select a category first</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Price (₱) *</label>
                <input type="number" step="0.01" class="form-control" name="price" id="price_value" required min="0" placeholder="0.00">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Price</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Price Modal -->
<div class="modal fade" id="editPriceModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Price</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editPriceForm">
        <input type="hidden" id="edit_price_id" name="id">
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Category *</label>
                <select class="form-control" name="category_id" id="edit_price_category_id" required>
                    <option value="">Select Category</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Price Type *</label>
                <input type="text" class="form-control" name="price_type" id="edit_price_type" required readonly style="background-color: #e9ecef;">
            </div>
            <div class="mb-3">
                <label class="form-label">Price (₱) *</label>
                <input type="number" step="0.01" class="form-control" name="price" id="edit_price_value" required min="0">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Price</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

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

        // Toggle notification dropdown
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
            
            if (dropdown.classList.contains('show')) {
                loadNotifications();
            }
        }

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

loadCategories();

let categoriesTable = null;

function loadCategories(){
  console.log('Loading categories...');
  fetch('/fluffy-admin/api/categories')
    .then(r=>r.json())
    .then(({success, data})=>{
      console.log('Categories API response:', {success, data});
      const tbody = document.querySelector('#categoriesTable tbody');
      if(!success){ tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load</td></tr>'; 
        if (categoriesTable) { categoriesTable.destroy(); categoriesTable = null; }
        return; 
      }
      if(!data.length){ tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No categories</td></tr>'; 
        if (categoriesTable) { categoriesTable.destroy(); categoriesTable = null; }
        return; 
      }
      tbody.innerHTML = data.map(c=>{
        console.log('Category data:', c);
        const isActive = c.status === 'active';
        return `
        <tr>
          <td><strong>${c.name}</strong></td>
          <td><small class="text-muted">${c.description || 'No description'}</small></td>
          <td>${c.image?`<img class=\"category-image\" src=\"/uploads/${c.image}\" onerror=\"this.src='/web/default-pet.jpg'\">`:''}</td>
          <td><span class="badge bg-${isActive ? 'success' : 'secondary'}">${c.status}</span></td>
          <td>${c.animal_count||0}</td>
          <td>
            <button class="btn btn-sm ${isActive ? 'btn-outline-danger' : 'btn-outline-success'}" onclick="toggleCategoryStatus(${c.id}, '${c.status}', event)">
              <i class="fas fa-${isActive ? 'times' : 'check'}"></i> ${isActive ? 'Deactivate' : 'Activate'}
            </button>
          </td>
        </tr>
        `;
      }).join('');
      
      // Destroy existing DataTable if it exists
      if (categoriesTable) {
        categoriesTable.destroy();
      }
      
      // Initialize DataTables
      categoriesTable = $('#categoriesTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'asc']],
        language: {
          search: "Search:",
          lengthMenu: "Show _MENU_ entries",
          info: "Showing _START_ to _END_ of _TOTAL_ entries",
          infoEmpty: "Showing 0 to 0 of 0 entries",
          infoFiltered: "(filtered from _MAX_ total entries)"
        }
      });
    })
    .catch(error => {
      console.error('Error loading categories:', error);
      const tbody = document.querySelector('#categoriesTable tbody');
      tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error loading categories</td></tr>';
      if (categoriesTable) { categoriesTable.destroy(); categoriesTable = null; }
    });
}

document.getElementById('addCategoryForm').addEventListener('submit', function(e){
  e.preventDefault();
  const fd = new FormData(this);
  fetch('/fluffy-admin/api/categories', { method:'POST', body:fd })
    .then(r=>r.json())
    .then(res=>{ if(res.success){ bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide(); this.reset(); loadCategories(); Swal.fire({icon: 'success', title: 'Success!', text: 'Category added successfully!'}); } else { Swal.fire({icon: 'error', title: 'Error', text: res.message||'Failed'}); } });
});

function toggleCategoryStatus(categoryId, currentStatus, event) {
  const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
  const action = newStatus === 'active' ? 'activate' : 'deactivate';
  
  Swal.fire({
    title: 'Confirm Action',
    text: `Are you sure you want to ${action} this category?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#4DD0E1',
    cancelButtonColor: '#6c757d',
    confirmButtonText: `Yes, ${action} it!`
  }).then((result) => {
    if (!result.isConfirmed) return;
  
  // Show loading state - try multiple ways to find the button and row
  let button = null;
  let row = null;
  
  // Try to get button from event if provided
  if (event && event.target) {
    button = event.target.closest ? event.target.closest('button') : null;
    if (!button && event.target.tagName === 'BUTTON') {
      button = event.target;
    }
    if (!button && event.target.parentElement && event.target.parentElement.tagName === 'BUTTON') {
      button = event.target.parentElement;
    }
  }
  
  // If still no button, try to find it by categoryId in the table
  if (!button && categoriesTable) {
    const tableRows = document.querySelectorAll('#categoriesTable tbody tr');
    for (let tr of tableRows) {
      const btn = tr.querySelector(`button[onclick*="toggleCategoryStatus(${categoryId}"]`);
      if (btn) {
        button = btn;
        row = tr;
        break;
      }
    }
  }
  
  // Fallback: find by categoryId in any button
  if (!button) {
    const allButtons = document.querySelectorAll(`button[onclick*="toggleCategoryStatus(${categoryId}"]`);
    if (allButtons.length > 0) {
      button = allButtons[0];
      row = button.closest('tr');
    }
  }
  
  // If we have button but no row, try to find row
  if (button && !row) {
    row = button.closest('tr');
  }
  
  if (!button) {
    console.error('Button not found for categoryId:', categoryId);
    Swal.fire({icon: 'error', title: 'Error', text: 'Button not found. Please refresh the page.'});
    return;
  }
  
  if (!row) {
    console.error('Row not found for categoryId:', categoryId);
    // Try to reload the table as fallback
    loadCategories();
    Swal.fire({icon: 'error', title: 'Error', text: 'Row not found. Table will be refreshed.'});
    return;
  }
  
  const originalText = button.innerHTML;
  button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
  button.disabled = true;
  
  console.log('Toggling category status:', categoryId, 'to', newStatus);
  
  fetch(`/fluffy-admin/api/categories/${categoryId}/toggle-status`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `status=${newStatus}`
  })
  .then(r => {
    if (!r.ok) {
      throw new Error(`HTTP error! status: ${r.status}`);
    }
    return r.json();
  })
  .then(res => {
    if (res.success) {
      const isActive = newStatus === 'active';
      
      // Update the row cells directly - check if row still exists
      if (row && row.parentNode) {
        const statusCell = row.querySelector('td:nth-child(4)');
        const actionCell = row.querySelector('td:nth-child(6)');
        
        if (statusCell) {
          statusCell.innerHTML = `<span class="badge bg-${isActive ? 'success' : 'secondary'}">${newStatus}</span>`;
        }
        
        if (actionCell) {
          actionCell.innerHTML = `
            <button class="btn btn-sm ${isActive ? 'btn-outline-danger' : 'btn-outline-success'}" onclick="toggleCategoryStatus(${categoryId}, '${newStatus}', event)">
              <i class="fas fa-${isActive ? 'times' : 'check'}"></i> ${isActive ? 'Deactivate' : 'Activate'}
            </button>
          `;
        }
      }
      
      // Update DataTables row data if available
      if (categoriesTable && row) {
        try {
          const dtRow = categoriesTable.row(row);
          if (dtRow && dtRow.node()) {
            const rowData = dtRow.data();
            if (rowData && Array.isArray(rowData) && rowData.length >= 6) {
              // Update the status in the row data
              rowData[3] = `<span class="badge bg-${isActive ? 'success' : 'secondary'}">${newStatus}</span>`;
              rowData[5] = `
                <button class="btn btn-sm ${isActive ? 'btn-outline-danger' : 'btn-outline-success'}" onclick="toggleCategoryStatus(${categoryId}, '${newStatus}', event)">
                  <i class="fas fa-${isActive ? 'times' : 'check'}"></i> ${isActive ? 'Deactivate' : 'Activate'}
                </button>
              `;
              dtRow.data(rowData).draw(false);
            } else {
              // Fallback: just redraw
              categoriesTable.draw(false);
            }
          } else {
            // Fallback: redraw the table
            categoriesTable.draw(false);
          }
        } catch (e) {
          console.error('DataTables update error:', e);
          // Fallback: reload the table
          loadCategories();
        }
      } else {
        // If no DataTables, just reload
        loadCategories();
      }
      
      Swal.fire({icon: 'success', title: 'Success!', text: `Category ${action}d successfully!`, timer: 2000, timerProgressBar: true});
    } else {
      // Restore button on error
      if (button) {
        button.innerHTML = originalText;
        button.disabled = false;
      }
      Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to update category status'});
    }
  })
  .catch(error => {
    console.error('Error:', error);
    // Restore button on error
    if (button) {
      button.innerHTML = originalText;
      button.disabled = false;
    }
    Swal.fire({icon: 'error', title: 'Error', text: 'Network error: ' + error.message});
  });
  });
}

// ==================== PRICE MANAGEMENT ====================

let pricesTable = null;
let allCategories = [];

// Category-specific price types mapping
const categoryPriceTypes = {
    'Dog': [
        // { value: 'base_price', label: 'Base Price' },
        // { value: 'male_price', label: 'Male Price' },
        // { value: 'female_price', label: 'Female Price' },
        { value: 'puppy_price_male', label: 'Puppy Price (Male)' },
        { value: 'puppy_price_female', label: 'Puppy Price (Female)' },
        { value: 'adult_price_male', label: 'Adult Price (Male)' },
        { value: 'adult_price_female', label: 'Adult Price (Female)' },
        { value: 'senior_price_male', label: 'Senior Price (Male)' },
        { value: 'senior_price_female', label: 'Senior Price (Female)' }
    ],
    'Dogs': [
        // { value: 'base_price', label: 'Base Price' },
        // { value: 'male_price', label: 'Male Price' },
        // { value: 'female_price', label: 'Female Price' },
        { value: 'puppy_price_male', label: 'Puppy Price (Male)' },
        { value: 'puppy_price_female', label: 'Puppy Price (Female)' },
        { value: 'adult_price_male', label: 'Adult Price (Male)' },
        { value: 'adult_price_female', label: 'Adult Price (Female)' },
        { value: 'senior_price_male', label: 'Senior Price (Male)' },
        { value: 'senior_price_female', label: 'Senior Price (Female)' }
    ],
    'Cat': [
        // { value: 'base_price', label: 'Base Price' },
        // { value: 'male_price', label: 'Male Price' },
        // { value: 'female_price', label: 'Female Price' },
        { value: 'kitten_price_male', label: 'Kitten Price (Male)' },
        { value: 'kitten_price_female', label: 'Kitten Price (Female)' },
        { value: 'adult_price_male', label: 'Adult Price (Male)' },
        { value: 'adult_price_female', label: 'Adult Price (Female)' },
        { value: 'senior_price_male', label: 'Senior Price (Male)' },
        { value: 'senior_price_female', label: 'Senior Price (Female)' }
    ],
    'Cats': [
        // { value: 'base_price', label: 'Base Price' },
        // { value: 'male_price', label: 'Male Price' },
        // { value: 'female_price', label: 'Female Price' },
        { value: 'kitten_price_male', label: 'Kitten Price (Male)' },
        { value: 'kitten_price_female', label: 'Kitten Price (Female)' },
        { value: 'adult_price_male', label: 'Adult Price (Male)' },
        { value: 'adult_price_female', label: 'Adult Price (Female)' },
        { value: 'senior_price_male', label: 'Senior Price (Male)' },
        { value: 'senior_price_female', label: 'Senior Price (Female)' }
    ],
    'Bird': [
        // { value: 'base_price', label: 'Base Price' },
        // { value: 'male_price', label: 'Male Price' },
        // { value: 'female_price', label: 'Female Price' },
        { value: 'baby_price_male', label: 'Baby Price (Male)' },
        { value: 'baby_price_female', label: 'Baby Price (Female)' },
        { value: 'adult_price_male', label: 'Adult Price (Male)' },
        { value: 'adult_price_female', label: 'Adult Price (Female)' },
        { value: 'senior_price_male', label: 'Senior Price (Male)' },
        { value: 'senior_price_female', label: 'Senior Price (Female)' }
    ],
    'Birds': [
        // { value: 'base_price', label: 'Base Price' },
        // { value: 'male_price', label: 'Male Price' },
        // { value: 'female_price', label: 'Female Price' },
        { value: 'baby_price_male', label: 'Baby Price (Male)' },
        { value: 'baby_price_female', label: 'Baby Price (Female)' },
        { value: 'adult_price_male', label: 'Adult Price (Male)' },
        { value: 'adult_price_female', label: 'Adult Price (Female)' },
        { value: 'senior_price_male', label: 'Senior Price (Male)' },
        { value: 'senior_price_female', label: 'Senior Price (Female)' }
    ],
    'Fish': [
        // { value: 'base_price', label: 'Base Price' },
        { value: 'small_size', label: 'Small Size' },
        { value: 'medium_size', label: 'Medium Size' },
        { value: 'large_size', label: 'Large Size' },
        { value: 'extra_large', label: 'Extra Large' }
    ],
    'Rabbit': [
        // { value: 'base_price', label: 'Base Price' },
        // { value: 'male_price', label: 'Male Price' },
        // { value: 'female_price', label: 'Female Price' },
        { value: 'baby_price_male', label: 'Baby Price (Male)' },
        { value: 'baby_price_female', label: 'Baby Price (Female)' },
        { value: 'adult_price_male', label: 'Adult Price (Male)' },
        { value: 'adult_price_female', label: 'Adult Price (Female)' },
        { value: 'senior_price_male', label: 'Senior Price (Male)' },
        { value: 'senior_price_female', label: 'Senior Price (Female)' }
    ],
    'Rabbits': [
        // { value: 'base_price', label: 'Base Price' },
        // { value: 'male_price', label: 'Male Price' },
        // { value: 'female_price', label: 'Female Price' },
        { value: 'baby_price_male', label: 'Baby Price (Male)' },
        { value: 'baby_price_female', label: 'Baby Price (Female)' },
        { value: 'adult_price_male', label: 'Adult Price (Male)' },
        { value: 'adult_price_female', label: 'Adult Price (Female)' },
        { value: 'senior_price_male', label: 'Senior Price (Male)' },
        { value: 'senior_price_female', label: 'Senior Price (Female)' }
    ],
    'Hamster': [
        // { value: 'base_price', label: 'Base Price' },
        // { value: 'male_price', label: 'Male Price' },
        // { value: 'female_price', label: 'Female Price' },
        { value: 'baby_price_male', label: 'Baby Price (Male)' },
        { value: 'baby_price_female', label: 'Baby Price (Female)' },
        { value: 'adult_price_male', label: 'Adult Price (Male)' },
        { value: 'adult_price_female', label: 'Adult Price (Female)' },
        { value: 'senior_price_male', label: 'Senior Price (Male)' },
        { value: 'senior_price_female', label: 'Senior Price (Female)' }
    ],
    'Hamsters': [
        // { value: 'base_price', label: 'Base Price' },
        // { value: 'male_price', label: 'Male Price' },
        // { value: 'female_price', label: 'Female Price' },
        { value: 'baby_price_male', label: 'Baby Price (Male)' },
        { value: 'baby_price_female', label: 'Baby Price (Female)' },
        { value: 'adult_price_male', label: 'Adult Price (Male)' },
        { value: 'adult_price_female', label: 'Adult Price (Female)' },
        { value: 'senior_price_male', label: 'Senior Price (Male)' },
        { value: 'senior_price_female', label: 'Senior Price (Female)' }
    ]
};

// Update price type dropdown based on selected category
function updatePriceTypesForCategory(categoryName) {
    const priceTypeSelect = document.getElementById('price_type');
    
    if (!categoryName) {
        priceTypeSelect.innerHTML = '<option value="">Please select a category first</option>';
        priceTypeSelect.disabled = true;
        return;
    }
    
    // Normalize category name (handle case and plural/singular)
    const normalizedName = categoryName.trim();
    let priceTypes = categoryPriceTypes[normalizedName] || 
                     categoryPriceTypes[normalizedName + 's'] || 
                     categoryPriceTypes[normalizedName.slice(0, -1)] || // Remove 's' if plural
                     [];
    
    // Try case-insensitive matching
    if (priceTypes.length === 0) {
        for (const key in categoryPriceTypes) {
            if (key.toLowerCase() === normalizedName.toLowerCase() || 
                key.toLowerCase() === (normalizedName + 's').toLowerCase() ||
                key.toLowerCase() === normalizedName.slice(0, -1).toLowerCase()) {
                priceTypes = categoryPriceTypes[key];
                break;
            }
        }
    }
    
    if (priceTypes.length > 0) {
        priceTypeSelect.innerHTML = '<option value="">Select Price Type</option>';
        priceTypes.forEach(type => {
            priceTypeSelect.innerHTML += `<option value="${type.value}">${type.label}</option>`;
        });
        priceTypeSelect.disabled = false;
    } else {
        priceTypeSelect.innerHTML = '<option value="">No predefined types for this category</option>';
        priceTypeSelect.disabled = false;
    }
}

// Load categories for dropdown
function loadCategoriesForDropdown() {
    fetch('/fluffy-admin/api/categories')
        .then(r => r.json())
        .then(({success, data}) => {
            if (success && data) {
                allCategories = data;
                const select = document.getElementById('price_category_id');
                const editSelect = document.getElementById('edit_price_category_id');
                
                // Clear existing options except first
                select.innerHTML = '<option value="">Select Category</option>';
                editSelect.innerHTML = '<option value="">Select Category</option>';
                
                data.forEach(cat => {
                    if (cat.status === 'active') {
                        const option = `<option value="${cat.id}" data-name="${cat.name}">${cat.name}</option>`;
                        select.innerHTML += option;
                        editSelect.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
                    }
                });
                
                // Add event listener for category change in add price modal
                select.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const categoryName = selectedOption ? selectedOption.getAttribute('data-name') : '';
                    updatePriceTypesForCategory(categoryName);
                });
            }
        })
        .catch(error => console.error('Error loading categories:', error));
}


// Load prices
function loadPrices() {
    fetch('/fluffy-admin/api/category-prices')
        .then(r => r.json())
        .then(({success, data}) => {
            const tbody = document.querySelector('#pricesTable tbody');
            if (!success) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Failed to load prices</td></tr>';
                if (pricesTable) { pricesTable.destroy(); pricesTable = null; }
                return;
            }
            if (!data || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No prices set yet</td></tr>';
                if (pricesTable) { pricesTable.destroy(); pricesTable = null; }
                return;
            }
            
            tbody.innerHTML = data.map(p => {
                const category = allCategories.find(c => c.id == p.category_id);
                const categoryName = category ? category.name : 'Unknown';
                return `
                    <tr>
                        <td><strong>${categoryName}</strong></td>
                        <td>${p.price_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</td>
                        <td>₱${parseFloat(p.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editPrice(${p.id})">
                                <i class="fas fa-edit"></i> Update
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Destroy existing DataTable if it exists
            if (pricesTable) {
                pricesTable.destroy();
            }
            
            // Initialize DataTables
            pricesTable = $('#pricesTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                order: [[0, 'asc']],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                }
            });
        })
        .catch(error => {
            console.error('Error loading prices:', error);
            const tbody = document.querySelector('#pricesTable tbody');
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error loading prices</td></tr>';
            if (pricesTable) { pricesTable.destroy(); pricesTable = null; }
        });
}

// Add price form submission
document.getElementById('addPriceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const priceType = formData.get('price_type');
    
    if (!priceType) {
        Swal.fire({icon: 'error', title: 'Error', text: 'Please select a price type'});
        return;
    }
    
    const data = {
        category_id: formData.get('category_id'),
        price_type: priceType,
        price: formData.get('price')
    };
    
    fetch('/fluffy-admin/api/category-prices', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('addPriceModal')).hide();
            this.reset();
            const priceTypeSelect = document.getElementById('price_type');
            priceTypeSelect.innerHTML = '<option value="">Please select a category first</option>';
            priceTypeSelect.disabled = true;
            document.getElementById('price_category_id').value = '';
            loadPrices();
            Swal.fire({icon: 'success', title: 'Success!', text: 'Price added successfully!'});
        } else {
            Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to add price'});
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
    });
});

// Edit price
function editPrice(id) {
    fetch('/fluffy-admin/api/category-prices')
        .then(r => r.json())
        .then(({success, data}) => {
            if (success && data) {
                const price = data.find(p => p.id == id);
                if (price) {
                    document.getElementById('edit_price_id').value = price.id;
                    document.getElementById('edit_price_category_id').value = price.category_id;
                    document.getElementById('edit_price_type').value = price.price_type;
                    document.getElementById('edit_price_value').value = price.price;
                    
                    new bootstrap.Modal(document.getElementById('editPriceModal')).show();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({icon: 'error', title: 'Error', text: 'Failed to load price details'});
        });
}

// Edit price form submission
document.getElementById('editPriceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = formData.get('id');
    
    const data = {
        category_id: formData.get('category_id'),
        price_type: formData.get('price_type'),
        price: formData.get('price')
    };
    
    fetch(`/fluffy-admin/api/category-prices/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('editPriceModal')).hide();
            this.reset();
            loadPrices();
            Swal.fire({icon: 'success', title: 'Success!', text: 'Price updated successfully!'});
        } else {
            Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to update price'});
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
    });
});

// Reset price type dropdown when modal is opened
document.getElementById('addPriceModal').addEventListener('show.bs.modal', function() {
    const priceTypeSelect = document.getElementById('price_type');
    priceTypeSelect.innerHTML = '<option value="">Please select a category first</option>';
    priceTypeSelect.disabled = true;
});

// Reset price type dropdown when modal is closed
document.getElementById('addPriceModal').addEventListener('hidden.bs.modal', function() {
    const priceTypeSelect = document.getElementById('price_type');
    priceTypeSelect.innerHTML = '<option value="">Please select a category first</option>';
    priceTypeSelect.disabled = true;
    document.getElementById('price_category_id').value = '';
});

// Load prices when prices tab is shown
document.getElementById('prices-list-tab').addEventListener('shown.bs.tab', function() {
    loadCategoriesForDropdown();
    loadPrices();
});

// Load categories for dropdown on page load
loadCategoriesForDropdown();

</script>
</body>
</html>

