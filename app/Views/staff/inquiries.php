<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inquiries - Staff Dashboard</title>
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
            background: var(--sidebar-hover); color: var(--black);
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

        .badge-new { background: #17a2b8; }
        .badge-in_progress { background: #ffc107; color: #000; }
        .badge-resolved { background: #28a745; }
        .badge-closed { background: #6c757d; }
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
                <a href="/staff/inquiries" class="sidebar-item active">
                    <i class="fas fa-envelope"></i>
                    <span>Inquiries</span>
                </a>
                <a href="/staff/orders" class="sidebar-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Adoptions</span>
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
                <div class="content-card">
                    <h3 class="mb-4">
                        <i class="fas fa-envelope"></i> Customer Inquiries
                    </h3>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Contact</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Animal</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="inquiriesTableBody">
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

    <!-- Respond Modal -->
    <div class="modal fade" id="respondModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Respond to Inquiry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="respondForm">
                    <div class="modal-body">
                        <input type="hidden" id="inquiry_id">
                        
                        <div class="mb-3">
                            <label class="form-label"><strong>Customer Details</strong></label>
                            <div id="customerDetails"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><strong>Original Message</strong></label>
                            <div id="originalMessage" class="p-3 bg-light rounded"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Your Response <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="response" name="response" rows="5" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send Response</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        loadInquiries();

        function loadInquiries() {
            fetch('/staff/api/inquiries')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderInquiries(data.data);
                    } else {
                        document.getElementById('inquiriesTableBody').innerHTML = 
                            '<tr><td colspan="8" class="text-center text-danger">Failed to load inquiries</td></tr>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('inquiriesTableBody').innerHTML = 
                        '<tr><td colspan="8" class="text-center text-danger">Network error</td></tr>';
                });
        }

        function renderInquiries(inquiries) {
            const tbody = document.getElementById('inquiriesTableBody');
            
            if (inquiries.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center">No inquiries found</td></tr>';
                return;
            }

            tbody.innerHTML = inquiries.map(inquiry => `
                <tr>
                    <td>${inquiry.customer_name}</td>
                    <td>
                        <small>
                            <i class="fas fa-envelope"></i> ${inquiry.customer_email}<br>
                            ${inquiry.customer_phone ? '<i class="fas fa-phone"></i> ' + inquiry.customer_phone : ''}
                        </small>
                    </td>
                    <td>${inquiry.subject}</td>
                    <td>${inquiry.message.substring(0, 50)}...</td>
                    <td>${inquiry.animal_name || 'General'}</td>
                    <td><span class="badge badge-${inquiry.status}">${inquiry.status}</span></td>
                    <td>${new Date(inquiry.created_at).toLocaleDateString()}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick='respondToInquiry(${JSON.stringify(inquiry)})'>
                            <i class="fas fa-reply"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function respondToInquiry(inquiry) {
            document.getElementById('inquiry_id').value = inquiry.id;
            document.getElementById('customerDetails').innerHTML = `
                <strong>${inquiry.customer_name}</strong><br>
                Email: ${inquiry.customer_email}<br>
                ${inquiry.customer_phone ? 'Phone: ' + inquiry.customer_phone + '<br>' : ''}
                Subject: ${inquiry.subject}
            `;
            document.getElementById('originalMessage').innerText = inquiry.message;
            
            if (inquiry.staff_response) {
                document.getElementById('response').value = inquiry.staff_response;
            }
            
            new bootstrap.Modal(document.getElementById('respondModal')).show();
        }

        document.getElementById('respondForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('inquiry_id').value;
            const formData = new URLSearchParams();
            formData.append('response', document.getElementById('response').value);
            formData.append('status', document.getElementById('status').value);
            formData.append('_method', 'PUT');
            
            fetch('/staff/api/inquiries/' + id + '/respond', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('respondModal')).hide();
                    this.reset();
                    loadInquiries();
                    Swal.fire({icon: 'success', title: 'Success!', text: 'Response sent successfully!'});
                } else {
                    Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to send response'});
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

