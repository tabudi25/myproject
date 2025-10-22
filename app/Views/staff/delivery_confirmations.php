<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Confirmations - Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
            --accent-color: #004e89;
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

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .delivery-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .delivery-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(255, 107, 53, 0.1);
        }

        .delivery-card.pending {
            border-left: 5px solid #ffc107;
        }

        .delivery-card.confirmed {
            border-left: 5px solid #28a745;
        }

        .delivery-card.rejected {
            border-left: 5px solid #dc3545;
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

        .photo-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .photo-thumbnail:hover {
            transform: scale(1.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.4);
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
                <a href="/logout" class="btn btn-outline-danger">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
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
                <a href="/staff/add-animal" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add New Animals</span>
                </a>
                <a href="/staff/animals" class="sidebar-item">
                    <i class="fas fa-paw"></i>
                    <span>Manage Animals</span>
                </a>
                <a href="/staff/orders" class="sidebar-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="/staff/delivery-confirmations" class="sidebar-item active">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
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
                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">
                            <i class="fas fa-truck"></i> My Delivery Confirmations
                        </h3>
                        <a href="/staff/delivery-confirmations/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>New Delivery
                        </a>
                    </div>

                    <?php if (session()->getFlashdata('msg')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= session()->getFlashdata('msg') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!isset($deliveries) || empty($deliveries)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No delivery confirmations yet</h5>
                            <p class="text-muted">Start by creating your first delivery confirmation</p>
                            <a href="/staff/delivery-confirmations/create" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create First Delivery
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Debug info (remove in production) -->
                        <div class="alert alert-info">
                            <small>Debug: Found <?= count($deliveries) ?> delivery confirmations</small>
                        </div>
                        <div class="row">
                            <?php foreach ($deliveries as $delivery): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="delivery-card <?= isset($delivery['status']) ? $delivery['status'] : 'pending' ?>">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-1">Order #<?= isset($delivery['order_id']) ? $delivery['order_id'] : 'N/A' ?></h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?= 
                                                        isset($delivery['created_at']) && !empty($delivery['created_at']) && $delivery['created_at'] !== '0000-00-00 00:00:00' 
                                                            ? date('M d, Y', strtotime($delivery['created_at'])) 
                                                            : date('M d, Y') 
                                                    ?>
                                                </small>
                                            </div>
                                            <span class="status-badge badge-<?= isset($delivery['status']) ? $delivery['status'] : 'pending' ?>">
                                                <?= isset($delivery['status']) ? ucfirst($delivery['status']) : 'Pending' ?>
                                            </span>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Customer:</strong> <?= $delivery['customer_name'] ?? 'N/A' ?>
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Animal:</strong> <?= $delivery['animal_name'] ?? 'N/A' ?>
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Amount:</strong> ₱<?= isset($delivery['payment_amount']) ? number_format($delivery['payment_amount'], 2) : '0.00' ?>
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Method:</strong> <?= isset($delivery['payment_method']) ? ucfirst($delivery['payment_method']) : 'N/A' ?>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <?php if (!empty($delivery['delivery_photo'])): ?>
                                                    <img src="/uploads/deliveries/<?= $delivery['delivery_photo'] ?>" 
                                                         class="photo-thumbnail mb-2" 
                                                         alt="Delivery photo"
                                                         onclick="showImageModal(this.src, 'Delivery Photo')">
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($delivery['payment_photo'])): ?>
                                                    <img src="/uploads/payments/<?= $delivery['payment_photo'] ?>" 
                                                         class="photo-thumbnail mb-2" 
                                                         alt="Payment photo"
                                                         onclick="showImageModal(this.src, 'Payment Proof')">
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php if (!empty($delivery['delivery_notes'])): ?>
                                            <div class="mt-3">
                                                <strong>Notes:</strong>
                                                <p class="mb-0 text-muted"><?= $delivery['delivery_notes'] ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($delivery['admin_notes'])): ?>
                                            <div class="mt-3">
                                                <strong>Admin Notes:</strong>
                                                <p class="mb-0 text-info"><?= $delivery['admin_notes'] ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                <?= isset($delivery['delivery_address']) ? $delivery['delivery_address'] : 'N/A' ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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
        function showImageModal(src, title) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModalTitle').textContent = title;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
    </script>
</body>
</html>
