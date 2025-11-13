<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .sidebar-item:hover, .sidebar-item.active {
            background: var(--sidebar-hover);
            color: var(--black);
            font-weight: 600;
        }

        .sidebar-item i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
        }

        .main-content {
            padding: 30px;
            background-color: var(--cream-bg);
            min-height: calc(100vh - 76px);
        }

        .page-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-top: 4px solid var(--primary-color);
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
            padding: 12px;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 12px;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

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

        .stat-card {
            background: var(--primary-color);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
        }

        .stat-card p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin: 20px 0;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .nav-tabs .nav-link {
            color: #333;
            border: none;
            border-bottom: 2px solid transparent;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            border-bottom-color: #dee2e6;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            font-weight: 600;
        }

        .photo-thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            transition: transform 0.3s;
            border: 2px solid #e9ecef;
        }

        .photo-thumbnail:hover {
            transform: scale(1.2);
            border-color: var(--primary-color);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .badge-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .delivery-row {
            transition: all 0.3s;
        }

        .delivery-row:hover {
            background-color: rgba(77, 208, 225, 0.05);
        }

        .delivery-row.pending {
            border-left: 4px solid #ffc107;
        }

        .delivery-row.confirmed {
            border-left: 4px solid #28a745;
        }

        .delivery-row.rejected {
            border-left: 4px solid #dc3545;
        }

        .notes-cell {
            max-width: 200px;
            word-wrap: break-word;
        }

        /* DataTables Pagination Styling */
        #completedDeliveriesTable_wrapper .dataTables_paginate {
            margin-top: 20px;
            text-align: center;
        }

        #completedDeliveriesTable_wrapper .dataTables_paginate .pagination {
            justify-content: center;
            margin: 0;
        }

        #completedDeliveriesTable_wrapper .dataTables_paginate .page-item {
            margin: 0 2px;
        }

        #completedDeliveriesTable_wrapper .dataTables_paginate .page-link {
            color: var(--primary-color);
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s;
        }

        #completedDeliveriesTable_wrapper .dataTables_paginate .page-link:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        #completedDeliveriesTable_wrapper .dataTables_paginate .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        #completedDeliveriesTable_wrapper .dataTables_paginate .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }

        #completedDeliveriesTable_wrapper .dataTables_length,
        #completedDeliveriesTable_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }

        /* Readonly field styling */
        .form-control[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
            opacity: 1;
        }

        #completedDeliveriesTable_wrapper .dataTables_length {
            display: flex;
            align-items: center;
        }

        #completedDeliveriesTable_wrapper .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            color: #495057;
            margin: 0;
            font-size: 0.9rem;
        }

        #completedDeliveriesTable_wrapper .dataTables_length select {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 6px 30px 6px 12px;
            margin: 0 8px;
            font-size: 0.9rem;
            background-color: white;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 16px 12px;
            appearance: none;
            cursor: pointer;
            transition: all 0.3s;
            min-width: 70px;
        }

        #completedDeliveriesTable_wrapper .dataTables_length select:hover {
            border-color: var(--primary-color);
        }

        #completedDeliveriesTable_wrapper .dataTables_length select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(77, 208, 225, 0.25);
        }

        #completedDeliveriesTable_wrapper .dataTables_filter {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        #completedDeliveriesTable_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            color: #495057;
            margin: 0;
            font-size: 0.9rem;
        }

        #completedDeliveriesTable_wrapper .dataTables_filter input {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 6px 12px;
            margin-left: 10px;
            font-size: 0.9rem;
            transition: all 0.3s;
            min-width: 200px;
        }

        #completedDeliveriesTable_wrapper .dataTables_filter input:hover {
            border-color: var(--primary-color);
        }

        #completedDeliveriesTable_wrapper .dataTables_filter input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(77, 208, 225, 0.25);
        }

        #completedDeliveriesTable_wrapper .dataTables_info {
            padding-top: 15px;
            color: #6c757d;
            font-size: 0.9rem;
        }

        #completedDeliveriesTable_wrapper .dataTables_wrapper .row {
            margin: 0;
        }

        #completedDeliveriesTable_wrapper .dataTables_wrapper .row > div {
            padding: 10px 0;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(77, 208, 225, 0.25);
        }

        .photo-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .animal-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .animal-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(77, 208, 225, 0.1);
        }

        .animal-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-top: 4px solid var(--primary-color);
            margin-bottom: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .sidebar {
                min-height: auto;
            }

            .page-card, .content-card, .stat-card {
                padding: 15px;
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
                <span class="me-3">
                    <i class="fas fa-user-circle"></i>
                    <?= esc(session()->get('name')) ?>
                </span>
                <a href="/logout" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <a href="/staff-dashboard" class="sidebar-item">
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
                <a href="/staff/sales-report" class="sidebar-item active">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Sales Report</span>
                </a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0"><i class="fas fa-chart-line me-2"></i>Sales Report</h2>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active" id="btn-today" onclick="loadReport('today')">Today</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-week" onclick="loadReport('week')">This Week</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-month" onclick="loadReport('month')">This Month</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-year" onclick="loadReport('year')">This Year</button>
                        </div>
                    </div>

                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-4" id="salesReportTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button" role="tab">
                                <i class="fas fa-chart-line me-2"></i>Sales Report
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="delivery-tab" data-bs-toggle="tab" data-bs-target="#delivery" type="button" role="tab">
                                <i class="fas fa-truck me-2"></i>Delivery Information
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-deliveries-tab" data-bs-toggle="tab" data-bs-target="#completed-deliveries" type="button" role="tab">
                                <i class="fas fa-check-circle me-2"></i>Completed Deliveries
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="salesReportTabContent">
                        <!-- Sales Report Tab -->
                        <div class="tab-pane fade show active" id="sales" role="tabpanel">

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="stat-card">
                                <h3 id="totalAdoptions">0</h3>
                                <p><i class="fas fa-paw me-2"></i>Total Adoptions</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card">
                                <h3 id="totalSales">₱0</h3>
                                <p><i class="fas fa-peso-sign me-2"></i>Total Sales</p>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="content-card">
                                <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i>Sales Trend</h5>
                                <div class="chart-container">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="content-card">
                                <h5 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Sales by Category</h5>
                                <div class="chart-container">
                                    <canvas id="categoryChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>

                        <!-- Delivery Information Tab -->
                        <div class="tab-pane fade" id="delivery" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-truck"></i> My Delivery Confirmations
                                </h5>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#newDeliveryForm" aria-expanded="false" aria-controls="newDeliveryForm">
                                    <i class="fas fa-plus me-2"></i>New Delivery
                                </button>
                            </div>

                            <!-- New Delivery Form (Collapsible) -->
                            <div class="collapse mb-4" id="newDeliveryForm">
                                <div class="content-card">
                                    <h5 class="mb-3">
                                        <i class="fas fa-truck"></i> Confirm Pet Delivery
                                    </h5>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Note:</strong> This includes all customer adoptions (both pickup and delivery) that need delivery confirmation.
                                    </div>

                                    <form id="deliveryForm" action="/staff/delivery-confirmations/store" method="post" enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Select Order *</label>
                                                <select class="form-select" name="order_id" id="orderSelect" required>
                                                    <option value="">Choose an order...</option>
                                                </select>
                                                <small class="text-muted">Loading orders...</small>
                                            </div>
                                        </div>

                                        <!-- Order Details (will be populated via AJAX) -->
                                        <div id="orderDetails" class="mb-4" style="display: none;">
                                            <h6>Order Details</h6>
                                            <div id="orderInfo"></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3" id="deliveryAddressField">
                                                <label class="form-label">Delivery Address *</label>
                                                <textarea class="form-control" name="delivery_address" id="deliveryAddress" rows="3" required placeholder="Enter the delivery address"></textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" id="notesLabel">Delivery Notes</label>
                                                <textarea class="form-control" name="delivery_notes" id="deliveryNotes" rows="3" placeholder="Any additional notes about the delivery"></textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Payment Amount Received *</label>
                                                <input type="number" step="0.01" class="form-control" name="payment_amount" id="paymentAmount" required placeholder="0.00" readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Payment Method *</label>
                                                <input type="text" class="form-control" id="paymentMethod" readonly>
                                                <input type="hidden" name="payment_method" id="paymentMethodHidden" value="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Delivery Photo *</label>
                                                <input type="file" class="form-control" name="delivery_photo" accept="image/*" required>
                                                <small class="text-muted">Take a photo of the animal being delivered to the customer</small>
                                                <div id="deliveryPhotoPreview" class="mt-2"></div>
                                            </div>

                                            <div class="col-md-6 mb-3" id="paymentProofPhotoField" style="display: none;">
                                                <label class="form-label">Payment Proof Photo *</label>
                                                <input type="file" class="form-control" name="payment_photo" accept="image/*" id="paymentPhotoInput">
                                                <small class="text-muted">Photo of payment receipt or transaction proof</small>
                                                <div id="paymentPhotoPreview" class="mt-2"></div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#newDeliveryForm">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-check me-2"></i>Submit Delivery Confirmation
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Flash Messages -->
                            <div id="deliveryFlashMessage"></div>

                            <div id="deliveriesLoading" class="text-center py-4" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                            <div id="deliveriesContent">
                                <div id="deliveriesEmpty" class="text-center py-5">
                                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No delivery confirmations yet</h5>
                                    <p class="text-muted">Start by creating your first delivery confirmation</p>
                                </div>

                                <div id="deliveriesTable" style="display: none;">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Adoption ID</th>
                                                    <th>Customer</th>
                                                    <th>Pets name</th>
                                                    <th>Amount</th>
                                                    <th>Date</th>
                                                    <th>Photos</th>
                                                    <th>Address</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody id="deliveriesTableBody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Deliveries Tab -->
                        <div class="tab-pane fade" id="completed-deliveries" role="tabpanel">
                            <div class="content-card">
                                <h5 class="mb-3"><i class="fas fa-check-circle me-2"></i>Completed Deliveries</h5>
                                <div class="table-responsive">
                                    <table class="table align-middle" id="completedDeliveriesTable">
                                        <thead>
                                            <tr>
                                                <th>Adoptions ID</th>
                                                <th>Customer</th>
                                                <th>Pet</th>
                                                <th>Amount</th>
                                                <th>Delivery Date</th>
                                                <th>Order Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td colspan="6" class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</td></tr>
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

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Modal image">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let salesChart, categoryChart;
        let currentPeriod = 'today';
        let deliveriesTable = null;

        function loadReport(period) {
            currentPeriod = period;
            updateActiveButton(period);
            loadSalesStats();
            loadSalesData();
            loadDeliveries();
        }

        function updateActiveButton(period) {
            // Remove active class from all buttons
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to the button corresponding to the period
            const buttonMap = {
                'today': 'btn-today',
                'week': 'btn-week',
                'month': 'btn-month',
                'year': 'btn-year'
            };
            
            const buttonId = buttonMap[period];
            if (buttonId) {
                const button = document.getElementById(buttonId);
                if (button) {
                    button.classList.add('active');
                }
            }
        }

        function loadSalesStats() {
            fetch(`/staff/api/sales-stats?period=${currentPeriod}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateStatistics(data.stats);
                    }
                })
                .catch(error => {
                    console.error('Error loading sales stats:', error);
                });
        }

        function loadSalesData() {
            console.log('Loading sales data for period:', currentPeriod);
            fetch(`/staff/api/sales-data?period=${currentPeriod}`)
                .then(response => {
                    console.log('Sales data response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Sales data received:', data);
                    if (data.success) {
                        updateCharts(data);
                    } else {
                        console.error('Sales data API error:', data.message);
                        // Still try to update charts with empty data
                        updateCharts({
                            labels: [],
                            sales: [],
                            categoryLabels: [],
                            categoryData: []
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading sales data:', error);
                    // Show empty charts on error
                    updateCharts({
                        labels: [],
                        sales: [],
                        categoryLabels: [],
                        categoryData: []
                    });
                });
        }

        function updateStatistics(stats) {
            document.getElementById('totalAdoptions').textContent = stats.total_adoptions || 0;
            document.getElementById('totalSales').textContent = '₱' + (stats.total_sales || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function updateCharts(chartData) {
            console.log('Updating charts with data:', chartData);
            
            if (!chartData) {
                console.error('No chart data provided');
                chartData = {
                    labels: [],
                    sales: [],
                    categoryLabels: [],
                    categoryData: []
                };
            }

            // Ensure we have arrays
            const labels = Array.isArray(chartData.labels) ? chartData.labels : [];
            const sales = Array.isArray(chartData.sales) ? chartData.sales : [];

            // Sales Trend Chart
            const salesCtx = document.getElementById('salesChart');
            if (!salesCtx) {
                console.error('Sales chart canvas not found');
                return;
            }

            if (salesChart) {
                salesChart.destroy();
            }
            
            // If no data, show a message or empty chart
            if (labels.length === 0 || sales.length === 0) {
                console.log('No sales data to display, showing empty chart');
                salesChart = new Chart(salesCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['No Data'],
                        datasets: [{
                            label: 'Sales (₱)',
                            data: [0],
                            borderColor: '#4DD0E1',
                            backgroundColor: 'rgba(77, 208, 225, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                enabled: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                salesChart = new Chart(salesCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Sales (₱)',
                            data: sales,
                            borderColor: '#4DD0E1',
                            backgroundColor: 'rgba(77, 208, 225, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return '₱' + parseFloat(context.parsed.y).toLocaleString('en-US', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart');
            if (!categoryCtx) {
                console.error('Category chart canvas not found');
                return;
            }

            if (categoryChart) {
                categoryChart.destroy();
            }

            // Use real category data from API, or show empty state
            const categoryLabels = chartData.categoryLabels || [];
            const categoryData = chartData.categoryData || [];
            const categoryColors = chartData.categoryColors || ['#4DD0E1', '#FF8A65', '#2c3e50', '#28a745', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14'];
            
            // If no category data, show empty state
            if (categoryLabels.length === 0 || categoryData.length === 0) {
                categoryChart = new Chart(categoryCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['No Data'],
                        datasets: [{
                            data: [1],
                            backgroundColor: ['#e9ecef']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        }
                    }
                });
                return;
            }
            
            categoryChart = new Chart(categoryCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryData,
                        backgroundColor: categoryColors.slice(0, categoryLabels.length)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ₱' + parseFloat(value).toLocaleString('en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }) + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        function loadDeliveries() {
            console.log('Loading deliveries...');
            const tbody = document.querySelector('#completedDeliveriesTable tbody');
            if (!tbody) {
                console.error('Table body not found');
                return;
            }
            
            // Show loading state
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</td></tr>';
            
            // Load ALL completed deliveries regardless of period
            fetch(`/staff/api/completed-deliveries?period=all`)
                .then(response => {
                    console.log('Deliveries API response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Deliveries API response data:', data);
                    if (data.success) {
                        displayCompletedDeliveries(data.deliveries || []);
                    } else {
                        console.error('Deliveries API error:', data.message);
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h5 class="text-muted mb-2">Error Loading Orders</h5>
                                        <p class="text-muted mb-0">${data.message || 'Failed to load completed deliveries. Please try again.'}</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                        // Destroy DataTable if it exists
                        if (deliveriesTable) { 
                            try {
                                deliveriesTable.destroy(); 
                            } catch(e) {
                                console.error('Error destroying table:', e);
                            }
                            deliveriesTable = null; 
                        }
                        if ($.fn.dataTable.isDataTable('#completedDeliveriesTable')) {
                            try {
                                $('#completedDeliveriesTable').DataTable().destroy();
                            } catch(e) {
                                console.error('Error destroying DataTable from element:', e);
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading deliveries:', error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                    <h5 class="text-muted mb-2">Error Loading Orders</h5>
                                    <p class="text-muted mb-0">Network error. Please refresh the page and try again.</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    // Destroy DataTable if it exists
                    if (deliveriesTable) { 
                        try {
                            deliveriesTable.destroy(); 
                        } catch(e) {
                            console.error('Error destroying table:', e);
                        }
                        deliveriesTable = null; 
                    }
                    if ($.fn.dataTable.isDataTable('#completedDeliveriesTable')) {
                        try {
                            $('#completedDeliveriesTable').DataTable().destroy();
                        } catch(e) {
                            console.error('Error destroying DataTable from element:', e);
                        }
                    }
                });
        }

        function displayCompletedDeliveries(deliveries) {
            try {
                console.log('Displaying completed deliveries:', deliveries);
                const tbody = document.querySelector('#completedDeliveriesTable tbody');
                if (!tbody) {
                    console.error('Table body not found in displayCompletedDeliveries');
                    return;
                }

                if (!deliveries || deliveries.length === 0) {
                    console.log('No deliveries to display');
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-2">No Orders Yet</h5>
                                    <p class="text-muted mb-0">There are no completed deliveries to display at this time.</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    // Destroy DataTable if it exists
                    if (deliveriesTable) { 
                        try {
                            deliveriesTable.destroy(); 
                        } catch(e) {
                            console.error('Error destroying table:', e);
                        }
                        deliveriesTable = null; 
                    }
                    // Also check if DataTables is initialized on the element
                    if ($.fn.dataTable.isDataTable('#completedDeliveriesTable')) {
                        try {
                            $('#completedDeliveriesTable').DataTable().destroy();
                        } catch(e) {
                            console.error('Error destroying DataTable from element:', e);
                        }
                    }
                    return;
                }

                console.log(`Displaying ${deliveries.length} deliveries`);
                
                // Destroy existing DataTable if it exists BEFORE updating innerHTML
                // Check both the variable and if DataTables is initialized on the table
                if (deliveriesTable) {
                    try {
                        deliveriesTable.destroy();
                    } catch(e) {
                        console.error('Error destroying existing table:', e);
                    }
                    deliveriesTable = null;
                }
                
                // Also check if DataTables is initialized directly on the table element
                if ($.fn.dataTable.isDataTable('#completedDeliveriesTable')) {
                    try {
                        $('#completedDeliveriesTable').DataTable().destroy();
                    } catch(e) {
                        console.error('Error destroying DataTable from element:', e);
                    }
                }

                // Build table rows with proper null checks
                tbody.innerHTML = deliveries.map(delivery => {
                    const orderNumber = delivery.order_number || delivery.id || 'N/A';
                    const customerName = delivery.customer_name || 'N/A';
                    const animalName = delivery.animal_name || 'N/A';
                    const amount = delivery.amount ? parseFloat(delivery.amount) : 0;
                    const deliveryDate = delivery.delivery_date ? new Date(delivery.delivery_date).toLocaleDateString() : 'N/A';
                    
                    return `
                        <tr>
                            <td>${orderNumber}</td>
                            <td>${customerName}</td>
                            <td>${animalName}</td>
                            <td>₱${amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                            <td>${deliveryDate}</td>
                            <td><span class="badge bg-success">Completed</span></td>
                        </tr>
                    `;
                }).join('');
                
                // Initialize DataTables after a small delay to ensure DOM is ready
                setTimeout(() => {
                    try {
                        // Double-check that DataTables is not already initialized
                        const tableElement = $('#completedDeliveriesTable');
                        if (tableElement.length && !$.fn.dataTable.isDataTable('#completedDeliveriesTable')) {
                            deliveriesTable = tableElement.DataTable({
                                pageLength: 10,
                                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                order: [[4, 'desc']],
                                language: {
                                    search: "Search:",
                                    lengthMenu: "Show _MENU_ entries",
                                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                                    infoEmpty: "Showing 0 to 0 of 0 entries",
                                    infoFiltered: "(filtered from _MAX_ total entries)"
                                },
                                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                                responsive: true,
                                autoWidth: false,
                                drawCallback: function() {
                                    // Ensure pagination is properly styled after each draw
                                    $('.dataTables_paginate .pagination').addClass('pagination-sm');
                                }
                            });
                        } else if ($.fn.dataTable.isDataTable('#completedDeliveriesTable')) {
                            // If already initialized, just get the instance
                            deliveriesTable = $('#completedDeliveriesTable').DataTable();
                        }
                    } catch(e) {
                        console.error('Error initializing DataTable:', e);
                    }
                }, 100);
            } catch(error) {
                console.error('Error in displayCompletedDeliveries:', error);
                const tbody = document.querySelector('#completedDeliveriesTable tbody');
                if (tbody) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                    <h5 class="text-muted mb-2">Error Displaying Data</h5>
                                    <p class="text-muted mb-0">An error occurred while displaying deliveries. Please refresh the page.</p>
                                </div>
                            </td>
                        </tr>
                    `;
                }
                // Destroy DataTable if it exists
                if (deliveriesTable) { 
                    try {
                        deliveriesTable.destroy(); 
                    } catch(e) {
                        console.error('Error destroying table on error:', e);
                    }
                    deliveriesTable = null; 
                }
                // Also check if DataTables is initialized on the element
                if ($.fn.dataTable.isDataTable('#completedDeliveriesTable')) {
                    try {
                        $('#completedDeliveriesTable').DataTable().destroy();
                    } catch(e) {
                        console.error('Error destroying DataTable from element:', e);
                    }
                }
            }
        }

        // Check if we should show delivery tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded!');
                return;
            }
            console.log('Chart.js loaded, initializing charts...');
            // Set Today button as active by default
            updateActiveButton('today');
            loadReport('today');

            // Check URL parameter for tab
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab === 'delivery') {
                // Switch to delivery tab
                const deliveryTab = new bootstrap.Tab(document.getElementById('delivery-tab'));
                deliveryTab.show();
                // Load deliveries when tab is shown
                loadMyDeliveries();
                checkFlashMessages();
            } else if (tab === 'completed-deliveries') {
                // Switch to completed deliveries tab
                const completedDeliveriesTab = new bootstrap.Tab(document.getElementById('completed-deliveries-tab'));
                completedDeliveriesTab.show();
                // Load deliveries immediately
                loadDeliveries();
            }
        });

        // Load deliveries when completed deliveries tab is shown
        const completedDeliveriesTabEl = document.getElementById('completed-deliveries-tab');
        if (completedDeliveriesTabEl) {
            completedDeliveriesTabEl.addEventListener('shown.bs.tab', function() {
                console.log('Completed Deliveries tab shown, loading deliveries...');
                loadDeliveries();
            });
        }

        // Load deliveries when delivery tab is shown
        const deliveryTabEl = document.getElementById('delivery-tab');
        if (deliveryTabEl) {
            deliveryTabEl.addEventListener('shown.bs.tab', function() {
                console.log('Delivery tab shown, loading deliveries...');
                loadMyDeliveries();
                checkFlashMessages();
                // Load available orders if form is visible
                if (document.getElementById('newDeliveryForm') && document.getElementById('newDeliveryForm').classList.contains('show')) {
                    loadAvailableOrders();
                }
            });
            
            // Also check if tab is already active on page load
            if (deliveryTabEl.classList.contains('active')) {
                console.log('Delivery tab is active on page load, loading deliveries...');
                setTimeout(() => {
                    loadMyDeliveries();
                    checkFlashMessages();
                }, 100);
            }
        }

        // Check for flash messages from URL parameters or session
        function checkFlashMessages() {
            const urlParams = new URLSearchParams(window.location.search);
            const msg = urlParams.get('msg');
            if (msg) {
                const flashDiv = document.getElementById('deliveryFlashMessage');
                flashDiv.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${msg}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                // Remove msg and delivery_created from URL after displaying
                const newUrl = new URL(window.location);
                newUrl.searchParams.delete('msg');
                newUrl.searchParams.delete('delivery_created');
                window.history.replaceState({}, document.title, newUrl.pathname + newUrl.search);
            }
        }

        function loadMyDeliveries() {
            console.log('Loading my deliveries...');
            const loadingEl = document.getElementById('deliveriesLoading');
            const contentEl = document.getElementById('deliveriesContent');
            const emptyEl = document.getElementById('deliveriesEmpty');
            const tableEl = document.getElementById('deliveriesTable');
            const tbody = document.getElementById('deliveriesTableBody');

            if (!loadingEl || !contentEl || !emptyEl || !tableEl || !tbody) {
                console.error('Delivery elements not found');
                return;
            }

            loadingEl.style.display = 'block';
            contentEl.style.display = 'block';
            emptyEl.style.display = 'none';
            tableEl.style.display = 'none';

            fetch('/staff/api/my-deliveries')
                .then(response => {
                    console.log('API Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('API Response data:', data);
                    loadingEl.style.display = 'none';
                    contentEl.style.display = 'block';

                    if (data.success && data.data && Array.isArray(data.data) && data.data.length > 0) {
                        console.log('Found', data.data.length, 'deliveries');
                        emptyEl.style.display = 'none';
                        tableEl.style.display = 'block';
                        
                        // Clear existing content to prevent duplication
                        tbody.innerHTML = '';
                        
                        // Build rows
                        const rows = data.data.map(delivery => {
                            // Format date safely
                            let formattedDate = 'N/A';
                            try {
                                if (delivery.created_at) {
                                    const date = new Date(delivery.created_at);
                                    if (!isNaN(date.getTime())) {
                                        formattedDate = date.toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'});
                                    }
                                }
                            } catch (e) {
                                console.error('Date formatting error:', e);
                            }

                            return `
                                <tr class="delivery-row ${delivery.status || 'pending'}">
                                    <td><strong>#${delivery.order_id || delivery.order_number || 'N/A'}</strong></td>
                                    <td>${delivery.customer_name || 'N/A'}</td>
                                    <td>${delivery.animal_name || 'N/A'}</td>
                                    <td>₱${parseFloat(delivery.payment_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            ${formattedDate}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            ${delivery.delivery_photo && delivery.delivery_photo.trim() !== '' ? `
                                                <img src="/uploads/deliveries/${delivery.delivery_photo}" 
                                                     class="photo-thumbnail" 
                                                     alt="Delivery photo"
                                                     title="Delivery Photo"
                                                     onerror="this.style.display='none'; this.nextElementSibling && this.nextElementSibling.style.display='inline';"
                                                     onclick="showImageModal(this.src, 'Delivery Photo')">
                                            ` : ''}
                                            ${delivery.payment_photo && delivery.payment_photo.trim() !== '' ? `
                                                <img src="/uploads/payments/${delivery.payment_photo}" 
                                                     class="photo-thumbnail" 
                                                     alt="Payment photo"
                                                     title="Payment Proof"
                                                     onerror="this.style.display='none'; this.nextElementSibling && this.nextElementSibling.style.display='inline';"
                                                     onclick="showImageModal(this.src, 'Payment Proof')">
                                            ` : ''}
                                            ${(!delivery.delivery_photo || delivery.delivery_photo.trim() === '') && (!delivery.payment_photo || delivery.payment_photo.trim() === '') ? '<span class="text-muted">No photos</span>' : ''}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            ${delivery.delivery_address || 'N/A'}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="notes-cell">
                                            ${delivery.delivery_notes ? `
                                                <div class="mb-1">
                                                    <strong>Delivery:</strong>
                                                    <small class="text-muted d-block">${delivery.delivery_notes}</small>
                                                </div>
                                            ` : ''}
                                            ${delivery.admin_notes ? `
                                                <div>
                                                    <strong>Admin:</strong>
                                                    <small class="text-info d-block">${delivery.admin_notes}</small>
                                                </div>
                                            ` : ''}
                                            ${!delivery.delivery_notes && !delivery.admin_notes ? '<span class="text-muted">No notes</span>' : ''}
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }).join('');
                        
                        // Set innerHTML once with all rows
                        tbody.innerHTML = rows;
                    } else {
                        console.log('No deliveries found or empty data');
                        emptyEl.style.display = 'block';
                        tableEl.style.display = 'none';
                        // Restore empty state message
                        emptyEl.innerHTML = `
                            <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No delivery confirmations yet</h5>
                            <p class="text-muted">Start by creating your first delivery confirmation</p>
                        `;
                    }
                })
                .catch(err => {
                    console.error('Error loading deliveries:', err);
                    loadingEl.style.display = 'none';
                    contentEl.style.display = 'block';
                    emptyEl.style.display = 'block';
                    tableEl.style.display = 'none';
                    
                    // Show error message
                    emptyEl.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Error loading deliveries:</strong> ${err.message}
                            <br><small>Please check the console for more details.</small>
                        </div>
                    `;
                });
        }

        function showImageModal(src, title) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModalTitle').textContent = title;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        // Load available orders when delivery tab is shown
        function loadAvailableOrders() {
            const orderSelect = document.getElementById('orderSelect');
            if (!orderSelect) return;

            fetch('/staff/api/available-orders')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        orderSelect.innerHTML = '<option value="">Choose an order...</option>';
                        data.data.forEach(order => {
                            const option = document.createElement('option');
                            option.value = order.id;
                            option.textContent = `Order #${order.order_number} - ${order.customer_name} - ₱${parseFloat(order.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} (${order.delivery_type ? order.delivery_type.charAt(0).toUpperCase() + order.delivery_type.slice(1) : 'N/A'})`;
                            orderSelect.appendChild(option);
                        });
                        orderSelect.nextElementSibling.textContent = `${data.data.length} orders available`;
                    } else {
                        orderSelect.innerHTML = '<option value="">No orders available</option>';
                        orderSelect.nextElementSibling.textContent = 'No orders available';
                    }
                })
                .catch(error => {
                    console.error('Error loading orders:', error);
                    orderSelect.innerHTML = '<option value="">Error loading orders</option>';
                    orderSelect.nextElementSibling.textContent = 'Error loading orders';
                });
        }

        // Handle order selection
        if (document.getElementById('orderSelect')) {
            document.getElementById('orderSelect').addEventListener('change', function() {
                const orderId = this.value;
                const orderDetails = document.getElementById('orderDetails');
                const orderInfo = document.getElementById('orderInfo');
                
                if (orderId) {
                    loadOrderDetails(orderId);
                } else {
                    orderDetails.style.display = 'none';
                    // Reset payment fields when no order is selected
                    const paymentAmount = document.getElementById('paymentAmount');
                    const paymentMethod = document.getElementById('paymentMethod');
                    const paymentMethodHidden = document.getElementById('paymentMethodHidden');
                    const paymentProofPhotoField = document.getElementById('paymentProofPhotoField');
                    const paymentPhotoInput = document.getElementById('paymentPhotoInput');
                    
                    if (paymentAmount) paymentAmount.value = '';
                    if (paymentMethod) paymentMethod.value = '';
                    if (paymentMethodHidden) paymentMethodHidden.value = '';
                    if (paymentProofPhotoField) paymentProofPhotoField.style.display = 'none';
                    if (paymentPhotoInput) {
                        paymentPhotoInput.required = false;
                        paymentPhotoInput.value = '';
                    }
                }
            });
        }

        // Load order details via AJAX
        function loadOrderDetails(orderId) {
            console.log('Loading order details for ID:', orderId);
            
            const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]')?.value || '';
            fetch('/staff/api/order-details-delivery', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: 'order_id=' + orderId
            })
            .then(response => response.json())
            .then(data => {
                console.log('Order details response:', data);
                if (data.success) {
                    displayOrderDetails(data.order, data.items);
                } else {
                    console.error('Error loading order details:', data.message);
                    Swal.fire({icon: 'error', title: 'Error', text: data.message || 'Failed to load order details.'});
                    document.getElementById('orderDetails').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading order details:', error);
                Swal.fire({icon: 'error', title: 'Error', text: 'Error loading order details. Please try again.'});
            });
        }

        // Display order details
        function displayOrderDetails(order, items) {
            const orderInfo = document.getElementById('orderInfo');
            const orderDetails = document.getElementById('orderDetails');
            const deliveryAddress = document.getElementById('deliveryAddress');
            const deliveryAddressField = document.getElementById('deliveryAddressField');
            const notesLabel = document.getElementById('notesLabel');
            const deliveryNotes = document.getElementById('deliveryNotes');
            const paymentAmount = document.getElementById('paymentAmount');
            const paymentMethod = document.getElementById('paymentMethod');
            
            // Set payment amount to order total (readonly)
            if (paymentAmount && order.total_amount) {
                paymentAmount.value = parseFloat(order.total_amount).toFixed(2);
            }
            
            // Set payment method from order (visible readonly field)
            const paymentMethodHidden = document.getElementById('paymentMethodHidden');
            if (paymentMethod && order.payment_method) {
                // Format payment method for display
                const paymentMethodMap = {
                    'cod': 'Cash on Delivery',
                    'cash': 'Cash',
                    'gcash': 'GCash'
                };
                const paymentMethodText = paymentMethodMap[order.payment_method] || order.payment_method || 'N/A';
                paymentMethod.value = paymentMethodText;
                if (paymentMethodHidden) {
                    paymentMethodHidden.value = order.payment_method;
                }
            }
            
            // Handle delivery type - hide/show delivery address and payment proof photo
            const paymentProofPhotoField = document.getElementById('paymentProofPhotoField');
            const paymentPhotoInput = document.getElementById('paymentPhotoInput');
            
            if (order.delivery_type === 'pickup') {
                deliveryAddressField.style.display = 'none';
                deliveryAddress.required = false;
                // Set a placeholder value for pickup orders to satisfy backend validation
                if (!deliveryAddress.value) {
                    deliveryAddress.value = 'Pickup - No delivery address required';
                }
                notesLabel.textContent = 'Notes';
                deliveryNotes.placeholder = 'Any additional notes about the pickup';
                
                // Show payment proof photo field for pickup orders
                if (paymentProofPhotoField) {
                    paymentProofPhotoField.style.display = 'block';
                }
                if (paymentPhotoInput) {
                    paymentPhotoInput.required = true;
                }
            } else {
                deliveryAddressField.style.display = 'block';
                deliveryAddress.required = true;
                notesLabel.textContent = 'Delivery Notes';
                deliveryNotes.placeholder = 'Any additional notes about the delivery';
                
                // Hide payment proof photo field for delivery orders
                if (paymentProofPhotoField) {
                    paymentProofPhotoField.style.display = 'none';
                }
                if (paymentPhotoInput) {
                    paymentPhotoInput.required = false;
                    paymentPhotoInput.value = '';
                }
                
                // Auto-fill delivery address if available
                if (order.delivery_address && !deliveryAddress.value) {
                    deliveryAddress.value = order.delivery_address;
                } else if (deliveryAddress.value === 'Pickup - No delivery address required') {
                    deliveryAddress.value = '';
                }
            }
            
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Order Number:</strong> #${order.order_number || 'N/A'}</p>
                        <p><strong>Customer:</strong> ${order.customer_name || 'Customer Name Not Available'}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Amount:</strong> ₱${parseFloat(order.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Animal Being Delivered:</h6>
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <img src="/uploads/${items[0].animal_image}" class="animal-image me-3" alt="${items[0].animal_name}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            <div>
                                <h6 class="mb-1">${items[0].animal_name}</h6>
                                <p class="mb-0 text-muted">₱${parseFloat(items[0].price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                                <small class="text-success"><i class="fas fa-check-circle me-1"></i>Selected for delivery</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Show other animals if there are more than one
            if (items.length > 1) {
                html += `
                    <div class="mt-3">
                        <h6>Other animals in this order (${items.length - 1}):</h6>
                        <div class="row">
                `;
                items.slice(1).forEach(item => {
                    html += `
                        <div class="col-md-4">
                            <div class="animal-card">
                                <div class="d-flex align-items-center">
                                    <img src="/uploads/${item.animal_image}" class="animal-image me-3" alt="${item.animal_name}">
                                    <div>
                                        <h6 class="mb-1">${item.animal_name}</h6>
                                        <p class="mb-0 text-muted">₱${parseFloat(item.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += `</div></div>`;
            }
            
            orderInfo.innerHTML = html;
            orderDetails.style.display = 'block';
        }

        // Preview uploaded images
        if (document.querySelector('input[name="delivery_photo"]')) {
            document.querySelector('input[name="delivery_photo"]').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('deliveryPhotoPreview').innerHTML = 
                            `<img src="${e.target.result}" class="photo-preview" alt="Delivery photo preview">`;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (document.querySelector('input[name="payment_photo"]')) {
            document.querySelector('input[name="payment_photo"]').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('paymentPhotoPreview').innerHTML = 
                            `<img src="${e.target.result}" class="photo-preview" alt="Payment photo preview">`;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Load orders when delivery form is shown
        const newDeliveryForm = document.getElementById('newDeliveryForm');
        if (newDeliveryForm) {
            newDeliveryForm.addEventListener('shown.bs.collapse', function() {
                loadAvailableOrders();
            });
        }

        // Handle form submission
        if (document.getElementById('deliveryForm')) {
            document.getElementById('deliveryForm').addEventListener('submit', function(e) {
                // Form will submit normally, but we can add validation here if needed
            });
        }
    </script>
</body>
</html>