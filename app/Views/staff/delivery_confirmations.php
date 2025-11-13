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

        .sidebar-item:hover, .sidebar-item.active {
            background: var(--sidebar-hover); color: var(--black);
        }

        .btn-primary:hover {
            background-color: var(--dark-orange);
            border-color: var(--dark-orange);
            color: white;
        }

        .btn-secondary {
            background-color: var(--dark-orange); border-color: var(--dark-orange); color: white; transform: translateY(-2px);
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
                            <small>Found <?= count($deliveries) ?> delivery confirmations</small>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Pets</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Photos</th>
                                        <th>Address</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($deliveries as $delivery): ?>
                                        <tr class="delivery-row <?= isset($delivery['status']) ? $delivery['status'] : 'pending' ?>">
                                            <td>
                                                <strong>#<?= isset($delivery['order_id']) ? $delivery['order_id'] : 'N/A' ?></strong>
                                            </td>
                                            <td><?= $delivery['customer_name'] ?? 'N/A' ?></td>
                                            <td><?= $delivery['animal_name'] ?? 'N/A' ?></td>
                                            <td>₱<?= isset($delivery['payment_amount']) ? number_format($delivery['payment_amount'], 2) : '0.00' ?></td>
                                            <td><?= isset($delivery['payment_method']) ? ucfirst($delivery['payment_method']) : 'N/A' ?></td>
                                            <td>
                                                <span class="status-badge badge-<?= isset($delivery['status']) ? $delivery['status'] : 'pending' ?>">
                                                    <?= isset($delivery['status']) ? ucfirst($delivery['status']) : 'Pending' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?= 
                                                        isset($delivery['created_at']) && !empty($delivery['created_at']) && $delivery['created_at'] !== '0000-00-00 00:00:00' 
                                                            ? date('M d, Y', strtotime($delivery['created_at'])) 
                                                            : date('M d, Y') 
                                                    ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <?php if (!empty($delivery['delivery_photo'])): ?>
                                                        <img src="/uploads/deliveries/<?= $delivery['delivery_photo'] ?>" 
                                                             class="photo-thumbnail" 
                                                             alt="Delivery photo"
                                                             title="Delivery Photo"
                                                             onclick="showImageModal(this.src, 'Delivery Photo')">
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($delivery['payment_photo'])): ?>
                                                        <img src="/uploads/payments/<?= $delivery['payment_photo'] ?>" 
                                                             class="photo-thumbnail" 
                                                             alt="Payment photo"
                                                             title="Payment Proof"
                                                             onclick="showImageModal(this.src, 'Payment Proof')">
                                                    <?php endif; ?>
                                                    
                                                    <?php if (empty($delivery['delivery_photo']) && empty($delivery['payment_photo'])): ?>
                                                        <span class="text-muted">No photos</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    <?= isset($delivery['delivery_address']) ? $delivery['delivery_address'] : 'N/A' ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="notes-cell">
                                                    <?php if (!empty($delivery['delivery_notes'])): ?>
                                                        <div class="mb-1">
                                                            <strong>Delivery:</strong>
                                                            <small class="text-muted d-block"><?= $delivery['delivery_notes'] ?></small>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($delivery['admin_notes'])): ?>
                                                        <div>
                                                            <strong>Admin:</strong>
                                                            <small class="text-info d-block"><?= $delivery['admin_notes'] ?></small>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (empty($delivery['delivery_notes']) && empty($delivery['admin_notes'])): ?>
                                                        <span class="text-muted">No notes</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
