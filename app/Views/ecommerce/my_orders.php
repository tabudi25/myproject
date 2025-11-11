<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
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

        .orders-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .orders-table {
            width: 100%;
            table-layout: auto;
        }

        .table-responsive {
            overflow-x: hidden !important;
            overflow-y: visible !important;
        }


        .orders-table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .orders-table thead th {
            border: none;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .orders-table tbody tr {
            transition: all 0.3s ease;
        }

        .orders-table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }

        .orders-table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
            word-wrap: break-word;
            max-width: 200px;
        }

        .orders-table tbody td:first-child {
            max-width: 150px;
        }

        .orders-table tbody td:nth-child(3) {
            max-width: 300px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
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

        .order-number {
            font-weight: bold;
            color: var(--primary-color);
        }

        .order-amount {
            font-weight: bold;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .table-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pets-ordered {
            max-width: 300px;
        }

        .pet-item {
            padding: 5px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .pet-item:last-child {
            border-bottom: none;
        }

        .pet-item strong {
            color: var(--accent-color);
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
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
            padding: 6px 18px;
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .empty-orders {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-orders i {
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

        .filter-tabs {
            margin-bottom: 30px;
        }

        .filter-tab {
            background: none;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 10px 20px;
            margin-right: 10px;
            margin-bottom: 10px;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .filter-tab.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .filter-tab:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
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

        /* Pagination Styles */
        .pagination {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
        }

        .page-link {
            color: var(--primary-color);
            border: 2px solid #dee2e6;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .page-link:hover:not(.disabled) {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-color: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        .page-item.disabled .page-link {
            color: #adb5bd;
            pointer-events: none;
            cursor: not-allowed;
            background-color: #f8f9fa;
            border-color: #e9ecef;
            opacity: 0.6;
        }

        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            font-weight: 600;
            min-width: 120px;
        }

        /* Entries Selector Styles */
        .entries-wrapper {
            display: flex;
            align-items: center;
        }

        .entries-selector {
            display: inline-flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .entries-selector label {
            margin-bottom: 0;
            color: var(--accent-color);
            font-weight: 500;
            margin-right: 8px;
        }

        .entries-selector select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 6px 12px;
            color: var(--accent-color);
            font-weight: 500;
            transition: all 0.3s ease;
            width: auto;
            min-width: 70px;
        }

        .entries-selector select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .entries-selector select:hover {
            border-color: var(--primary-color);
        }

        /* DataTables Top Bar - Entries on Left, Search on Right */
        .dataTables-top-bar {
            display: flex !important;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
            width: 100%;
        }

        .dataTables_wrapper .dataTables_filter {
            float: none !important;
            text-align: right !important;
            margin-bottom: 0 !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            width: auto;
        }

        .dataTables_wrapper .dataTables_filter label {
            display: flex !important;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 0;
            white-space: nowrap;
        }

        .dataTables_wrapper .dataTables_filter .form-control {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 8px 15px;
        }

        .dataTables_wrapper .dataTables_filter .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e9ecef !important;
            border-radius: 25px !important;
            padding: 8px 15px !important;
            margin-left: 10px !important;
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            width: auto !important;
            min-width: 200px;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--primary-color) !important;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25) !important;
        }

        .dataTables_wrapper .dataTables_length {
            display: none !important; /* Hide default length selector since we have custom one */
        }

        @media (max-width: 768px) {
            .orders-container {
                padding: 20px;
                margin-top: 20px;
            }
            
            .table-responsive {
                overflow-x: hidden !important;
            }

            .orders-table {
                font-size: 0.85rem;
                width: 100% !important;
            }

            .orders-table thead th,
            .orders-table tbody td {
                padding: 10px 8px;
                white-space: normal;
                word-wrap: break-word;
            }

            .table-actions {
                flex-direction: column;
            }

            .btn-sm {
                padding: 5px 10px;
                font-size: 0.8rem;
            }

            .dataTables-top-bar {
                flex-direction: column;
                align-items: stretch !important;
            }

            .entries-wrapper {
                width: 100%;
                margin-bottom: 10px;
            }

            .entries-selector {
                width: 100%;
                justify-content: flex-start;
            }

            .dataTables_wrapper .dataTables_filter {
                width: 100%;
                text-align: left;
            }

            .dataTables_wrapper .dataTables_filter label {
                flex-direction: column;
                align-items: flex-start !important;
                width: 100%;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
                margin-top: 10px;
                margin-left: 0;
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
                        <a class="nav-link active" href="/my-orders">
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
                <li class="breadcrumb-item active">My Orders</li>
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
        <div class="orders-container">
            <h2 class="mb-4">
                <i class="fas fa-box me-2 text-primary"></i>
                My Orders
            </h2>
            
            <!-- Filter Tabs -->
            <div class="filter-tabs mb-4">
                <button class="filter-tab <?= !isset($_GET['status']) || $_GET['status'] === 'all' ? 'active' : '' ?>" onclick="filterOrders('all')">
                    All Orders
                </button>
                <button class="filter-tab <?= isset($_GET['status']) && $_GET['status'] === 'pending' ? 'active' : '' ?>" onclick="filterOrders('pending')">
                    Pending
                </button>
                <button class="filter-tab <?= isset($_GET['status']) && $_GET['status'] === 'confirmed' ? 'active' : '' ?>" onclick="filterOrders('confirmed')">
                    Confirmed
                </button>
                <button class="filter-tab <?= isset($_GET['status']) && $_GET['status'] === 'processing' ? 'active' : '' ?>" onclick="filterOrders('processing')">
                    Processing
                </button>
                <button class="filter-tab <?= isset($_GET['status']) && $_GET['status'] === 'shipped' ? 'active' : '' ?>" onclick="filterOrders('shipped')">
                    Shipped
                </button>
                <button class="filter-tab <?= isset($_GET['status']) && $_GET['status'] === 'delivered' ? 'active' : '' ?>" onclick="filterOrders('delivered')">
                    Delivered
                </button>
                <button class="filter-tab <?= isset($_GET['status']) && $_GET['status'] === 'cancelled' ? 'active' : '' ?>" onclick="filterOrders('cancelled')">
                    Cancelled
                </button>
            </div>
            
            <?php if (!empty($orders)): ?>
                <div class="table-responsive">
                    <table class="table orders-table" id="ordersTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>Order Number</th>
                                <th><i class="fas fa-paw me-2"></i>Pets Ordered</th>
                                <th><i class="fas fa-peso-sign me-2"></i>Amount</th>
                                <th><i class="fas fa-calendar me-2"></i>Date</th>
                                <th><i class="fas fa-truck me-2"></i>Delivery Type</th>
                                <th><i class="fas fa-credit-card me-2"></i>Payment Method</th>
                                <th><i class="fas fa-tag me-2"></i>Status</th>
                                <th><i class="fas fa-cog me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <span class="order-number">#<?= esc($order['order_number']) ?></span>
                                    </td>
                                    <td>
                                        <?php if (isset($order['items']) && !empty($order['items'])): ?>
                                            <div class="pets-ordered">
                                                <?php foreach ($order['items'] as $item): ?>
                                                    <div class="pet-item mb-1">
                                                        <i class="fas fa-paw me-1 text-primary"></i>
                                                        <strong><?= esc($item['animal_name'] ?? 'Unknown Pet') ?></strong>
                                                        <br>
                                                        <small class="text-muted ms-3">
                                                            <i class="fas fa-tag me-1"></i><?= esc($item['category_name'] ?? 'Unknown Category') ?>
                                                            <?php if (($item['quantity'] ?? 1) > 1): ?>
                                                                <span class="ms-2">
                                                                    <i class="fas fa-times me-1"></i><?= $item['quantity'] ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </small>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="order-amount">₱<?= number_format($order['total_amount'], 2) ?></span>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar me-1 text-muted"></i>
                                        <?= 
                                            !empty($order['created_at']) && $order['created_at'] !== '0000-00-00 00:00:00' 
                                                ? date('M d, Y', strtotime($order['created_at'])) 
                                                : date('M d, Y') 
                                        ?>
                                        <br>
                                        <small class="text-muted">
                                            <?= 
                                                !empty($order['created_at']) && $order['created_at'] !== '0000-00-00 00:00:00' 
                                                    ? date('g:i A', strtotime($order['created_at'])) 
                                                    : date('g:i A') 
                                            ?>
                                        </small>
                                    </td>
                                    <td>
                                        <i class="fas fa-<?= $order['delivery_type'] === 'pickup' ? 'store' : 'truck' ?> me-1"></i>
                                        <?= $order['delivery_type'] === 'pickup' ? 'Store Pickup' : 'Home Delivery' ?>
                                    </td>
                                    <td>
                                        <?php
                                        $paymentMethods = [
                                            'cod' => 'Cash on Delivery',
                                            'gcash' => 'GCash',
                                            'bank_transfer' => 'Bank Transfer'
                                        ];
                                        echo $paymentMethods[$order['payment_method']] ?? ucfirst($order['payment_method']);
                                        ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= $order['status'] ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="/order/<?= $order['id'] ?>" class="btn btn-primary btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($order['status'] === 'pending'): ?>
                                                <button class="btn btn-outline-danger btn-sm" onclick="cancelOrder(<?= $order['id'] ?>)" title="Cancel Order">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Orders pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php 
                            // Build pagination URL with status filter, search, and per_page
                            $paginationUrl = '?';
                            if (isset($currentStatus) && $currentStatus !== 'all') {
                                $paginationUrl .= 'status=' . urlencode($currentStatus) . '&';
                            }
                            if (isset($currentSearch) && !empty($currentSearch)) {
                                $paginationUrl .= 'search=' . urlencode($currentSearch) . '&';
                            }
                            if (isset($perPage) && $perPage != 10) {
                                $paginationUrl .= 'per_page=' . $perPage . '&';
                            }
                            ?>
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= $paginationUrl ?>page=<?= $currentPage - 1 ?>">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </span>
                                </li>
                            <?php endif; ?>

                            <?php
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                            
                            if ($startPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= $paginationUrl ?>page=1">1</a>
                                </li>
                                <?php if ($startPage > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= $paginationUrl ?>page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($endPage < $totalPages): ?>
                                <?php if ($endPage < $totalPages - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= $paginationUrl ?>page=<?= $totalPages ?>"><?= $totalPages ?></a>
                                </li>
                            <?php endif; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= $paginationUrl ?>page=<?= $currentPage + 1 ?>">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <div class="text-center text-muted mt-2">
                        Showing <?= $totalOrders > 0 ? (($currentPage - 1) * $perPage) + 1 : 0 ?> to <?= min($currentPage * $perPage, $totalOrders) ?> of <?= $totalOrders ?> orders
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Empty Orders -->
                <div class="empty-orders">
                    <i class="fas fa-box-open"></i>
                    <h3>No orders yet</h3>
                    <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                    <a href="/shop" class="btn btn-primary">
                        <i class="fas fa-store me-2"></i>Start Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter orders function
        function filterOrders(status) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Build URL with filter and reset to page 1
            const url = new URL(window.location.href);
            if (status === 'all') {
                url.searchParams.delete('status');
            } else {
                url.searchParams.set('status', status);
            }
            url.searchParams.set('page', '1'); // Reset to first page when filtering
            
            // Preserve per_page parameter
            const perPage = document.getElementById('perPageSelect');
            if (perPage) {
                const perPageValue = perPage.value;
                if (perPageValue && perPageValue !== '10') {
                    url.searchParams.set('per_page', perPageValue);
                } else {
                    url.searchParams.delete('per_page');
                }
            }
            
            // Preserve search parameter
            const searchInput = document.getElementById('orderSearchInput');
            if (searchInput && searchInput.value.trim()) {
                url.searchParams.set('search', searchInput.value.trim());
            } else {
                url.searchParams.delete('search');
            }
            
            // Navigate to filtered URL
            window.location.href = url.toString();
        }

        // Change entries per page function
        function changePerPage(perPage) {
            // Build URL with new per_page value
            const url = new URL(window.location.href);
            if (perPage && perPage !== '10') {
                url.searchParams.set('per_page', perPage);
            } else {
                url.searchParams.delete('per_page');
            }
            url.searchParams.set('page', '1'); // Reset to first page when changing entries
            
            // Preserve status filter
            const status = url.searchParams.get('status');
            if (!status || status === 'all') {
                url.searchParams.delete('status');
            } else {
                url.searchParams.set('status', status);
            }
            
            // Preserve search parameter
            const searchInput = document.getElementById('orderSearchInput');
            if (searchInput && searchInput.value.trim()) {
                url.searchParams.set('search', searchInput.value.trim());
            } else {
                url.searchParams.delete('search');
            }
            
            // Navigate to new URL
            window.location.href = url.toString();
        }

        // Search orders function
        function searchOrders() {
            const searchInput = document.getElementById('orderSearchInput');
            const searchValue = searchInput ? searchInput.value.trim() : '';
            
            // Build URL with search and reset to page 1
            const url = new URL(window.location.href);
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.set('page', '1'); // Reset to first page when searching
            
            // Preserve status filter
            const status = url.searchParams.get('status');
            if (!status || status === 'all') {
                url.searchParams.delete('status');
            } else {
                url.searchParams.set('status', status);
            }
            
            // Preserve per_page parameter
            const perPage = document.getElementById('perPageSelect');
            if (perPage) {
                const perPageValue = perPage.value;
                if (perPageValue && perPageValue !== '10') {
                    url.searchParams.set('per_page', perPageValue);
                } else {
                    url.searchParams.delete('per_page');
                }
            }
            
            // Navigate to search URL
            window.location.href = url.toString();
        }

        // Handle Enter key in search input
        function handleSearchKeyPress(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchOrders();
            }
        }

        // Clear search function
        function clearSearch() {
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            url.searchParams.set('page', '1');
            
            // Preserve status filter
            const status = url.searchParams.get('status');
            if (!status || status === 'all') {
                url.searchParams.delete('status');
            }
            
            // Preserve per_page parameter
            const perPage = document.getElementById('perPageSelect');
            if (perPage) {
                const perPageValue = perPage.value;
                if (perPageValue && perPageValue !== '10') {
                    url.searchParams.set('per_page', perPageValue);
                }
            }
            
            window.location.href = url.toString();
        }

        // Initialize DataTables
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                "paging": false, // Disable DataTables pagination since we're using server-side pagination
                "searching": true,
                "ordering": true,
                "info": false,
                "scrollX": false, // Disable horizontal scrolling
                "autoWidth": false, // Disable auto width calculation
                "order": [[3, "desc"]], // Sort by date descending (column 3 is now Date)
                "columnDefs": [
                    { "orderable": false, "targets": 7 }, // Disable sorting on Actions column
                    { "width": "auto", "targets": "_all" } // Auto width for all columns
                ],
                "language": {
                    "search": "Search orders:",
                    "zeroRecords": "No matching orders found",
                    "emptyTable": "No orders available"
                },
                "dom": '<"dataTables-top-bar"<"entries-wrapper"><"dataTables_filter">>rtip',
                "initComplete": function() {
                    // Create entries selector wrapper on the left
                    const currentPerPage = <?= isset($perPage) ? $perPage : 10 ?>;
                    const currentSearch = <?= isset($currentSearch) && !empty($currentSearch) ? json_encode($currentSearch) : '""' ?>;
                    
                    const entriesHtml = `
                        <div class="entries-selector">
                            <label for="perPageSelect">Show:</label>
                            <select id="perPageSelect" class="form-select form-select-sm" onchange="changePerPage(this.value)">
                                <option value="10" ${currentPerPage == 10 ? 'selected' : ''}>10</option>
                                <option value="25" ${currentPerPage == 25 ? 'selected' : ''}>25</option>
                                <option value="50" ${currentPerPage == 50 ? 'selected' : ''}>50</option>
                                <option value="100" ${currentPerPage == 100 ? 'selected' : ''}>100</option>
                            </select>
                            <span class="ms-2">entries</span>
                        </div>
                    `;
                    
                    // Find or create entries wrapper
                    let entriesWrapper = $('.entries-wrapper');
                    if (entriesWrapper.length === 0) {
                        entriesWrapper = $('<div class="entries-wrapper"></div>');
                        $('.dataTables-top-bar').prepend(entriesWrapper);
                    }
                    entriesWrapper.html(entriesHtml);
                    
                    // Replace DataTables default search with custom server-side search
                    const filterWrapper = $('.dataTables_filter');
                    const customSearchHtml = `
                        <label>
                            <span>Search orders:</span>
                            <input type="text" 
                                   id="orderSearchInput" 
                                   class="form-control form-control-sm" 
                                   placeholder="Search by order number, pet name, category, amount..." 
                                   value="${currentSearch}"
                                   onkeypress="handleSearchKeyPress(event)"
                                   style="display: inline-block; margin-left: 10px; min-width: 250px;">
                            <button type="button" 
                                    class="btn btn-sm btn-primary ms-2" 
                                    onclick="searchOrders()"
                                    style="border-radius: 25px; padding: 6px 15px;">
                                <i class="fas fa-search"></i>
                            </button>
                            ${currentSearch ? `<button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="clearSearch()" style="border-radius: 25px; padding: 6px 15px;"><i class="fas fa-times"></i></button>` : ''}
                        </label>
                    `;
                    filterWrapper.html(customSearchHtml);
                    
                    // Ensure search filter is visible and positioned correctly
                    filterWrapper.css({
                        'display': 'block',
                        'visibility': 'visible',
                        'opacity': '1'
                    });
                }
            });
        });

        function cancelOrder(orderId) {
            Swal.fire({
                title: 'Cancel Order?',
                text: 'Are you sure you want to cancel this order?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                // Here you would make an AJAX request to cancel the order
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
                        // Reload the page to update the order status
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
            });
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
        // Real-time notification updates
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
