<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Orders - Admin</title>
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
        justify-content: space-between;
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
        background-color: var(--cream-bg);
        min-height: calc(100vh - 80px);
    }

    .page-card {
        background: white;
        border-radius: 12px;
        padding: 25px; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-top: 4px solid var(--primary-color);
        margin-bottom: 20px;
    }
    
    #deliveryConfirmationsSection {
        width: 100%;
        max-width: 100%;
        overflow: visible;
    }
    
    #deliveryConfirmationsSection .page-card {
        padding: 20px;
    }
    
    #deliveryConfirmationsSection .d-flex {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }
    
    #deliveryConfirmationsSection h5 {
        color: var(--black);
        font-weight: 600;
    }
    
    #deliveryConfirmationsSection .btn-group {
        flex-wrap: nowrap;
    }
    
    #deliveryConfirmationsSection .btn-group .btn {
        white-space: nowrap;
        padding: 6px 12px;
        font-size: 0.875rem;
    }
    
    #deliveryConfirmationsSection .btn-group .btn.active {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    
    /* Responsive adjustments for delivery confirmations section */
    @media (max-width: 768px) {
        #deliveryConfirmationsSection .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        #deliveryConfirmationsSection .btn-group {
            width: 100%;
            margin-top: 15px;
        }
        
        #deliveryConfirmationsSection .btn-group .btn {
            flex: 1;
        }
        
        #deliveryConfirmationsSection .page-card {
            padding: 15px;
        }
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        max-width: 100%;
    }
    
    #deliveryConfirmationsTable {
        width: 100% !important;
        table-layout: fixed;
        margin: 0;
    }
    
    #deliveryConfirmationsTable th,
    #deliveryConfirmationsTable td {
        padding: 10px 8px;
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    #deliveryConfirmationsTable thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid var(--primary-color);
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    /* Column width distribution */
    #deliveryConfirmationsTable th:nth-child(1),
    #deliveryConfirmationsTable td:nth-child(1) {
        width: 10%;
        min-width: 100px;
    }
    
    #deliveryConfirmationsTable th:nth-child(2),
    #deliveryConfirmationsTable td:nth-child(2) {
        width: 12%;
        min-width: 120px;
    }
    
    #deliveryConfirmationsTable th:nth-child(3),
    #deliveryConfirmationsTable td:nth-child(3) {
        width: 12%;
        min-width: 120px;
    }
    
    #deliveryConfirmationsTable th:nth-child(4),
    #deliveryConfirmationsTable td:nth-child(4) {
        width: 12%;
        min-width: 120px;
    }
    
    #deliveryConfirmationsTable th:nth-child(5),
    #deliveryConfirmationsTable td:nth-child(5) {
        width: 12%;
        min-width: 120px;
    }
    
    #deliveryConfirmationsTable th:nth-child(6),
    #deliveryConfirmationsTable td:nth-child(6) {
        width: 10%;
        min-width: 100px;
    }
    
    #deliveryConfirmationsTable th:nth-child(7),
    #deliveryConfirmationsTable td:nth-child(7) {
        width: 14%;
        min-width: 130px;
    }
    
    #deliveryConfirmationsTable th:nth-child(8),
    #deliveryConfirmationsTable td:nth-child(8) {
        width: 8%;
        min-width: 90px;
    }
    
    /* Text handling */
    #deliveryConfirmationsTable th:nth-child(1),
    #deliveryConfirmationsTable td:nth-child(1),
    #deliveryConfirmationsTable th:nth-child(5),
    #deliveryConfirmationsTable td:nth-child(5),
    #deliveryConfirmationsTable th:nth-child(6),
    #deliveryConfirmationsTable td:nth-child(6),
    #deliveryConfirmationsTable th:nth-child(7),
    #deliveryConfirmationsTable td:nth-child(7),
    #deliveryConfirmationsTable th:nth-child(8),
    #deliveryConfirmationsTable td:nth-child(8) {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Allow wrapping for name columns */
    #deliveryConfirmationsTable th:nth-child(2),
    #deliveryConfirmationsTable td:nth-child(2),
    #deliveryConfirmationsTable th:nth-child(3),
    #deliveryConfirmationsTable td:nth-child(3),
    #deliveryConfirmationsTable th:nth-child(4),
    #deliveryConfirmationsTable td:nth-child(4) {
        white-space: normal;
        word-wrap: break-word;
        word-break: break-word;
    }
    
    /* DataTables wrapper styling */
    #deliveryConfirmationsTable_wrapper {
        width: 100%;
        overflow-x: auto;
    }
    
    #deliveryConfirmationsTable_wrapper .dataTables_scrollHead,
    #deliveryConfirmationsTable_wrapper .dataTables_scrollBody {
        width: 100% !important;
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
        padding: 12px;
    }

    .table tbody td {
        vertical-align: middle;
        padding: 12px;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
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
        background-color: var(--dark-orange);
        border-color: var(--dark-orange);
        color: white;
    }

    .btn-secondary:hover {
        background-color: var(--black);
        border-color: var(--black);
        color: white;
    }
    
    #ordersTable {
        width: 100%;
    }
    

    /* Tab Styling */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        background: transparent;
    }

    .nav-tabs .nav-link:hover {
        color: var(--primary-color);
        border-bottom-color: rgba(255, 107, 53, 0.3);
        background: rgba(255, 107, 53, 0.05);
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
        background: transparent;
        font-weight: 600;
    }

    .nav-tabs .nav-link i {
        margin-right: 8px;
    }

    .tab-content {
        padding-top: 0;
    }

    .tab-pane {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
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
                <a href="/fluffy-admin/orders" class="active">
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
            <ul class="nav nav-tabs mb-3" id="ordersTab" role="tablist" style="border-bottom: 2px solid #dee2e6;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="adoptions-tab" data-bs-toggle="tab" data-bs-target="#adoptions" type="button" role="tab" aria-controls="adoptions" aria-selected="true">
                        <i class="fas fa-shopping-cart me-2"></i>List of Adoption
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="deliveries-tab" data-bs-toggle="tab" data-bs-target="#deliveries" type="button" role="tab" aria-controls="deliveries" aria-selected="false">
                        <i class="fas fa-truck me-2"></i>Delivery Confirmations
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="ordersTabContent">
                <!-- List of Adoption Tab -->
                <div class="tab-pane fade show active" id="adoptions" role="tabpanel" aria-labelledby="adoptions-tab">
                    <div class="page-card">
                        <div class="table-responsive">
                            <table class="table align-middle" id="ordersTable">
                                <thead>
                                    <tr>
                                        <th>Adoption ID</th>
                                        <th>Customer</th>
                                        <th>Pet Order</th>
                                        <th>Order Date</th>
                                        <th>Delivery Info</th>
                                        <th>Total Payment</th>
                                        <th>Payment Status</th>
                                        <th>Order Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td colspan="8" class="text-center py-4">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            
                <!-- Delivery Confirmations Tab -->
                <div class="tab-pane fade" id="deliveries" role="tabpanel" aria-labelledby="deliveries-tab">
                    <div id="deliveryConfirmationsSection">
                        <div class="page-card">
                            <!-- Section Header -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                <div>
                                    <h5 class="mb-1">
                                        <i class="fas fa-truck me-2"></i>Delivery Confirmations
                                    </h5>
                                    <small class="text-muted">Manage and review delivery confirmations submitted by staff</small>
                                </div>
                            </div>
                            
                            <!-- Table Container -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="deliveryConfirmationsTable">
                                    <thead>
                                        <tr>
                                            <th>Adoptions ID</th>
                                            <th>Customer</th>
                                            <th>Pet Order</th>
                                            <th>Staff</th>
                                            <th>Payment Amount</th>
                                            <th>Order Status</th>
                                            <th>Order Submitted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                <i class="fas fa-spinner fa-spin me-2"></i>Loading...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Order Modal -->
<div class="modal fade" id="editOrderModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editOrderForm">
        <input type="hidden" name="edit_id" id="edit_id">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Order Number</label>
              <input type="text" class="form-control" id="edit_order_number" readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Customer</label>
              <input type="text" class="form-control" id="edit_customer" readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Status *</label>
              <select class="form-control" name="status" id="edit_status" required>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Payment Status *</label>
              <select class="form-control" name="payment_status" id="edit_payment_status" required>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Delivery Type</label>
              <input type="text" class="form-control" id="edit_delivery_type" readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Total Amount</label>
              <input type="text" class="form-control" id="edit_total_amount" readonly>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Status</button>
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

// Helper function to format dates properly
function formatDate(dateString) {
    if (!dateString || dateString === '0000-00-00 00:00:00' || dateString === '1970-01-01 00:00:00') {
        return new Date().toLocaleDateString();
    }
    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
        return new Date().toLocaleDateString();
    }
    return date.toLocaleDateString();
}

