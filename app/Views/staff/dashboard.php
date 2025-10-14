<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Fluffy Planet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
            --accent-color: #004e89;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
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

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }

        .stat-icon.orange { background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); }
        .stat-icon.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-icon.green { background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); }
        .stat-icon.purple { background: linear-gradient(135deg, #a044ff 0%, #6a3093 100%); }
        .stat-icon.red { background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); }
        .stat-icon.yellow { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-confirmed { background: #d4edda; color: #155724; }
        .badge-completed { background: #d1ecf1; color: #0c5460; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }
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
                    <?= esc(session()->get('name')) ?> (Staff)
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
                <a href="/staff-dashboard" class="sidebar-item active">
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
                <a href="/staff/inquiries" class="sidebar-item">
                    <i class="fas fa-envelope"></i>
                    <span>Inquiries</span>
                </a>
                <a href="/staff/reservations" class="sidebar-item">
                    <i class="fas fa-calendar-check"></i>
                    <span>Reservations</span>
                </a>
                <a href="/staff/orders" class="sidebar-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="/staff/sales-report" class="sidebar-item">
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
                <h2 class="mb-4" style="color: white;">
                    <i class="fas fa-chart-line"></i> Staff Dashboard
                </h2>

                <?php if (session()->getFlashdata('msg')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= session()->getFlashdata('msg') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics Grid -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon orange">
                                <i class="fas fa-paw"></i>
                            </div>
                            <div class="stat-value"><?= $stats['available_animals'] ?></div>
                            <div class="stat-label">Available Animals</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-value"><?= $stats['pending_orders'] ?></div>
                            <div class="stat-label">Pending Orders</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="stat-value"><?= $stats['total_inquiries'] ?></div>
                            <div class="stat-label">Active Inquiries</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon purple">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-value"><?= $stats['pending_reservations'] ?></div>
                            <div class="stat-label">Pending Reservations</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon yellow">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-value"><?= $stats['pending_animals'] ?></div>
                            <div class="stat-label">Awaiting Admin Approval</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon red">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="stat-value"><?= $stats['total_animals'] ?></div>
                            <div class="stat-label">Total Animals</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="stat-card">
                            <h5 class="mb-3">
                                <i class="fas fa-bolt"></i> Quick Actions
                            </h5>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="/staff/add-animal" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add New Animal
                                </a>
                                <a href="/staff/orders" class="btn btn-success">
                                    <i class="fas fa-check"></i> Confirm Orders
                                </a>
                                <a href="/staff/inquiries" class="btn btn-info text-white">
                                    <i class="fas fa-reply"></i> Respond to Inquiries
                                </a>
                                <a href="/staff/sales-report" class="btn btn-warning">
                                    <i class="fas fa-file-download"></i> Generate Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Highlight active sidebar item
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-item').forEach(item => {
            if (item.getAttribute('href') === currentPath) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    </script>
</body>
</html>

