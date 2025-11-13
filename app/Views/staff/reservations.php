<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations - Staff Dashboard</title>
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
                    <span>Pets</span>
                </a>
                <a href="/staff/reservations" class="sidebar-item active">
                    <i class="fas fa-calendar-check"></i>
                    <span>Reservations</span>
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
            Swal.fire({
                title: 'Confirm Reservation?',
                text: 'Are you sure you want to confirm this reservation?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, confirm it!'
            }).then((result) => {
                if (!result.isConfirmed) return;
            
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
                    Swal.fire({icon: 'success', title: 'Success!', text: 'Reservation confirmed!'});
                } else {
                    Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to confirm reservation'});
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
            });
        });
        }

        function cancelReservation(id) {
            Swal.fire({
                title: 'Cancel Reservation?',
                text: 'Are you sure you want to cancel this reservation?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (!result.isConfirmed) return;
            
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
                    Swal.fire({icon: 'success', title: 'Success!', text: 'Reservation cancelled!'});
                } else {
                    Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to cancel reservation'});
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
            });
        });
        }
    </script>
</body>
</html>