loadOrders();

function loadOrders(){
  fetch('/fluffy-admin/api/orders')
    .then(r=>r.json())
    .then(({success, data})=>{
      const tbody = document.querySelector('#ordersTable tbody');
      if(!success){ tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Failed to load</td></tr>'; 
        if (ordersTable) { ordersTable.destroy(); ordersTable = null; }
        return; 
      }
      if(!data.length){ tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No orders</td></tr>'; 
        if (ordersTable) { ordersTable.destroy(); ordersTable = null; }
        return; 
      }
      tbody.innerHTML = data.map(o=>{
        // Format delivery info
        const deliveryType = o.delivery_type || 'N/A';
        const deliveryAddress = o.delivery_address ? `<br><small class="text-muted">${o.delivery_address}</small>` : '';
        const deliveryInfo = deliveryType.charAt(0).toUpperCase() + deliveryType.slice(1) + deliveryAddress;
        
        // Get pet order (from API or format from items)
        const petOrder = o.pet_order || (o.items && o.items.length > 0 ? o.items.map(item => {
          const name = item.name || 'Unknown Pet';
          const qty = item.quantity || 1;
          return qty > 1 ? name + ' (x' + qty + ')' : name;
        }).join(', ') : 'N/A');
        
        return `
        <tr data-order-id="${o.id}">
          <td><strong>${o.order_number||o.id}</strong></td>
          <td>${o.customer_name||o.user_id}</td>
          <td>${petOrder}</td>
          <td>${formatDate(o.created_at)}</td>
          <td>${deliveryInfo}</td>
          <td><strong>₱${parseFloat(o.total_amount).toLocaleString()}</strong></td>
          <td><span class="badge bg-${o.payment_status==='pending'?'warning':o.payment_status==='paid'?'success':o.payment_status==='failed'?'danger':'secondary'} text-white">${(o.payment_status||'pending').charAt(0).toUpperCase() + (o.payment_status||'pending').slice(1)}</span></td>
          <td><span class="badge bg-${o.status==='pending'?'warning':o.status==='delivered'?'success':o.status==='confirmed'?'info':o.status==='processing'?'primary':o.status==='shipped'?'info':'secondary'}">${(o.status||'pending').charAt(0).toUpperCase() + (o.status||'pending').slice(1)}</span></td>
        </tr>
      `;
      }).join('');
      
      // Destroy existing DataTable if it exists
      if (ordersTable) {
        ordersTable.destroy();
      }
      
      // Initialize DataTables
      ordersTable = $('#ordersTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[3, 'desc']], // Order by Order Date (column index 3)
        autoWidth: true,
        language: {
          search: "Search:",
          lengthMenu: "Show _MENU_ entries",
          info: "Showing _START_ to _END_ of _TOTAL_ entries",
          infoEmpty: "Showing 0 to 0 of 0 entries",
          infoFiltered: "(filtered from _MAX_ total entries)"
        }
      });
    });
}

