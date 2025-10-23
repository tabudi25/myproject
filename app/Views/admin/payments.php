<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management - Admin</title>
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
        .page-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,.05); }
        .table-responsive { border-radius: 8px; overflow: hidden; }
        .status-badge { font-size: .8rem; padding: .4rem .8rem; border-radius: 20px; }
        .btn-action { margin: .2rem; border-radius: 20px; padding: .4rem .8rem; }
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
                    <a href="/fluffy-admin/payments" class="active">
                        <i class="fas fa-credit-card"></i>
                        <span class="menu-text">Payments</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/sales-report">
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">List of Payments</h4>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="statusFilter" id="all" value="all" checked>
                        <label class="btn btn-outline-primary" for="all">All</label>
                        <input type="radio" class="btn-check" name="statusFilter" id="pending" value="pending">
                        <label class="btn btn-outline-warning" for="pending">Pending</label>
                        <input type="radio" class="btn-check" name="statusFilter" id="paid" value="paid">
                        <label class="btn btn-outline-success" for="paid">Paid</label>
                    </div>
                </div>

                <div class="page-card">
                    <div class="table-responsive">
                        <table class="table align-middle" id="paymentsTable">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th style="width:160px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="7" class="text-center py-4">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let allPayments = [];

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        function toggleProfileDropdown() {
            // Profile dropdown functionality
        }

        // Load payments on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPayments();
            
            // Add filter event listeners
            document.querySelectorAll('input[name="statusFilter"]').forEach(radio => {
                radio.addEventListener('change', filterPayments);
            });
        });

        function loadPayments() {
            fetch('/fluffy-admin/api/payments')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allPayments = data.data;
                        displayPayments(allPayments);
                    } else {
                        showAlert('danger', 'Failed to load payments: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Error loading payments');
                });
        }

        function displayPayments(payments) {
            const tbody = document.querySelector('#paymentsTable tbody');
            
            if (payments.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No payments found</td></tr>';
                return;
            }

            tbody.innerHTML = payments.map(payment => {
                const statusClass = payment.payment_status === 'paid' ? 'success' : 'warning';
                const statusIcon = payment.payment_status === 'paid' ? 'check-circle' : 'clock';
                
                return `
                    <tr>
                        <td><strong>${payment.order_number || payment.id}</strong></td>
                        <td>
                            <div>
                                <strong>${payment.customer_name}</strong><br>
                                <small class="text-muted">${payment.customer_email}</small>
                            </div>
                        </td>
                        <td><strong>₱${parseFloat(payment.total_amount).toLocaleString('en-PH', {minimumFractionDigits: 2})}</strong></td>
                        <td>
                            <span class="badge bg-info">${payment.payment_method || 'N/A'}</span>
                        </td>
                        <td>
                            <span class="badge status-badge bg-${statusClass}">
                                <i class="fas fa-${statusIcon} me-1"></i>
                                ${payment.payment_status.charAt(0).toUpperCase() + payment.payment_status.slice(1)}
                            </span>
                        </td>
                        <td>${new Date(payment.created_at).toLocaleDateString()}</td>
                        <td>
                            ${payment.payment_status === 'pending' ? `
                                <button class="btn btn-success btn-sm btn-action" onclick="updatePaymentStatus(${payment.id}, 'paid')">
                                    <i class="fas fa-check"></i> Mark Paid
                                </button>
                            ` : `
                                <button class="btn btn-warning btn-sm btn-action" onclick="updatePaymentStatus(${payment.id}, 'pending')">
                                    <i class="fas fa-undo"></i> Mark Pending
                                </button>
                            `}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function filterPayments() {
            const selectedFilter = document.querySelector('input[name="statusFilter"]:checked').value;
            let filteredPayments = allPayments;

            if (selectedFilter !== 'all') {
                filteredPayments = allPayments.filter(payment => payment.payment_status === selectedFilter);
            }

            displayPayments(filteredPayments);
        }

        function updatePaymentStatus(paymentId, newStatus) {
            const action = newStatus === 'paid' ? 'mark as paid' : 'mark as pending';
            
            if (!confirm(`Are you sure you want to ${action} this payment?`)) {
                return;
            }

            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            button.disabled = true;

            fetch(`/fluffy-admin/api/payments/${paymentId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `status=${newStatus}`
            })
            .then(r => {
                if (!r.ok) {
                    throw new Error(`HTTP error! status: ${r.status}`);
                }
                return r.json();
            })
            .then(res => {
                if (res.success) {
                    loadPayments(); // Reload the table
                    showAlert('success', `Payment ${action} successfully!`);
                } else {
                    showAlert('danger', res.message || 'Failed to update payment status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Network error: ' + error.message);
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
    </script>
</body>
</html>
