<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
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
        }

        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .stat-box h4 {
            margin: 0;
            font-size: 2rem;
        }

        .stat-box p {
            margin: 5px 0 0 0;
            opacity: 0.9;
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
                    <span>Manage Animals</span>
                </a>
                <a href="/staff/add-animal" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add New Animal</span>
                </a>
                <a href="/staff/delivery-confirmations" class="sidebar-item">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="/staff/reservations" class="sidebar-item">
                    <i class="fas fa-calendar-check"></i>
                    <span>Reservations</span>
                </a>
                <a href="/staff/orders" class="sidebar-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="/staff/sales-report" class="sidebar-item active">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Sales Report</span>
                </a>
                <a href="/staff/payments" class="sidebar-item">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="content-card">
                    <h3 class="mb-4">
                        <i class="fas fa-file-invoice-dollar"></i> Sales Report
                    </h3>

                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Period</label>
                            <select class="form-select" id="period">
                                <option value="daily">Today</option>
                                <option value="weekly" selected>This Week</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="startDateDiv" style="display: none;">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date">
                        </div>
                        <div class="col-md-3" id="endDateDiv" style="display: none;">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary d-block w-100" onclick="generateReport()">
                                <i class="fas fa-sync-alt"></i> Generate Report
                            </button>
                        </div>
                    </div>

                    <!-- Summary Stats -->
                    <div class="row mb-4" id="summarySection" style="display: none;">
                        <div class="col-md-4">
                            <div class="stat-box">
                                <h4 id="totalOrders">0</h4>
                                <p>Total Orders</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-box" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);">
                                <h4 id="totalSales">₱0</h4>
                                <p>Total Sales</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-box" style="background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);">
                                <h4 id="avgSale">₱0</h4>
                                <p>Average Sale</p>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Report -->
                    <div id="reportSection" style="display: none;">
                        <h5 class="mb-3">Detailed Breakdown</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Total Orders</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody id="reportTableBody"></tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-success" onclick="downloadReport()">
                                <i class="fas fa-download"></i> Download Report (CSV)
                            </button>
                            <button class="btn btn-secondary" onclick="printReport()">
                                <i class="fas fa-print"></i> Print Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentReportData = null;

        // Show/hide custom date fields
        document.getElementById('period').addEventListener('change', function() {
            const isCustom = this.value === 'custom';
            document.getElementById('startDateDiv').style.display = isCustom ? 'block' : 'none';
            document.getElementById('endDateDiv').style.display = isCustom ? 'block' : 'none';
        });

        // Load initial report (weekly)
        generateReport();

        function generateReport() {
            const period = document.getElementById('period').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            let url = '/staff/api/sales-report?period=' + period;
            if (period === 'custom') {
                if (!startDate || !endDate) {
                    alert('Please select both start and end dates');
                    return;
                }
                url += '&start_date=' + startDate + '&end_date=' + endDate;
            }

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        currentReportData = data;
                        displayReport(data);
                    } else {
                        alert('Failed to generate report');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Network error. Please try again.');
                });
        }

        function displayReport(data) {
            // Show summary
            document.getElementById('summarySection').style.display = 'flex';
            document.getElementById('totalOrders').textContent = data.summary.total_orders || 0;
            document.getElementById('totalSales').textContent = '₱' + parseFloat(data.summary.total_sales || 0).toLocaleString();
            document.getElementById('avgSale').textContent = '₱' + parseFloat(data.summary.avg_sale || 0).toLocaleString();

            // Show detailed report
            document.getElementById('reportSection').style.display = 'block';
            const tbody = document.getElementById('reportTableBody');
            
            if (data.reports.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No sales data for this period</td></tr>';
                return;
            }

            tbody.innerHTML = data.reports.map(report => `
                <tr>
                    <td>${new Date(report.date).toLocaleDateString()}</td>
                    <td>${report.total_orders}</td>
                    <td>₱${parseFloat(report.total_sales).toLocaleString()}</td>
                </tr>
            `).join('');
        }

        function downloadReport() {
            if (!currentReportData) return;

            let csv = 'Date,Total Orders,Total Sales\n';
            currentReportData.reports.forEach(report => {
                csv += `${report.date},${report.total_orders},${report.total_sales}\n`;
            });
            csv += `\nSummary\n`;
            csv += `Total Orders,${currentReportData.summary.total_orders}\n`;
            csv += `Total Sales,${currentReportData.summary.total_sales}\n`;
            csv += `Average Sale,${currentReportData.summary.avg_sale}\n`;

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'sales_report_' + new Date().toISOString().split('T')[0] + '.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function printReport() {
            window.print();
        }
    </script>
</body>
</html>

