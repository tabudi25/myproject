<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations - Staff Dashboard</title>
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

        .table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }

        .badge-pending { background: #ffc107; color: #000; }
        .badge-confirmed { background: #28a745; }
        .badge-completed { background: #17a2b8; }
        .badge-cancelled { background: #dc3545; }
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
                <a href="/staff/inquiries" class="sidebar-item">
                    <i class="fas fa-envelope"></i>
                    <span>Inquiries</span>
                </a>
                <a href="/staff/reservations" class="sidebar-item active">
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
                <div class="content-card">
                    <h3 class="mb-4">
                        <i class="fas fa-calendar-check"></i> Pet Viewing Reservations
                    </h3>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Animal</th>
                                    <th>Customer</th>
                                    <th>Contact</th>
                                    <th>Visit Date</th>
                                    <th>Visit Time</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="reservationsTableBody">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        loadReservations();

        function loadReservations() {
            fetch('/staff/api/reservations')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderReservations(data.data);
                    } else {
                        document.getElementById('reservationsTableBody').innerHTML = 
                            '<tr><td colspan="8" class="text-center text-danger">Failed to load reservations</td></tr>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('reservationsTableBody').innerHTML = 
                        '<tr><td colspan="8" class="text-center text-danger">Network error</td></tr>';
                });
        }

        function renderReservations(reservations) {
            const tbody = document.getElementById('reservationsTableBody');
            
            if (reservations.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center">No reservations found</td></tr>';
                return;
            }

            tbody.innerHTML = reservations.map(reservation => `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="/uploads/${reservation.image || 'default.png'}" alt="${reservation.animal_name}">
                            <span class="ms-2">${reservation.animal_name}</span>
                        </div>
                    </td>
                    <td>${reservation.customer_name}</td>
                    <td><small>${reservation.customer_email}</small></td>
                    <td>${reservation.visit_date ? new Date(reservation.visit_date).toLocaleDateString() : 'N/A'}</td>
                    <td>${reservation.visit_time || 'N/A'}</td>
                    <td><span class="badge badge-${reservation.status}">${reservation.status}</span></td>
                    <td>${reservation.notes || '-'}</td>
                    <td>
                        ${reservation.status === 'pending' ? `
                            <button class="btn btn-sm btn-success me-1" onclick="confirmReservation(${reservation.id})">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="cancelReservation(${reservation.id})">
                                <i class="fas fa-times"></i>
                            </button>
                        ` : '-'}
                    </td>
                </tr>
            `).join('');
        }

        function confirmReservation(id) {
            if (!confirm('Confirm this reservation?')) return;
            
            const formData = new URLSearchParams();
            formData.append('_method', 'PUT');
            
            fetch('/staff/api/reservations/' + id + '/confirm', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    loadReservations();
                    alert('Reservation confirmed!');
                } else {
                    alert(res.message || 'Failed to confirm reservation');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Network error. Please try again.');
            });
        }

        function cancelReservation(id) {
            if (!confirm('Cancel this reservation?')) return;
            
            const formData = new URLSearchParams();
            formData.append('_method', 'PUT');
            
            fetch('/staff/api/reservations/' + id + '/cancel', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    loadReservations();
                    alert('Reservation cancelled!');
                } else {
                    alert(res.message || 'Failed to cancel reservation');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Network error. Please try again.');
            });
        }
    </script>
</body>
</html>