function editOrder(id){
  fetch('/fluffy-admin/api/orders')
    .then(r=>r.json())
    .then(({success, data})=>{
      const order = data.find(o => o.id == id);
      if(!order) return Swal.fire({icon: 'error', title: 'Error', text: 'Order not found'});
      
      document.getElementById('edit_id').value = order.id;
      document.getElementById('edit_order_number').value = order.order_number || order.id;
      document.getElementById('edit_customer').value = order.customer_name || order.user_id;
      document.getElementById('edit_status').value = order.status;
      document.getElementById('edit_payment_status').value = order.payment_status || 'pending';
      document.getElementById('edit_delivery_type').value = order.delivery_type || 'N/A';
      document.getElementById('edit_total_amount').value = '₱' + parseFloat(order.total_amount).toLocaleString();
      
      new bootstrap.Modal(document.getElementById('editOrderModal')).show();
    });
}

document.getElementById('editOrderForm').addEventListener('submit', function(e){
  e.preventDefault();
  const id = document.getElementById('edit_id').value;
  const status = document.getElementById('edit_status').value;
  const paymentStatus = document.getElementById('edit_payment_status').value;
  
  if (!status) {
    Swal.fire({icon: 'warning', title: 'Warning', text: 'Please select a status'});
    return;
  }
  
  if (!paymentStatus) {
    Swal.fire({icon: 'warning', title: 'Warning', text: 'Please select a payment status'});
    return;
  }
  
  // Add confirmation dialog
  Swal.fire({
    title: 'Confirm Update',
    html: `Are you sure you want to update this order?<br><br><strong>Order Status:</strong> ${status}<br><strong>Payment Status:</strong> ${paymentStatus}`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#4DD0E1',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, update it!'
  }).then((result) => {
    if (!result.isConfirmed) return;
  
  // Use POST with _method override for better compatibility
  const formData = new URLSearchParams();
  formData.append('status', status);
  formData.append('payment_status', paymentStatus);
  formData.append('_method', 'PUT');
  
  fetch('/fluffy-admin/api/orders/'+id+'/status', { 
    method:'POST', 
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: formData 
  })
    .then(r=>r.json())
    .then(res=>{
      if(res.success){
        bootstrap.Modal.getInstance(document.getElementById('editOrderModal')).hide();
          // If delivered and paid, create a delivery confirmation server-side, then refresh confirmations
          if (status === 'delivered' && paymentStatus === 'paid') {
            fetch(`/fluffy-admin/api/orders/${id}/delivery-confirmation`, { method: 'POST' })
              .then(r => r.json())
              .then(dcRes => {
                // Remove from list if present and refresh confirmations
                const row = document.querySelector(`#ordersTable tbody tr[data-order-id="${id}"]`);
                if (row) row.remove();
                loadDeliveryConfirmations();
              })
              .catch(() => {
                // Fallback: refresh both lists
        loadOrders();
                loadDeliveryConfirmations();
              });
      } else { 
            loadOrders();
          }
          Swal.fire({icon: 'success', title: 'Success!', text: 'Order updated successfully!'});
        } else { 
          Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to update order'}); 
      }
    })
    .catch(err=>{
      console.error('Error:', err);
        Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
      });
    });
});

