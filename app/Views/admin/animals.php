<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Animals - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
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
            border-bottom: 1px solid var(--black);
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
            background: var(--sidebar-hover); color: var(--black);
            border-color: var(--black);
            color: white;
        }

        .nav-tabs {
            border-bottom-color: var(--primary-color);
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            font-weight: 600;
        }
        .table img { 
            width:50px; 
            height:50px; 
            object-fit:cover; 
            border-radius:8px; 
        }

        /* Tab Styling */
        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-color: transparent;
            border-bottom-color: var(--primary-color);
            background-color: transparent;
        }

        .nav-tabs .nav-link .badge {
            font-size: 0.7rem;
            padding: 2px 6px;
        }

        /* Content Area Styling */
        .content-area {
            padding: 30px;
            background-color: var(--cream-bg);
            min-height: calc(100vh - 80px);
        }

        .page-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid var(--primary-color);
            font-weight: 600;
            color: var(--black);
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 12px;
        }

        /* Profile Dropdown Styling */
        .profile-dropdown {
            position: relative;
        }

        .profile-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            min-width: 200px;
            display: none;
            z-index: 1000;
            overflow: hidden;
        }

        .profile-menu.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        .profile-header {
            padding: 15px 20px;
            background: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        .profile-divider {
            height: 1px;
            background: #e9ecef;
            margin: 5px 0;
        }

        .profile-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--black);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .profile-item:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }

        .profile-item.logout-item {
            color: #dc3545;
        }

        .profile-item.logout-item:hover {
            background: #fff5f5;
            color: #dc3545;
        }

        /* Notification Styling */
        .notification-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .notification-item:hover {
            background: #f8f9fa;
        }

        .notification-item.unread {
            background: #fff5e6;
            font-weight: 500;
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.collapsed {
                transform: translateX(0);
            }

            .content-area {
                padding: 15px;
            }

            .page-card {
                padding: 15px;
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
                    <a href="/fluffy-admin/categories">
                        <i class="fas fa-tags"></i>
                        <span class="menu-text">Categories</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/animals" class="active">
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
                        <div class="profile-trigger" onclick="toggleProfileDropdown()" style="display: flex; align-items: center; background: #ff6b35; color: white; padding: 10px 18px; border-radius: 25px; cursor: pointer; font-weight: 600; box-shadow: 0 3px 10px rgba(0,0,0,0.15);">
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnimalModal">
                        <i class="fas fa-plus me-2"></i>Add Pet
                    </button>
                </div>
                
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-4" id="petsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                            <i class="fas fa-clock me-2"></i>Proposed Pets
                            <span class="badge bg-warning ms-2" id="pendingCount">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
                            <i class="fas fa-check-circle me-2"></i>Approved Pets
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="petsTabContent">
                    <!-- Approved Pets Tab -->
                    <div class="tab-pane fade show active" id="approved" role="tabpanel">
                        <div class="page-card">
                            <div class="table-responsive">
                                <table class="table align-middle" id="animalsTable">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Gender</th>
                                            <th>Age</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th style="width:130px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="8" class="text-center py-4">Loading...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Pets Tab -->
                    <div class="tab-pane fade" id="pending" role="tabpanel">
                        <div class="page-card">
                            <div class="table-responsive">
                                <table class="table align-middle" id="pendingAnimalsTable">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Gender</th>
                                            <th>Age</th>
                                            <th>Price</th>
                                            <th>Submitted By</th>
                                            <th>Submitted Date</th>
                                            <th style="width:100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="9" class="text-center py-4">Loading...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Pet Modal -->
    <div class="modal fade" id="addAnimalModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Pet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addAnimalForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" required placeholder="Enter pet name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select class="form-control" name="category_id" required id="categorySelect">
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                        </div>

                        <!-- Category Prices Display -->
                        <div class="mb-3" id="categoryPricesContainer" style="display: none;">
                            <label class="form-label">Fixed Prices for this Category (Set by Admin)</label>
                            <div class="alert alert-info" id="categoryPricesDisplay">
                                <i class="fas fa-info-circle"></i> Select a category to view fixed prices
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Birthdate *</label>
                                <input type="date" class="form-control" name="birthdate" required max="<?= date('Y-m-d') ?>" placeholder="YYYY-MM-DD">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender *</label>
                                <select class="form-control" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price (₱) *</label>
                                <input type="number" step="0.01" class="form-control" name="price" id="add_price" required min="0" placeholder="0.00">
                                <small class="text-muted">Note: Prices shown above are fixed by admin and cannot be edited</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Describe the pet (optional)"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Image *</label>
                                <input type="file" class="form-control" name="image" accept="image/*" required>
                            </div>
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

    <!-- Edit Animal Modal -->
    <div class="modal fade" id="editAnimalModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Animal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editAnimalForm" enctype="multipart/form-data">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" id="edit_name" required placeholder="Enter pet name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select class="form-control" name="category_id" id="edit_category_id" required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Age (months) *</label>
                                <input type="number" class="form-control" name="age" id="edit_age" required min="1" placeholder="Enter age in months">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender *</label>
                                <select class="form-control" name="gender" id="edit_gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price (₱) *</label>
                                <input type="number" step="0.01" class="form-control" name="price" id="edit_price" required min="0" placeholder="0.00">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-control" name="status" id="edit_status" required>
                                    <option value="available">Active</option>
                                    <option value="sold">Inactive</option>
                                    <!-- <option value="reserved">Reserved</option> -->
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="edit_description" rows="3" placeholder="Describe the pet (optional)"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Image (leave empty to keep current)</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                <small class="text-muted" id="edit_current_image"></small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Animal</button>
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

        // Helper function to safely format dates
        function formatDate(dateString) {
            if (!dateString || dateString === '0000-00-00 00:00:00' || dateString === '1970-01-01 00:00:00' || dateString === null || dateString === undefined) {
                return 'N/A';
            }
            try {
                const date = new Date(dateString);
                // Check if date is valid
                if (isNaN(date.getTime())) {
                    return 'N/A';
                }
                // Only return N/A if date is exactly 1970-01-01 00:00:00 (Unix epoch from null/invalid dates)
                // Allow other 1970 dates if they're valid
                if (date.getFullYear() === 1970 && date.getMonth() === 0 && date.getDate() === 1 && 
                    date.getHours() === 0 && date.getMinutes() === 0 && date.getSeconds() === 0) {
                    return 'N/A';
                }
                return date.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
            } catch (e) {
                return 'N/A';
            }
        }

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

        loadAnimals();
        loadCategories();
        loadPendingAnimals();

        let animalsTable = null;
        let pendingAnimalsTable = null;

        function loadAnimals() {
            fetch('/fluffy-admin/api/animals')
                .then(r => r.json())
                .then(({success, data}) => {
                    const tbody = document.querySelector('#animalsTable tbody');
                    if (!success) { tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Failed to load</td></tr>'; 
                        if (animalsTable) { animalsTable.destroy(); animalsTable = null; }
                        return; 
                    }
                    if (!data.length) { tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No animals found</td></tr>'; 
                        if (animalsTable) { animalsTable.destroy(); animalsTable = null; }
                        return; 
                    }
                    tbody.innerHTML = data.map(animal => `
                        <tr>
                            <td><img src="/uploads/${animal.image}" onerror="this.src='/web/default-pet.jpg'" alt="${animal.name}"></td>
                            <td>${animal.name}</td>
                            <td>${animal.category_name || 'N/A'}</td>
                            <td>${animal.gender || ''}</td>
                            <td>${animal.age} mo</td>
                            <td>₱${parseFloat(animal.price).toLocaleString()}</td>
                            <td><span class="badge bg-${animal.status==='available'?'success':animal.status==='sold'?'danger':'secondary'}">${animal.status==='available'?'Active':animal.status==='sold'?'Inactive':animal.status}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="openEdit(${animal.id})" title="Edit Pet">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                    
                    // Destroy existing DataTable if it exists
                    if (animalsTable) {
                        animalsTable.destroy();
                    }
                    
                    // Initialize DataTables
                    animalsTable = $('#animalsTable').DataTable({
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        order: [[1, 'asc']],
                        language: {
                            search: "Search:",
                            lengthMenu: "Show _MENU_ entries",
                            info: "Showing _START_ to _END_ of _TOTAL_ entries",
                            infoEmpty: "Showing 0 to 0 of 0 entries",
                            infoFiltered: "(filtered from _MAX_ total entries)"
                        }
                    });
                })
                .catch(() => {
                    document.querySelector('#animalsTable tbody').innerHTML = '<tr><td colspan="8" class="text-center text-danger">Network error</td></tr>';
                    if (animalsTable) { animalsTable.destroy(); animalsTable = null; }
                });
        }

        function loadCategories() {
            fetch('/fluffy-admin/api/categories')
                .then(r => r.json())
                .then(({success, data}) => {
                    const addSelect = document.getElementById('categorySelect');
                    const editSelect = document.getElementById('edit_category_id');
                    const options = '<option value="">Select Category</option>' + (success ? data.map(cat => `<option value="${cat.id}">${cat.name}</option>`).join('') : '');
                    addSelect.innerHTML = options;
                    editSelect.innerHTML = options;
                });
        }

        // Load category prices when category is selected in Add Pet modal
        const categorySelect = document.getElementById('categorySelect');
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                const categoryId = this.value;
                const pricesContainer = document.getElementById('categoryPricesContainer');
                const pricesDisplay = document.getElementById('categoryPricesDisplay');
                
                if (!categoryId) {
                    if (pricesContainer) pricesContainer.style.display = 'none';
                    return;
                }
                
                // Fetch prices for selected category using admin API
                fetch(`/fluffy-admin/api/category-prices`)
                    .then(r => r.json())
                    .then(({success, data}) => {
                        if (success && data) {
                            // Filter prices for the selected category
                            const categoryPrices = data.filter(p => p.category_id == categoryId);
                            
                            if (categoryPrices.length > 0) {
                                let pricesHtml = '<div class="row">';
                                categoryPrices.forEach(price => {
                                    const priceType = price.price_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                    pricesHtml += `
                                        <div class="col-md-6 mb-2">
                                            <strong>${priceType}:</strong> ₱${parseFloat(price.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                                        </div>
                                    `;
                                });
                                pricesHtml += '</div>';
                                if (pricesDisplay) {
                                    pricesDisplay.innerHTML = pricesHtml;
                                    if (pricesContainer) pricesContainer.style.display = 'block';
                                }
                            } else {
                                if (pricesDisplay) {
                                    pricesDisplay.innerHTML = '<i class="fas fa-info-circle"></i> No fixed prices set for this category yet.';
                                    if (pricesContainer) pricesContainer.style.display = 'block';
                                }
                            }
                        } else {
                            if (pricesDisplay) {
                                pricesDisplay.innerHTML = '<i class="fas fa-info-circle"></i> No fixed prices set for this category yet.';
                                if (pricesContainer) pricesContainer.style.display = 'block';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error loading category prices:', error);
                        if (pricesDisplay) {
                            pricesDisplay.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error loading prices.';
                            if (pricesContainer) pricesContainer.style.display = 'block';
                        }
                    });
            });
        }

        // Add
        document.getElementById('addAnimalForm').addEventListener('submit', function(e){
            e.preventDefault();
            const formData = new FormData(this);

            // Convert birthdate to age in months for API compatibility
            const birthdate = formData.get('birthdate');
            if (!birthdate) {
                Swal.fire({icon: 'warning', title: 'Warning', text: 'Please select the pet\'s birthdate.'});
                return;
            }
            const b = new Date(birthdate);
            const now = new Date();
            if (b > now) {
                Swal.fire({icon: 'warning', title: 'Warning', text: 'Birthdate cannot be in the future.'});
                return;
            }
            const years = now.getFullYear() - b.getFullYear();
            const months = now.getMonth() - b.getMonth();
            const days = now.getDate() - b.getDate();
            let ageMonths = years * 12 + months - (days < 0 ? 1 : 0);
            if (ageMonths < 0) ageMonths = 0;

            formData.delete('birthdate');
            formData.set('age', String(ageMonths));

            fetch('/fluffy-admin/api/animals', { method:'POST', body:formData })
                .then(r=>r.json())
                .then(res=>{
                    if(res.success){
                        bootstrap.Modal.getInstance(document.getElementById('addAnimalModal')).hide();
                        this.reset();
                        loadAnimals();
                        Swal.fire({icon: 'success', title: 'Success!', text: 'Pet added successfully!'});
                    } else { Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed'}); }
                })
                .catch(()=>Swal.fire({icon: 'error', title: 'Error', text: 'Network error'}));
        });

        // Edit
        function openEdit(id){
            fetch('/fluffy-admin/api/animals')
                .then(r => r.json())
                .then(({success, data}) => {
                    const animal = data.find(a => a.id == id);
                    if (!animal) return Swal.fire({icon: 'error', title: 'Error', text: 'Animal not found'});
                    
                    document.getElementById('edit_id').value = animal.id;
                    document.getElementById('edit_name').value = animal.name;
                    document.getElementById('edit_category_id').value = animal.category_id;
                    document.getElementById('edit_age').value = animal.age;
                    document.getElementById('edit_gender').value = animal.gender;
                    document.getElementById('edit_price').value = animal.price;
                    document.getElementById('edit_status').value = animal.status;
                    document.getElementById('edit_description').value = animal.description || '';
                    document.getElementById('edit_current_image').textContent = 'Current: ' + animal.image;
                    
                    new bootstrap.Modal(document.getElementById('editAnimalModal')).show();
                });
        }

        document.getElementById('editAnimalForm').addEventListener('submit', function(e){
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('edit_id').value;
            const newStatus = formData.get('status');
            
            // Use POST with _method override for file uploads
            formData.append('_method', 'PUT');
            
            fetch('/fluffy-admin/api/animals/'+id, { method:'POST', body:formData })
                .then(r=>r.json())
                .then(res=>{
                    if(res.success){
                        bootstrap.Modal.getInstance(document.getElementById('editAnimalModal')).hide();
                        this.reset();
                        
                        // Update the table row immediately without reloading
                        if (animalsTable) {
                            // Find the row in DataTables
                            const tableRows = document.querySelectorAll('#animalsTable tbody tr');
                            let targetRow = null;
                            
                            for (let tr of tableRows) {
                                const editBtn = tr.querySelector(`button[onclick*="openEdit(${id})"]`);
                                if (editBtn) {
                                    targetRow = tr;
                                    break;
                                }
                            }
                            
                            if (targetRow) {
                                // Get DataTables row
                                const dtRow = animalsTable.row(targetRow);
                                
                                // Update status cell
                                const statusCell = targetRow.querySelector('td:nth-child(7)');
                                if (statusCell) {
                                    const isActive = newStatus === 'available';
                                    statusCell.innerHTML = `<span class="badge bg-${isActive?'success':newStatus==='sold'?'danger':'secondary'}">${isActive?'Active':newStatus==='sold'?'Inactive':newStatus}</span>`;
                                }
                                
                                // Update DataTables row data if available
                                if (dtRow && dtRow.node()) {
                                    try {
                                        const rowData = dtRow.data();
                                        if (rowData && Array.isArray(rowData) && rowData.length >= 7) {
                                            const isActive = newStatus === 'available';
                                            rowData[6] = `<span class="badge bg-${isActive?'success':newStatus==='sold'?'danger':'secondary'}">${isActive?'Active':newStatus==='sold'?'Inactive':newStatus}</span>`;
                                            dtRow.data(rowData).draw(false);
                                        } else {
                                            animalsTable.draw(false);
                                        }
                                    } catch (e) {
                                        console.error('DataTables update error:', e);
                                        animalsTable.draw(false);
                                    }
                                } else {
                                    animalsTable.draw(false);
                                }
                            } else {
                                // If row not found, reload the table
                                loadAnimals();
                            }
                        } else {
                            // If DataTables not initialized, reload
                            loadAnimals();
                        }
                        
                        Swal.fire({icon: 'success', title: 'Success!', text: 'Pet updated successfully!'});
                    } else { 
                        Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to update animal'}); 
                    }
                })
                .catch(err=>{
                    console.error('Error:', err);
                    Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
                });
        });

        // Load pending animals
        function loadPendingAnimals() {
            fetch('/fluffy-admin/api/pending-animals')
                .then(r => r.json())
                .then(({success, data}) => {
                    const tbody = document.querySelector('#pendingAnimalsTable tbody');
                    const pendingCount = document.getElementById('pendingCount');
                    
                    if (!success) { 
                        tbody.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Failed to load pending pets</td></tr>'; 
                        if (pendingAnimalsTable) { pendingAnimalsTable.destroy(); pendingAnimalsTable = null; }
                        return; 
                    }
                    
                    // Update pending count badge
                    pendingCount.textContent = data.length;
                    
                    if (!data.length) { 
                        tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">No pending pets found</td></tr>'; 
                        if (pendingAnimalsTable) { pendingAnimalsTable.destroy(); pendingAnimalsTable = null; }
                        return; 
                    }
                    
                    tbody.innerHTML = data.map(animal => {
                        // Check if animal is already approved
                        const isApproved = animal.status === 'approved' || animal.is_approved === 1;
                        
                        if (isApproved) {
                            // Show "Done" state for already approved pets
                            return `
                                <tr>
                                    <td><img src="/uploads/${animal.image}" onerror="this.src='/web/default-pet.jpg'" alt="${animal.name}"></td>
                                    <td>${animal.name}</td>
                                    <td>${animal.category_name || 'N/A'}</td>
                                    <td>${animal.gender || ''}</td>
                                    <td>${animal.age} mo</td>
                                    <td>₱${parseFloat(animal.price).toLocaleString()}</td>
                                    <td>${animal.submitted_by || 'Staff'}</td>
                                    <td>${formatDate(animal.created_at)}</td>
                                    <td>
                                        <button class="btn btn-sm btn-secondary" disabled title="Already Approved">
                                            <i class="fas fa-check-circle"></i> Done
                                        </button>
                                    </td>
                                </tr>
                            `;
                        } else {
                            // Show "Approve" button for pending pets
                            return `
                                <tr>
                                    <td><img src="/uploads/${animal.image}" onerror="this.src='/web/default-pet.jpg'" alt="${animal.name}"></td>
                                    <td>${animal.name}</td>
                                    <td>${animal.category_name || 'N/A'}</td>
                                    <td>${animal.gender || ''}</td>
                                    <td>${animal.age} mo</td>
                                    <td>₱${parseFloat(animal.price).toLocaleString()}</td>
                                    <td>${animal.submitted_by || 'Staff'}</td>
                                    <td>${formatDate(animal.created_at)}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success" onclick="approveAnimal(${animal.id})" title="Approve Pet">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        }
                    }).join('');
                    
                    // Destroy existing DataTable if it exists
                    if (pendingAnimalsTable) {
                        pendingAnimalsTable.destroy();
                    }
                    
                    // Initialize DataTables
                    pendingAnimalsTable = $('#pendingAnimalsTable').DataTable({
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        order: [[7, 'desc']],
                        language: {
                            search: "Search:",
                            lengthMenu: "Show _MENU_ entries",
                            info: "Showing _START_ to _END_ of _TOTAL_ entries",
                            infoEmpty: "Showing 0 to 0 of 0 entries",
                            infoFiltered: "(filtered from _MAX_ total entries)"
                        }
                    });
                })
                .catch(() => {
                    document.querySelector('#pendingAnimalsTable tbody').innerHTML = '<tr><td colspan="9" class="text-center text-danger">Network error</td></tr>';
                    if (pendingAnimalsTable) { pendingAnimalsTable.destroy(); pendingAnimalsTable = null; }
                });
        }

        // Approve animal
        function approveAnimal(id) {
            Swal.fire({
                title: 'Approve Pet?',
                text: 'Are you sure you want to approve this pet? This will make it available for adoption.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (!result.isConfirmed) return;

            // Find the button and update it immediately
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            fetch(`/fluffy-admin/api/pending-animals/${id}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    // Show success message
                        Swal.fire({icon: 'success', title: 'Success!', text: 'Pet approved successfully!'});
                    
                    // Refresh both tables immediately to show persistent "Done" state
                    loadPendingAnimals();
                    loadAnimals();
                } else {
                    // Restore original button state on error
                    button.innerHTML = originalContent;
                    button.disabled = false;
                        Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to approve pet'});
                }
            })
            .catch(() => {
                // Restore original button state on error
                button.innerHTML = originalContent;
                button.disabled = false;
                    Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
                });
            });
        }

        // Tab change event to load pending animals when tab is clicked
        document.getElementById('pending-tab').addEventListener('shown.bs.tab', function () {
            loadPendingAnimals();
        });

        // Tab change event to load approved animals when tab is clicked
        document.getElementById('approved-tab').addEventListener('shown.bs.tab', function () {
            loadAnimals();
        });

    </script>
</body>
</html>

