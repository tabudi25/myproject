<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .admin-wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: var(--sidebar-bg); color: #fff; position: fixed; height: 100vh; overflow-y: auto; z-index: 1000; transition: all .3s ease; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #495057; text-align: center; }
        .sidebar-brand { font-size: 1.5rem; font-weight: bold; color: var(--primary-color); text-decoration: none; }
        .sidebar-menu { padding: 0; margin: 0; list-style: none; }
        .sidebar-menu li { border-bottom: 1px solid #495057; }
        .sidebar-menu a { display: flex; align-items: center; padding: 15px 20px; color: #adb5bd; text-decoration: none; transition: all .3s ease; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: var(--sidebar-hover); color: #fff; }
        .sidebar-menu i { width: 20px; margin-right: 15px; text-align: center; }

        .main-content { flex: 1; margin-left: 280px; transition: all .3s ease; }
        .top-navbar { background: #fff; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,.1); display: flex; justify-content: between; align-items: center; }
        .sidebar-toggle { background: none; border: none; font-size: 1.2rem; color: var(--accent-color); cursor: pointer; }
        .admin-user { display: flex; align-items: center; margin-left: auto; gap: 20px; z-index: 100; }

        .content-area { padding: 30px; }
        .page-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,.05); margin-bottom: 20px; }
        .stat-card { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .stat-card h3 { font-size: 2rem; font-weight: bold; margin: 0; }
        .stat-card p { margin: 5px 0 0 0; opacity: 0.9; }
        .chart-container { position: relative; height: 400px; margin: 20px 0; }
        .table-responsive { border-radius: 8px; overflow: hidden; }
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
                    <a href="/fluffy-admin/animals">
                        <i class="fas fa-paw"></i>
                        <span class="menu-text">Animals</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/pending-animals">
                        <i class="fas fa-clock"></i>
                        <span class="menu-text">Pending Animal</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/orders">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="menu-text">Orders</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/payments">
                        <i class="fas fa-credit-card"></i>
                        <span class="menu-text">Payments</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/sales-report" class="active">
                        <i class="fas fa-chart-line"></i>
                        <span class="menu-text">Sales Report</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/delivery-confirmations">
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
                    <div class="profile-trigger" onclick="toggleProfileDropdown()" style="display: flex; align-items: center; background: #ff6b35; color: white; padding: 10px 18px; border-radius: 25px; cursor: pointer; font-weight: 600; box-shadow: 0 3px 10px rgba(0,0,0,0.15);">
                        <i class="fas fa-user-shield me-2"></i>
                        <span>Admin</span>
                        <i class="fas fa-chevron-down ms-2"></i>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="fas fa-chart-line me-2"></i>Sales Report</h2>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="loadReport('today')">Today</button>
                        <button type="button" class="btn btn-outline-primary" onclick="loadReport('week')">This Week</button>
                        <button type="button" class="btn btn-outline-primary" onclick="loadReport('month')">This Month</button>
                        <button type="button" class="btn btn-outline-primary" onclick="loadReport('year')">This Year</button>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 id="totalAdoptions">0</h3>
                            <p><i class="fas fa-paw me-2"></i>Total Adoptions</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 id="totalSales">₱0</h3>
                            <p><i class="fas fa-dollar-sign me-2"></i>Total Sales</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 id="averageSales">₱0</h3>
                            <p><i class="fas fa-chart-bar me-2"></i>Average Sale</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h3 id="totalPayments">₱0</h3>
                            <p><i class="fas fa-credit-card me-2"></i>Total Payments</p>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="page-card">
                            <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i>Sales Trend</h5>
                            <div class="chart-container">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="page-card">
                            <h5 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Sales by Category</h5>
                            <div class="chart-container">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Deliveries Table -->
                <div class="page-card">
                    <h5 class="mb-3"><i class="fas fa-truck me-2"></i>Completed Deliveries</h5>
                    <div class="table-responsive">
                        <table class="table align-middle" id="deliveriesTable">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Animal</th>
                                    <th>Amount</th>
                                    <th>Delivery Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="6" class="text-center py-4">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let salesChart, categoryChart;
        let currentPeriod = 'month';

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        function toggleProfileDropdown() {
            // Profile dropdown functionality
        }

        function loadReport(period) {
            currentPeriod = period;
            updateActiveButton(period);
            loadSalesData();
            loadDeliveries();
        }

        function updateActiveButton(period) {
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        function loadSalesData() {
            fetch(`/fluffy-admin/api/sales-data?period=${currentPeriod}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateStatistics(data.stats);
                        updateCharts(data.chartData);
                    }
                })
                .catch(error => {
                    console.error('Error loading sales data:', error);
                });
        }

        function updateStatistics(stats) {
            document.getElementById('totalAdoptions').textContent = stats.totalAdoptions || 0;
            document.getElementById('totalSales').textContent = '₱' + (stats.totalSales || 0).toLocaleString();
            document.getElementById('averageSales').textContent = '₱' + (stats.averageSales || 0).toLocaleString();
            document.getElementById('totalPayments').textContent = '₱' + (stats.totalPayments || 0).toLocaleString();
        }

        function updateCharts(chartData) {
            // Sales Trend Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            if (salesChart) salesChart.destroy();
            
            salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: chartData.salesTrend.labels,
                    datasets: [{
                        label: 'Sales',
                        data: chartData.salesTrend.data,
                        borderColor: '#ff6b35',
                        backgroundColor: 'rgba(255, 107, 53, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            if (categoryChart) categoryChart.destroy();
            
            categoryChart = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: chartData.categorySales.labels,
                    datasets: [{
                        data: chartData.categorySales.data,
                        backgroundColor: [
                            '#ff6b35',
                            '#f7931e',
                            '#2c3e50',
                            '#28a745',
                            '#17a2b8',
                            '#6f42c1'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function loadDeliveries() {
            fetch(`/fluffy-admin/api/completed-deliveries?period=${currentPeriod}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayDeliveries(data.deliveries);
                    }
                })
                .catch(error => {
                    console.error('Error loading deliveries:', error);
                });
        }

        function displayDeliveries(deliveries) {
            const tbody = document.querySelector('#deliveriesTable tbody');
            if (!deliveries || deliveries.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No completed deliveries found</td></tr>';
                return;
            }

            tbody.innerHTML = deliveries.map(delivery => `
                <tr>
                    <td>${delivery.order_number || delivery.id}</td>
                    <td>${delivery.customer_name}</td>
                    <td>${delivery.animal_name}</td>
                    <td>₱${parseFloat(delivery.amount).toLocaleString()}</td>
                    <td>${new Date(delivery.delivery_date).toLocaleDateString()}</td>
                    <td><span class="badge bg-success">Completed</span></td>
                </tr>
            `).join('');
        }

        // Load initial data
        document.addEventListener('DOMContentLoaded', function() {
            loadReport('month');
        });
    </script>
</body>
</html>