// Delivery Confirmation Functions
let ordersTable = null;
let deliveryConfirmationsTable = null;

function loadDeliveryConfirmations() {
    const tbody = document.querySelector('#deliveryConfirmationsTable tbody');
    tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Loading...</td></tr>';
    
    fetch('/fluffy-admin/api/delivery-confirmations')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                // Remove duplicates based on order_id (keep only one per order_id)
                const seenOrderIds = new Set();
                const seenIds = new Set();
                const uniqueData = data.data.filter(confirmation => {
                    // Skip if we've already seen this exact ID
                    if (confirmation.id && seenIds.has(confirmation.id)) {
                        console.log('Filtered out duplicate delivery confirmation ID:', confirmation.id);
                        return false;
                    }
                    seenIds.add(confirmation.id);
                    
                    // Skip if we've already seen this order_id
                    if (confirmation.order_id && seenOrderIds.has(confirmation.order_id)) {
                        console.log('Filtered out duplicate delivery confirmation for order_id:', confirmation.order_id);
                        return false;
                    }
                    if (confirmation.order_id) {
                        seenOrderIds.add(confirmation.order_id);
                    }
                    
                    return true;
                });
                
                let filteredData = uniqueData;
                
                tbody.innerHTML = filteredData.map(confirmation => {
                    // Format date properly - use created_at, confirmation_created_at, delivery_date, or current date
                    let formattedDate = '';
                    let dateToUse = confirmation.confirmation_created_at || confirmation.created_at || confirmation.confirmation_delivery_date || confirmation.delivery_date || confirmation.order_created_at || null;
                    
                    try {
                        if (dateToUse) {
                            // Normalize the date string
                            let dateStr = String(dateToUse).trim();
                            
                            // Handle different date formats
                            let date;
                            if (dateStr.includes('T')) {
                                date = new Date(dateStr);
                            } else if (dateStr.match(/^\d{4}-\d{2}-\d{2}/)) {
                                // Format: YYYY-MM-DD or YYYY-MM-DD HH:MM:SS
                                date = new Date(dateStr.replace(' ', 'T'));
                            } else {
                                date = new Date(dateStr);
                            }
                            
                            // Validate date
                            if (!isNaN(date.getTime()) && date.getFullYear() > 1970 && date.getFullYear() <= new Date().getFullYear() + 1) {
                                formattedDate = date.toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric'
                                });
                            } else {
                                // Invalid date, use current date
                                formattedDate = new Date().toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric'
                                });
                            }
                        } else {
                            // No date available, use current date
                            formattedDate = new Date().toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });
                        }
                    } catch (e) {
                        console.error('Date formatting error:', e, 'Date value:', dateToUse);
                        // Fallback to current date
                        formattedDate = new Date().toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                    }
                    
                    // Get order status badge
                    const orderStatus = confirmation.order_status || 'pending';
                    let statusBadgeClass = 'secondary';
                    let statusText = orderStatus.charAt(0).toUpperCase() + orderStatus.slice(1);
                    
                    switch(orderStatus.toLowerCase()) {
                        case 'processing':
                            statusBadgeClass = 'info';
                            break;
                        case 'shipped':
                            statusBadgeClass = 'primary';
                            break;
                        case 'delivered':
                            statusBadgeClass = 'success';
                            break;
                        case 'cancelled':
                            statusBadgeClass = 'danger';
                            break;
                        case 'pending':
                            statusBadgeClass = 'warning';
                            break;
                        default:
                            statusBadgeClass = 'secondary';
                    }
                    
                    return `
                    <tr>
                        <td>${confirmation.order_number || 'N/A'}</td>
                        <td>${confirmation.customer_name || 'N/A'}</td>
                        <td>${confirmation.animal_name || 'N/A'}</td>
                        <td>${confirmation.staff_name || 'N/A'}</td>
                        <td>₱${parseFloat(confirmation.payment_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td>
                            <span class="badge bg-${statusBadgeClass}">
                                ${statusText}
                            </span>
                        </td>
                        <td>${formattedDate}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="viewConfirmation(${confirmation.id})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${confirmation.status === 'pending' ? `
                                    <button class="btn btn-outline-success" onclick="approveConfirmation(${confirmation.id})" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
                }).join('');
                
                // Destroy existing DataTable if it exists
                if (deliveryConfirmationsTable) {
                    deliveryConfirmationsTable.destroy();
                }
                
                // Initialize DataTables
                deliveryConfirmationsTable = $('#deliveryConfirmationsTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    order: [[6, 'desc']],
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    fixedColumns: false,
                    columnDefs: [
                        { targets: [0, 5, 6, 7], className: 'text-nowrap', orderable: true },
                        { targets: [1, 2, 3, 4], className: '', orderable: true },
                        { targets: [7], orderable: false, searchable: false }
                    ],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        infoFiltered: "(filtered from _MAX_ total entries)"
                    },
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted mb-2">No delivery confirmations found</h6>
                                <small class="text-muted">Staff members haven't submitted any delivery confirmations yet.</small>
                            </div>
                        </td>
                    </tr>
                `;
                if (deliveryConfirmationsTable) { deliveryConfirmationsTable.destroy(); deliveryConfirmationsTable = null; }
            }
        })
        .catch(error => {
            console.error('Error loading delivery confirmations:', error);
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error loading data</td></tr>';
            if (deliveryConfirmationsTable) { deliveryConfirmationsTable.destroy(); deliveryConfirmationsTable = null; }
        });
}


function viewConfirmation(confirmationId) {
    // Fetch confirmation details and show modal
    fetch(`/fluffy-admin/api/delivery-confirmations/${confirmationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showConfirmationModal(data.data);
            } else {
                Swal.fire({icon: 'error', title: 'Error', text: 'Failed to load confirmation details: ' + data.message});
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
        });
}

