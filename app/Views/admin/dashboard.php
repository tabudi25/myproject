<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet">
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
                    <a href="/">
                        <i class="fas fa-globe"></i>
                        <span class="menu-text">Visit Site</span>
                    </a>
                </li>
                <li>
                    <a href="/logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="menu-text">Logout</span>
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
                    <i class="fas fa-user-shield me-2"></i>
                    <span>Welcome, <?= esc($userName) ?></span>
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
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-paw"></i>
                        </div>
                        <div class="stat-number"><?= $stats['total_animals'] ?></div>
                        <div class="stat-label">Total Animals</div>
                        <small class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            <?= $stats['available_animals'] ?> Available
                        </small>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-number"><?= $stats['total_orders'] ?></div>
                        <div class="stat-label">Total Orders</div>
                        <small class="text-warning">
                            <i class="fas fa-clock me-1"></i>
                            <?= $stats['pending_orders'] ?> Pending
                        </small>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number"><?= $stats['total_users'] ?></div>
                        <div class="stat-label">Total Users</div>
                        <small class="text-info">
                            <i class="fas fa-user me-1"></i>
                            <?= $stats['customer_users'] ?> Customers
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
                </div>

                <!-- Sales Graph -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="dashboard-section">
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

                <div class="row">
                    <!-- Recent Orders -->
                    <div class="col-lg-8">
                        <div class="dashboard-section">
                            <h4 class="section-title">
                                <i class="fas fa-shopping-cart"></i>
                                Recent Orders
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
                                                    <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
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
                    
                    <!-- Recent Animals -->
                    <div class="col-lg-4">
                        <div class="dashboard-section">
                            <h4 class="section-title">
                                <i class="fas fa-paw"></i>
                                Recent Animals
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
                                <p class="text-muted">No recent animals found.</p>
                            <?php endif; ?>
                            
                            <div class="text-center mt-3">
                                <button class="btn btn-primary" onclick="loadSection('animals')">
                                    View All Animals
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
                            Manage Animals
                        </h4>
                        <button class="btn btn-primary" onclick="showAddAnimalModal()">
                            <i class="fas fa-plus me-2"></i>Add Animal
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
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteAnimal(${animal.id})">
                                <i class="fas fa-trash"></i>
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
                                <h5 class="modal-title">Add New Animal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="addAnimalForm" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" required>
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
                                                <input type="number" class="form-control" name="age" required>
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
                                        <input type="number" step="0.01" class="form-control" name="price" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Image</label>
                                        <input type="file" class="form-control" name="image" accept="image/*" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Add Animal</button>
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
                
                fetch('/admin/animals', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modal.hide();
                        showAlert('success', data.message);
                        loadAnimalsSection(); // Reload animals table
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'An error occurred while adding the animal');
                });
            });
        }

        function loadCategoriesForDropdown() {
            fetch('/admin/categories')
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
            showAlert('info', `Edit animal ${id} coming soon...`);
        }

        function deleteAnimal(id) {
            if (confirm('Are you sure you want to delete this animal?')) {
                fetch(`/admin/animals/${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        loadAnimalsSection(); // Reload animals table
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'An error occurred while deleting the animal');
                });
            }
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

        // Auto-refresh dashboard data every 30 seconds
        setInterval(() => {
            // In a real implementation, you would refresh the dashboard data here
            console.log('Refreshing dashboard data...');
        }, 30000);

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
    </script>
</body>
</html>
