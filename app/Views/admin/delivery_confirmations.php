<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Confirmations - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        .content-area {
            padding: 30px;
        }

        .page-card { 
            background:#fff; 
            border-radius:12px; 
            padding:20px; 
            box-shadow:0 2px 10px rgba(0,0,0,.05); 
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
                    <a href="/fluffy-admin/animals">
                        <i class="fas fa-paw"></i>
                        <span class="menu-text">Animals</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/categories">
                        <i class="fas fa-tags"></i>
                        <span class="menu-text">Categories</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/orders">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="menu-text">Orders</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/users">
                        <i class="fas fa-users"></i>
                        <span class="menu-text">Users</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/delivery-confirmations" class="active">
                        <i class="fas fa-truck"></i>
                        <span class="menu-text">Deliveries</span>
                    </a>
                </li>
                <li>
                    <a href="/">
                        <i class="fas fa-globe"></i>
                        <span class="menu-text">Visit Site</span>
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

            <!-- Content Area -->
            <div class="content-area">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">
                        <i class="fas fa-truck"></i> Delivery Confirmations
                    </h3>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="filterDeliveries('all')">All</button>
                        <button type="button" class="btn btn-outline-warning" onclick="filterDeliveries('pending')">Pending</button>
                        <button type="button" class="btn btn-outline-success" onclick="filterDeliveries('confirmed')">Confirmed</button>
                        <button type="button" class="btn btn-outline-danger" onclick="filterDeliveries('rejected')">Rejected</button>
                    </div>
                </div>

                <?php if (session()->getFlashdata('msg')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= session()->getFlashdata('msg') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="page-card">
                    <?php if (empty($deliveries)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No delivery confirmations yet</h5>
                            <p class="text-muted">Staff members will submit delivery confirmations here</p>
                        </div>
                    <?php else: ?>
                        <div class="row" id="deliveriesContainer">
                            <?php foreach ($deliveries as $delivery): ?>
                                <div class="col-md-6 mb-4 delivery-item" data-status="<?= $delivery['status'] ?>">
                                    <div class="delivery-card <?= $delivery['status'] ?>">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-1">Order #<?= $delivery['order_number'] ?? $delivery['order_id'] ?></h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?= 
                                                        !empty($delivery['created_at']) && $delivery['created_at'] !== '0000-00-00 00:00:00' 
                                                            ? date('M d, Y', strtotime($delivery['created_at'])) 
                                                            : date('M d, Y') 
                                                    ?>
                                                </small>
                                            </div>
                                            <span class="status-badge badge-<?= $delivery['status'] ?>">
                                                <?= ucfirst($delivery['status']) ?>
                                            </span>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Staff:</strong> <?= $delivery['staff_name'] ?? 'N/A' ?>
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Customer:</strong> <?= $delivery['customer_name'] ?? 'N/A' ?>
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Animal:</strong> <?= $delivery['animal_name'] ?? 'N/A' ?>
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Amount:</strong> ₱<?= number_format($delivery['payment_amount'], 2) ?>
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Method:</strong> <?= ucfirst($delivery['payment_method']) ?>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <?php if ($delivery['delivery_photo']): ?>
                                                    <img src="/uploads/deliveries/<?= $delivery['delivery_photo'] ?>" 
                                                         class="photo-thumbnail mb-2" 
                                                         alt="Delivery photo"
                                                         onclick="showImageModal(this.src, 'Delivery Photo')">
                                                <?php endif; ?>
                                                
                                                <?php if ($delivery['payment_photo']): ?>
                                                    <img src="/uploads/payments/<?= $delivery['payment_photo'] ?>" 
                                                         class="photo-thumbnail mb-2" 
                                                         alt="Payment photo"
                                                         onclick="showImageModal(this.src, 'Payment Proof')">
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php if ($delivery['delivery_notes']): ?>
                                            <div class="mt-3">
                                                <strong>Staff Notes:</strong>
                                                <p class="mb-0 text-muted"><?= $delivery['delivery_notes'] ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($delivery['admin_notes']): ?>
                                            <div class="mt-3">
                                                <strong>Admin Notes:</strong>
                                                <p class="mb-0 text-info"><?= $delivery['admin_notes'] ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                <?= $delivery['delivery_address'] ?>
                                            </small>
                                        </div>

                                        <?php if ($delivery['status'] === 'pending'): ?>
                                            <div class="mt-3">
                                                <form action="/fluffy-admin/delivery-confirmations/update-status" method="post" class="d-inline">
                                                    <input type="hidden" name="delivery_id" value="<?= $delivery['id'] ?>">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <textarea class="form-control" name="admin_notes" placeholder="Admin notes (optional)" rows="2"></textarea>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex gap-2">
                                                                <button type="submit" name="status" value="confirmed" class="btn btn-success btn-sm">
                                                                    <i class="fas fa-check"></i> Confirm
                                                                </button>
                                                                <button type="submit" name="status" value="rejected" class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-times"></i> Reject
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php endif; ?>
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
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        function showImageModal(src, title) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModalTitle').textContent = title;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        function filterDeliveries(status) {
            const items = document.querySelectorAll('.delivery-item');
            
            items.forEach(item => {
                if (status === 'all' || item.getAttribute('data-status') === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });

            // Update button states
            document.querySelectorAll('.btn-group button').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        // Profile dropdown functionality
        function toggleProfileDropdown() {
            const profileMenu = document.getElementById('profileMenu');
            profileMenu.classList.toggle('show');
        }

        // Close profile dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.querySelector('.profile-dropdown');
            const profileMenu = document.getElementById('profileMenu');
            
            if (!profileDropdown.contains(event.target)) {
                profileMenu.classList.remove('show');
            }
        });

        // Toggle notification dropdown
        function toggleNotifications() {
            // Implementation for notifications
        }
    </script>
</body>
</html>
