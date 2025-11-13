<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Pets - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            background-color: var(--cream-bg);
        }

        .admin-wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: var(--sidebar-bg); color: #fff; position: fixed; height: 100vh; overflow-y: auto; z-index: 1000; transition: all .3s ease; }
        .sidebar-header { padding: 20px; border-bottom: 2px solid var(--primary-color); text-align: center; }
        .sidebar-brand { font-size: 1.5rem; font-weight: bold; color: var(--primary-color); text-decoration: none; }
        .sidebar-menu { padding: 0; margin: 0; list-style: none; }
        .sidebar-menu li { border-bottom: 1px solid var(--black); }
        .sidebar-menu a { display: flex; align-items: center; padding: 15px 20px; color: #ffffff; text-decoration: none; transition: all .3s ease; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: var(--sidebar-hover); color: var(--black);
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
                    <a href="/fluffy-admin/pending-animals" class="active">
                        <i class="fas fa-clock"></i>
                        <span class="menu-text">Pending Pets</span>
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
                    <a href="/fluffy-admin/sales-report">
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
                    <!-- Notification Icon -->
                    <div class="notification-icon" id="notificationIcon" onclick="toggleNotifications()">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </div>
                    
                    <!-- Profile Dropdown -->
                    <div class="profile-dropdown" style="display: inline-block;">
                        <div class="profile-trigger" onclick="toggleProfileDropdown()" style="display: flex; align-items: center; background: #ff6b35; color: white; padding: 10px 18px; border-radius: 25px; cursor: pointer; font-weight: 600; box-shadow: 0 3px 10px rgba(0,0,0,0.15);">
                            <i class="fas fa-user-shield me-2"></i>
                            <span>Admin</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </div>
                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <i class="fas fa-user-shield me-2"></i>
                                <span>Admin</span>
                                <small class="d-block text-muted">Administrator</small>
                            </div>
                            <div class="profile-divider"></div>
                            <a href="/profile" class="profile-item">
                                <i class="fas fa-user me-2"></i>
                                <span>Profile</span>
                            </a>
                            <a href="/settings" class="profile-item">
                                <i class="fas fa-cog me-2"></i>
                                <span>Settings</span>
                            </a>
                            <div class="profile-divider"></div>
                            <a href="/logout" class="profile-item logout-item">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Dropdown -->
            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-header">
                    <span><i class="fas fa-bell me-2"></i>Notifications</span>
                    <button class="btn btn-sm btn-light" onclick="markAllAsRead()">Mark all as read</button>
                </div>
                <div class="notification-body" id="notificationBody">
                    <div class="notification-empty">
                        <i class="fas fa-bell-slash fa-2x mb-3"></i>
                        <p>No notifications yet</p>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0"><i class="fas fa-clock me-2"></i>Pending Pets</h4>
                    <span class="badge bg-warning text-dark">Pending: <span id="pendingCount">0</span></span>
                </div>

                <!-- Filter Section -->
                <div class="page-card mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Animals</h6>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="statusFilter" id="all" value="all" checked>
                                <label class="btn btn-outline-primary" for="all">All</label>
                                <input type="radio" class="btn-check" name="statusFilter" id="pending" value="pending">
                                <label class="btn btn-outline-warning" for="pending">Pending</label>
                                <input type="radio" class="btn-check" name="statusFilter" id="approved" value="approved">
                                <label class="btn btn-outline-success" for="approved">Approved</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Pets List -->
                <div class="page-card">
                    <div id="pendingAnimalsList">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Loading pending animals...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Modal -->
    <div class="modal fade" id="approvalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i>Approve Animal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="approvalImage" src="" alt="Animal" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h6 id="approvalName"></h6>
                            <p class="text-muted mb-2">
                                <strong>Category:</strong> <span id="approvalCategory"></span><br>
                                <strong>Age:</strong> <span id="approvalAge"></span> months<br>
                                <strong>Gender:</strong> <span id="approvalGender"></span><br>
                                <strong>Price:</strong> ₱<span id="approvalPrice"></span><br>
                                <strong>Added by:</strong> <span id="approvalAddedBy"></span>
                            </p>
                            <p id="approvalDescription" class="text-muted"></p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="approvalNotes" class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" id="approvalNotes" rows="3" placeholder="Add any notes about this approval..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="approveAnimal()">
                        <i class="fas fa-check me-2"></i>Approve & Add to Store
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        function toggleProfileDropdown() {
            const profileMenu = document.getElementById('profileMenu');
            const notificationDropdown = document.getElementById('notificationDropdown');
            if (notificationDropdown.classList.contains('show')) {
                notificationDropdown.classList.remove('show');
            }
            profileMenu.classList.toggle('show');
        }

        document.addEventListener('click', function(event) {
            const profileDropdown = document.querySelector('.profile-dropdown');
            const profileMenu = document.getElementById('profileMenu');
            const notificationDropdown = document.getElementById('notificationDropdown');
            if (profileDropdown && !profileDropdown.contains(event.target)) {
                profileMenu.classList.remove('show');
            }
            if (!document.getElementById('notificationIcon').contains(event.target)) {
                notificationDropdown.classList.remove('show');
            }
        });

        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
            if (dropdown.classList.contains('show')) {
                loadNotifications();
            }
        }

        function loadNotifications() {
            fetch('/fluffy-admin/api/notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayNotifications(data.notifications);
                        updateNotificationBadge(data.unreadCount);
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        function displayNotifications(notifications) {
            const body = document.getElementById('notificationBody');
            if (!notifications || notifications.length === 0) {
                body.innerHTML = `
                    <div class="notification-empty">
                        <i class="fas fa-bell-slash fa-2x mb-3"></i>
                        <p>No notifications yet</p>
                    </div>
                `;
                return;
            }
            body.innerHTML = notifications.map(notif => `
                <div class="notification-item ${notif.is_read ? '' : 'unread'}" onclick="markAsRead(${notif.id}, ${notif.order_id})">
                    <div class="notification-title">${escapeHtml(notif.title)}</div>
                    <div class="notification-message">${escapeHtml(notif.message)}</div>
                    <div class="notification-time">
                        <i class="fas fa-clock me-1"></i>${timeAgo(notif.created_at)}
                    </div>
                </div>
            `).join('');
        }

        function updateNotificationBadge(count) {
            const badge = document.getElementById('notificationBadge');
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        function markAsRead(notificationId, orderId) {
            fetch(`/fluffy-admin/api/notifications/${notificationId}/read`, { method: 'PUT' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadNotifications();
                        if (orderId) { window.location.href = '/fluffy-admin/orders'; }
                    }
                })
                .catch(error => console.error('Error marking notification as read:', error));
        }

        function markAllAsRead() {
            fetch('/fluffy-admin/api/notifications/mark-all-read', { method: 'PUT' })
                .then(response => response.json())
                .then(data => { if (data.success) { loadNotifications(); } })
                .catch(error => console.error('Error marking all as read:', error));
        }

        function timeAgo(dateString) {
            const date = new Date(dateString);
            const seconds = Math.floor((new Date() - date) / 1000);
            const intervals = { year: 31536000, month: 2592000, week: 604800, day: 86400, hour: 3600, minute: 60 };
            for (let [name, seconds_in] of Object.entries(intervals)) {
                const interval = Math.floor(seconds / seconds_in);
                if (interval >= 1) { return interval === 1 ? `1 ${name} ago` : `${interval} ${name}s ago`; }
            }
            return 'Just now';
        }

        function escapeHtml(text) { const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }

        let currentAnimalId = null;
        let allPendingAnimals = [];

        // Load pending animals on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPendingAnimals();
            
            // Add filter event listeners
            document.querySelectorAll('input[name="statusFilter"]').forEach(radio => {
                radio.addEventListener('change', filterAnimals);
            });
        });

        // Helper function to safely format dates
        function formatDate(dateString) {
            if (!dateString || dateString === '0000-00-00 00:00:00' || dateString === '1970-01-01 00:00:00' || dateString === null || dateString === undefined) {
                return 'N/A';
            }
            try {
                const date = new Date(dateString);
                // Check if date is valid
                if (isNaN(date.getTime())) {
                    return 'N/A';
                }
                // Only return N/A if date is exactly 1970-01-01 00:00:00 (Unix epoch from null/invalid dates)
                // Allow other 1970 dates if they're valid
                if (date.getFullYear() === 1970 && date.getMonth() === 0 && date.getDate() === 1 && 
                    date.getHours() === 0 && date.getMinutes() === 0 && date.getSeconds() === 0) {
                    return 'N/A';
                }
                return date.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
            } catch (e) {
                return 'N/A';
            }
        }

        function loadPendingAnimals() {
            fetch('/fluffy-admin/api/pending-animals')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allPendingAnimals = data.data;
                        displayPendingAnimals(allPendingAnimals);
                        updatePendingCount();
                    } else {
                        showAlert('danger', 'Failed to load pending animals: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Error loading pending animals');
                });
        }

        function displayPendingAnimals(animals) {
            const container = document.getElementById('pendingAnimalsList');
            
            if (animals.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No pending animals found</h5>
                        <p class="text-muted">All animals have been reviewed.</p>
                    </div>
                `;
                return;
            }

            let html = '';
            animals.forEach(animal => {
                const statusClass = {
                    'pending': 'warning',
                    'approved': 'success'
                }[animal.status] || 'secondary';

                const statusIcon = {
                    'pending': 'clock',
                    'approved': 'check-circle'
                }[animal.status] || 'question';

                html += `
                    <div class="animal-card">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="/uploads/${animal.image}" alt="${animal.name}" class="animal-image" onerror="this.src='/web/default-pet.jpg'">
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-1">${animal.name}</h6>
                                <p class="text-muted mb-1">
                                    <strong>Category:</strong> ${animal.category_name || 'N/A'}<br>
                                    <strong>Age:</strong> ${animal.age} months | <strong>Gender:</strong> ${animal.gender}<br>
                                    <strong>Price:</strong> ₱${parseFloat(animal.price).toLocaleString('en-PH', {minimumFractionDigits: 2})}<br>
                                    <strong>Added by:</strong> ${animal.added_by_name} (${animal.added_by_email})
                                </p>
                                ${animal.description ? `<p class="text-muted small mb-0">${animal.description}</p>` : ''}
                                ${animal.admin_notes ? `<p class="text-info small mb-0"><strong>Admin Notes:</strong> ${animal.admin_notes}</p>` : ''}
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge status-badge bg-${statusClass}">
                                    <i class="fas fa-${statusIcon} me-1"></i>
                                    ${animal.status.charAt(0).toUpperCase() + animal.status.slice(1)}
                                </span>
                                <p class="small text-muted mt-2 mb-0">
                                    ${formatDate(animal.created_at)}
                                </p>
                            </div>
                            <div class="col-md-2 text-end">
                                ${animal.status === 'pending' ? `
                                    <button class="btn btn-success btn-action" onclick="showApprovalModal(${animal.id})">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                ` : `
                                    <p class="small text-muted mb-0">
                                        ${animal.approved_by_name ? `Reviewed by: ${animal.approved_by_name}` : ''}
                                        ${animal.approved_at ? `<br>On: ${formatDate(animal.approved_at)}` : ''}
                                    </p>
                                `}
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function filterAnimals() {
            const selectedFilter = document.querySelector('input[name="statusFilter"]:checked').value;
            let filteredAnimals = allPendingAnimals;

            if (selectedFilter !== 'all') {
                filteredAnimals = allPendingAnimals.filter(animal => animal.status === selectedFilter);
            }

            displayPendingAnimals(filteredAnimals);
        }

        function updatePendingCount() {
            const pendingCount = allPendingAnimals.filter(animal => animal.status === 'pending').length;
            document.getElementById('pendingCount').textContent = pendingCount;
        }

        function showApprovalModal(animalId) {
            const animal = allPendingAnimals.find(a => a.id == animalId);
            if (!animal) return;

            currentAnimalId = animalId;
            document.getElementById('approvalImage').src = `/uploads/${animal.image}`;
            document.getElementById('approvalName').textContent = animal.name;
            document.getElementById('approvalCategory').textContent = animal.category_name || 'N/A';
            document.getElementById('approvalAge').textContent = animal.age;
            document.getElementById('approvalGender').textContent = animal.gender;
            document.getElementById('approvalPrice').textContent = parseFloat(animal.price).toLocaleString('en-PH', {minimumFractionDigits: 2});
            document.getElementById('approvalAddedBy').textContent = `${animal.added_by_name} (${animal.added_by_email})`;
            document.getElementById('approvalDescription').textContent = animal.description || 'No description provided';
            document.getElementById('approvalNotes').value = '';

            new bootstrap.Modal(document.getElementById('approvalModal')).show();
        }


        function approveAnimal() {
            const adminNotes = document.getElementById('approvalNotes').value;

            fetch(`/fluffy-admin/api/pending-animals/${currentAnimalId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ admin_notes: adminNotes })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('approvalModal')).hide();
                    showAlert('success', data.message);
                    loadPendingAnimals(); // Reload the list
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while approving the animal');
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
