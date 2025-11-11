<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
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
                    <a href="/fluffy-admin/sales-report" class="active">
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
                        <button type="button" class="btn btn-outline-primary active" id="btn-today" onclick="loadReport('today')">Today</button>
                        <button type="button" class="btn btn-outline-primary" id="btn-week" onclick="loadReport('week')">This Week</button>
                        <button type="button" class="btn btn-outline-primary" id="btn-month" onclick="loadReport('month')">This Month</button>
                        <button type="button" class="btn btn-outline-primary" id="btn-year" onclick="loadReport('year')">This Year</button>
                    </div>
                </div>

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
                            <p><i class="fas fa-dollar-sign me-2"></i>Total Sales</p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let salesChart, categoryChart;
        let currentPeriod = 'today';
        let deliveriesTable = null;

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
            fetch(`/fluffy-admin/api/sales-stats?period=${currentPeriod}`)
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
            fetch(`/fluffy-admin/api/sales-data?period=${currentPeriod}`)
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
                            borderColor: '#ff6b35',
                            backgroundColor: 'rgba(255, 107, 53, 0.1)',
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
                            borderColor: '#ff6b35',
                            backgroundColor: 'rgba(255, 107, 53, 0.1)',
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
            const categoryColors = chartData.categoryColors || ['#ff6b35', '#f7931e', '#2c3e50', '#28a745', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14'];
            
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
            const tbody = document.querySelector('#deliveriesTable tbody');
            if (!tbody) {
                console.error('Table body not found');
                return;
            }
            
            // Show loading state
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</td></tr>';
            
            fetch(`/fluffy-admin/api/completed-deliveries?period=${currentPeriod}`)
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
                        displayDeliveries(data.deliveries || []);
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
                        if ($.fn.dataTable.isDataTable('#deliveriesTable')) {
                            try {
                                $('#deliveriesTable').DataTable().destroy();
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
                    if ($.fn.dataTable.isDataTable('#deliveriesTable')) {
                        try {
                            $('#deliveriesTable').DataTable().destroy();
                        } catch(e) {
                            console.error('Error destroying DataTable from element:', e);
                        }
                    }
                });
        }

        function displayDeliveries(deliveries) {
            try {
                console.log('Displaying deliveries:', deliveries);
                const tbody = document.querySelector('#deliveriesTable tbody');
                if (!tbody) {
                    console.error('Table body not found in displayDeliveries');
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
                    if ($.fn.dataTable.isDataTable('#deliveriesTable')) {
                        try {
                            $('#deliveriesTable').DataTable().destroy();
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
                if ($.fn.dataTable.isDataTable('#deliveriesTable')) {
                    try {
                        $('#deliveriesTable').DataTable().destroy();
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
                        if ($('#deliveriesTable').length && !$.fn.dataTable.isDataTable('#deliveriesTable')) {
                            deliveriesTable = $('#deliveriesTable').DataTable({
                                pageLength: 10,
                                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                order: [[4, 'desc']],
                                language: {
                                    search: "Search:",
                                    lengthMenu: "Show _MENU_ entries",
                                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                                    infoEmpty: "Showing 0 to 0 of 0 entries",
                                    infoFiltered: "(filtered from _MAX_ total entries)"
                                }
                            });
                        } else if ($.fn.dataTable.isDataTable('#deliveriesTable')) {
                            // If already initialized, just get the instance
                            deliveriesTable = $('#deliveriesTable').DataTable();
                        }
                    } catch(e) {
                        console.error('Error initializing DataTable:', e);
                    }
                }, 100);
            } catch(error) {
                console.error('Error in displayDeliveries:', error);
                const tbody = document.querySelector('#deliveriesTable tbody');
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
                if ($.fn.dataTable.isDataTable('#deliveriesTable')) {
                    try {
                        $('#deliveriesTable').DataTable().destroy();
                    } catch(e) {
                        console.error('Error destroying DataTable from element:', e);
                    }
                }
            }
        }

        // Load initial data
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
        });
    </script>
</body>
</html>
