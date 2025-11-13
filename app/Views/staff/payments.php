<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status - Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .btn-outline-danger {
            color: var(--dark-orange);
            border-color: var(--dark-orange);
        }

        .btn-outline-danger:hover {
            background-color: var(--dark-orange);
            border-color: var(--dark-orange);
            color: white;
        }

        .badge-pending { background: #ffc107; color: #000; }
        .badge-paid { background: #28a745; color: #fff; }
        .badge-failed { background: #dc3545; color: #fff; }
        .badge-refunded { background: #6c757d; color: #fff; }
        
        /* Order status badges */
        .badge-confirmed { background: #28a745; color: #fff; }
        .badge-processing { background: #17a2b8; color: #fff; }
        .badge-shipped { background: #6610f2; color: #fff; }
        .badge-delivered { background: #198754; color: #fff; }
        .badge-cancelled { background: #dc3545; color: #fff; }

        .alert-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .sidebar {
                min-height: auto;
            }

            .page-card {
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
                <a href="/staff/payments" class="sidebar-item active">
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
                    <h3 class="mb-4">
                        <i class="fas fa-credit-card"></i> Payment Status Tracking
                    </h3>

                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Update payment status after verifying customer payments. This notifies customers of their payment status.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Adoption ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="paymentsTableBody">
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Payment Modal -->
    <div class="modal fade" id="updatePaymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Payment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="updatePaymentForm">
                    <div class="modal-body">
                        <input type="hidden" id="payment_id">
                        
                        <div class="mb-3">
                            <label class="form-label"><strong>Order Details</strong></label>
                            <div id="orderDetails"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="failed">Failed</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <strong>Pending:</strong> Payment not yet received<br>
                                <strong>Paid:</strong> Payment confirmed and verified<br>
                                <strong>Failed:</strong> Payment attempt failed<br>
                                <strong>Refunded:</strong> Payment returned to customer
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Helper function to format dates properly
        function formatDate(dateString) {
            if (!dateString || dateString === '0000-00-00 00:00:00' || dateString === '1970-01-01 00:00:00') {
                return new Date().toLocaleDateString();
            }
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                return new Date().toLocaleDateString();
            }
            return date.toLocaleDateString();
        }

        loadPayments();

        function loadPayments() {
            fetch('/staff/api/payments')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderPayments(data.data);
                    } else {
                        document.getElementById('paymentsTableBody').innerHTML = 
                            '<tr><td colspan="8" class="text-center text-danger">Failed to load payments</td></tr>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('paymentsTableBody').innerHTML = 
                        '<tr><td colspan="8" class="text-center text-danger">Network error</td></tr>';
                });
        }

        function renderPayments(payments) {
            const tbody = document.getElementById('paymentsTableBody');
            
            if (payments.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center">No payments found</td></tr>';
                return;
            }

            tbody.innerHTML = payments.map(payment => `
                <tr>
                    <td><strong>#${payment.order_number}</strong></td>
                    <td>
                        ${payment.customer_name}<br>
                        <small class="text-muted">${payment.customer_email}</small>
                    </td>
                    <td>₱${parseFloat(payment.total_amount).toLocaleString()}</td>
                    <td>${payment.payment_method}</td>
                    <td><span class="badge badge-${payment.payment_status} text-white">${payment.payment_status}</span></td>
                    <td><span class="badge badge-${payment.status} text-white">${payment.status}</span></td>
                    <td>${formatDate(payment.created_at)}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick='updatePayment(${JSON.stringify(payment)})'>
                            <i class="fas fa-edit"></i> Update
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function updatePayment(payment) {
            document.getElementById('payment_id').value = payment.id;
            document.getElementById('orderDetails').innerHTML = `
                <strong>Order #${payment.order_number}</strong><br>
                Customer: ${payment.customer_name}<br>
                Amount: ₱${parseFloat(payment.total_amount).toLocaleString()}<br>
                Method: ${payment.payment_method}<br>
                Current Status: <span class="badge badge-${payment.payment_status}">${payment.payment_status}</span>
            `;
            document.getElementById('payment_status').value = payment.payment_status;
            
            new bootstrap.Modal(document.getElementById('updatePaymentModal')).show();
        }

        document.getElementById('updatePaymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('payment_id').value;
            const paymentStatus = document.getElementById('payment_status').value;
            
            if (!paymentStatus) {
                Swal.fire({icon: 'warning', title: 'Warning', text: 'Please select a payment status'});
                return;
            }
            
            const formData = new URLSearchParams();
            formData.append('payment_status', paymentStatus);
            formData.append('_method', 'PUT');
            
            fetch('/staff/api/payments/' + id + '/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('updatePaymentModal')).hide();
                    this.reset();
                    loadPayments();
                    Swal.fire({icon: 'success', title: 'Success!', text: 'Payment status updated successfully!'});
                } else {
                    Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to update payment status'});
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
            });
        });
    </script>
</body>
</html>