function showConfirmationModal(confirmation) {
    const modalHtml = `
        <div class="modal fade" id="confirmationViewModal" tabindex="-1" aria-labelledby="confirmationViewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationViewModalLabel">
                            <i class="fas fa-truck"></i> Delivery Confirmation Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-info-circle"></i> Order Information</h6>
                                <p><strong>Order #:</strong> ${confirmation.order_number}</p>
                                <p><strong>Customer:</strong> ${confirmation.customer_name}</p>
                                <p><strong>Pet:</strong> ${confirmation.animal_name}</p>
                                <p><strong>Staff:</strong> ${confirmation.staff_name}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-money-bill"></i> Payment Information</h6>
                                <p><strong>Amount:</strong> ₱${parseFloat(confirmation.payment_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                                <p><strong>Method:</strong> ${confirmation.payment_method || 'N/A'}</p>
                                <p><strong>Submitted:</strong> ${(() => {
                                    // Format date properly - use created_at, confirmation_created_at, delivery_date, or current date
                                    let dateToUse = confirmation.confirmation_created_at || confirmation.created_at || confirmation.confirmation_delivery_date || confirmation.delivery_date || confirmation.order_created_at || null;
                                    let formattedDate = '';
                                    
                                    try {
                                        if (dateToUse) {
                                            let dateStr = String(dateToUse).trim();
                                            let date;
                                            if (dateStr.includes('T')) {
                                                date = new Date(dateStr);
                                            } else if (dateStr.match(/^\d{4}-\d{2}-\d{2}/)) {
                                                date = new Date(dateStr.replace(' ', 'T'));
                                            } else {
                                                date = new Date(dateStr);
                                            }
                                            
                                            if (!isNaN(date.getTime()) && date.getFullYear() > 1970 && date.getFullYear() <= new Date().getFullYear() + 1) {
                                                formattedDate = date.toLocaleString('en-US', {
                                                    year: 'numeric',
                                                    month: 'short',
                                                    day: 'numeric',
                                                    hour: 'numeric',
                                                    minute: '2-digit',
                                                    hour12: true
                                                });
                                            } else {
                                                formattedDate = new Date().toLocaleString('en-US', {
                                                    year: 'numeric',
                                                    month: 'short',
                                                    day: 'numeric',
                                                    hour: 'numeric',
                                                    minute: '2-digit',
                                                    hour12: true
                                                });
                                            }
                                        } else {
                                            formattedDate = new Date().toLocaleString('en-US', {
                                                year: 'numeric',
                                                month: 'short',
                                                day: 'numeric',
                                                hour: 'numeric',
                                                minute: '2-digit',
                                                hour12: true
                                            });
                                        }
                                    } catch (e) {
                                        formattedDate = new Date().toLocaleString('en-US', {
                                            year: 'numeric',
                                            month: 'short',
                                            day: 'numeric',
                                            hour: 'numeric',
                                            minute: '2-digit',
                                            hour12: true
                                        });
                                    }
                                    return formattedDate;
                                })()}</p>
                                <p><strong>Status:</strong> ${(() => {
                                    const paymentStatus = confirmation.payment_status || 'paid';
                                    const statusText = paymentStatus.charAt(0).toUpperCase() + paymentStatus.slice(1);
                                    let badgeClass = 'success';
                                    if (paymentStatus === 'pending') badgeClass = 'warning';
                                    else if (paymentStatus === 'failed') badgeClass = 'danger';
                                    else if (paymentStatus === 'refunded') badgeClass = 'info';
                                    return `<span class="badge bg-${badgeClass}">${statusText}</span>`;
                                })()}</p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <h6><i class="fas fa-map-marker-alt"></i> Delivery Address</h6>
                            <p class="bg-light p-3 rounded">${confirmation.delivery_address || 'N/A'}</p>
                        </div>
                        
                        ${confirmation.delivery_notes ? `
                            <div class="mb-3">
                                <h6><i class="fas fa-sticky-note"></i> Delivery Notes</h6>
                                <p class="bg-light p-3 rounded">${confirmation.delivery_notes}</p>
                            </div>
                        ` : ''}
                        
                        <div class="row">
                            <div class="${confirmation.delivery_type === 'pickup' ? 'col-md-6' : 'col-md-12'}">
                                <h6><i class="fas fa-camera"></i> Delivery Photo</h6>
                                <div class="bg-light p-3 rounded text-center">
                                    ${confirmation.delivery_photo && confirmation.delivery_photo.trim() !== '' ? `
                                        <img src="/uploads/deliveries/${confirmation.delivery_photo}" 
                                             class="img-fluid" 
                                             style="max-height: 200px; cursor: pointer;" 
                                             alt="Delivery photo"
                                             onerror="this.onerror=null; this.src='/assets/img/placeholder.jpg'; this.alt='Photo not found';"
                                             onclick="window.open('/uploads/deliveries/${confirmation.delivery_photo}', '_blank')">
                                    ` : `
                                        <p class="text-muted mb-0"><i class="fas fa-image"></i> No delivery photo available</p>
                                    `}
                                </div>
                            </div>
                            ${confirmation.delivery_type === 'pickup' ? `
                                <div class="col-md-6">
                                    <h6><i class="fas fa-receipt"></i> Payment Proof Photo</h6>
                                    <div class="bg-light p-3 rounded text-center">
                                        ${confirmation.payment_photo && confirmation.payment_photo.trim() !== '' ? `
                                            <img src="/uploads/payments/${confirmation.payment_photo}" 
                                                 class="img-fluid" 
                                                 style="max-height: 200px; cursor: pointer;" 
                                                 alt="Payment proof"
                                                 onerror="this.onerror=null; this.src='/assets/img/placeholder.jpg'; this.alt='Photo not found';"
                                                 onclick="window.open('/uploads/payments/${confirmation.payment_photo}', '_blank')">
                                        ` : `
                                            <p class="text-muted mb-0"><i class="fas fa-image"></i> No payment proof photo available</p>
                                        `}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        ${confirmation.status === 'pending' ? `
                            <button type="button" class="btn btn-success" onclick="approveConfirmation(${confirmation.id})">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('confirmationViewModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('confirmationViewModal'));
    modal.show();
}

function approveConfirmation(confirmationId) {
    console.log('Approving confirmation ID:', confirmationId);
    
    Swal.fire({
        title: 'Approve Delivery Confirmation?',
        text: 'Are you sure you want to approve this delivery confirmation?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, approve it!',
        input: 'textarea',
        inputPlaceholder: 'Add admin notes (optional)',
        inputAttributes: {
            'aria-label': 'Type your notes here'
        }
    }).then((result) => {
        if (!result.isConfirmed) return;
        
        const notes = result.value || '';
        const formData = new URLSearchParams();
        formData.append('admin_notes', notes);
        
        console.log('Sending approval request to:', `/fluffy-admin/api/delivery-confirmations/${confirmationId}/approve`);
        
        fetch(`/fluffy-admin/api/delivery-confirmations/${confirmationId}/approve`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                Swal.fire({icon: 'success', title: 'Success!', text: 'Delivery confirmation approved successfully!'});
                loadDeliveryConfirmations();
                loadOrders(); // Refresh orders
                // Close modal if open
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmationViewModal'));
                if (modal) modal.hide();
            } else {
                Swal.fire({icon: 'error', title: 'Error', text: 'Failed to approve delivery confirmation: ' + data.message});
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
        });
        });
}

// Load data on page load
document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
    loadDeliveryConfirmations();
});
</script>
</body>
</html>
