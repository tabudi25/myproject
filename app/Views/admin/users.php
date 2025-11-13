<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
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

    .admin-wrapper {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 280px;
        background: var(--sidebar-bg);
        color: white;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .sidebar-header {
        padding: 20px;
        border-bottom: 2px solid var(--primary-color);
        text-align: center;
    }

    .sidebar-brand {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-color);
        text-decoration: none;
    }

    .sidebar-menu {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .sidebar-menu li {
        border-bottom: 1px solid var(--light-black);
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        color: #ffffff;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
        background: var(--sidebar-hover);
        color: var(--black);
        font-weight: 600;
    }

    .sidebar-menu i {
        width: 20px;
        margin-right: 15px;
        text-align: center;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .notification-dropdown {
        position: absolute;
        top: 60px;
        right: 30px;
        width: 400px;
        max-height: 500px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.15);
        display: none;
        z-index: 1000;
        overflow: hidden;
    }

    .notification-dropdown.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notification-header {
        padding: 15px 20px;
        background: var(--primary-color);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-body {
        max-height: 400px;
        overflow-y: auto;
    }

    .notification-item {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
        transition: background 0.2s;
        position: relative;
    }

    .notification-item:hover {
        background: #f8f9fa;
    }

    .notification-item.unread {
        background: #fff3e0;
        border-left: 4px solid var(--primary-color);
    }

    .notification-title {
        font-weight: 600;
        color: var(--black);
        margin-bottom: 5px;
    }

    .notification-message {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .notification-time {
        font-size: 0.75rem;
        color: #adb5bd;
    }

    .notification-empty {
        padding: 40px 20px;
        text-align: center;
        color: #6c757d;
    }

    .profile-dropdown {
        position: relative;
        display: inline-block;
        z-index: 1000;
    }

    .profile-trigger {
        display: flex;
        align-items: center;
        padding: 10px 18px;
        background: var(--primary-color);
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        color: white;
        font-weight: 600;
        border: 2px solid var(--primary-color);
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        min-width: 120px;
        justify-content: center;
    }

    .notification-item.unread::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        background: var(--primary-color);
        border-radius: 50%;
    }

    .profile-trigger:hover {
        background: var(--secondary-color);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .profile-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        min-width: 220px;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .profile-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .profile-header {
        padding: 16px 20px;
        background: var(--primary-color);
        color: white;
        border-radius: 12px 12px 0 0;
    }

    .profile-header small {
        color: rgba(255, 255, 255, 0.8);
    }

    .profile-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }

    .profile-item:hover {
        background: #f8f9fa;
        color: var(--primary-color);
    }

    .profile-item.logout-item {
        color: #dc3545;
    }

    .profile-item.logout-item:hover {
        background: #f8d7da;
        color: #721c24;
    }

    .profile-divider {
        height: 1px;
        background: #e9ecef;
        margin: 8px 0;
    }

    .mark-all-read {
        padding: 10px 20px;
        text-align: center;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        cursor: pointer;
        color: var(--primary-color);
        font-weight: 500;
        transition: background 0.2s;
    }

    .mark-all-read:hover {
        background: #e9ecef;
    }

    .main-content {
        flex: 1;
        margin-left: 280px;
        transition: all 0.3s ease;
    }

    .top-navbar {
        background: white;
        padding: 15px 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        border-bottom: 2px solid var(--primary-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sidebar-toggle {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: var(--black);
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .sidebar-toggle:hover {
        color: var(--primary-color);
    }

    .admin-user {
        display: flex;
        align-items: center;
        margin-left: auto;
        gap: 20px;
        z-index: 100;
    }

    .notification-icon {
        position: relative;
        font-size: 1.3rem;
        color: var(--black);
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .notification-icon:hover {
        color: var(--primary-color);
    }

    .notification-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: var(--dark-orange);
        color: white;
        border-radius: 50%;
        min-width: 20px;
        height: 20px;
        padding: 2px 6px;
        font-size: 0.7rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse 2s infinite;
        border: 2px solid white;
    }

    .content-area {
        padding: 30px;
        background-color: var(--cream-bg);
        min-height: calc(100vh - 80px);
    }

    .page-card { 
        background: white; 
        border-radius: 15px; 
        padding: 25px; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-top: 4px solid var(--primary-color);
        margin-bottom: 25px;
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
        border-top: none;
        color: var(--black);
        font-weight: 600;
        background-color: var(--warm-beige);
        border-bottom: 2px solid var(--primary-color);
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

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }

        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.collapsed {
            transform: translateX(0);
        }

        .content-area {
            padding: 15px;
        }

        .page-card {
            padding: 15px;
        }
    }

    /* Custom Button Styles - Orange & Black Theme */
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
        background-color: var(--light-black);
        border-color: var(--light-black);
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
                <a href="/fluffy-admin/users" class="active">
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
                <h4 class="mb-0">List of Users</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-user-plus me-2"></i>Add Staff</button>
            </div>
            <div class="page-card">
                <div class="table-responsive">
                    <table class="table align-middle" id="usersTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th style="width:130px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="5" class="text-center py-4">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="addUserForm">
        <div class="modal-body">
          <div class="mb-3"><label class="form-label">Name *</label><input class="form-control" name="name" required placeholder="Enter full name"></div>
          <div class="mb-3"><label class="form-label">Email *</label><input type="email" class="form-control" name="email" required placeholder="name@example.com"></div>
          <div class="mb-3"><label class="form-label">Password *</label><input type="password" class="form-control" name="password" required minlength="6" placeholder="Minimum 6 characters"></div>
          <div class="mb-3"><label class="form-label">Role *</label><select class="form-control" name="role" required><option value="">Select Role</option><option value="customer">Customer</option><option value="staff">Staff</option><option value="admin">Admin</option></select></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Create</button></div>
      </form>
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

        // Profile dropdown functionality
        function toggleProfileDropdown() {
            const profileMenu = document.getElementById('profileMenu');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            // Close notification dropdown if open
            if (notificationDropdown.classList.contains('show')) {
                notificationDropdown.classList.remove('show');
            }
            
            // Toggle profile menu
            profileMenu.classList.toggle('show');
        }

        // Close profile dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.querySelector('.profile-dropdown');
            const profileMenu = document.getElementById('profileMenu');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            if (!profileDropdown.contains(event.target)) {
                profileMenu.classList.remove('show');
            }
            
            if (!document.getElementById('notificationIcon').contains(event.target)) {
                notificationDropdown.classList.remove('show');
            }
        });

        // Toggle notification dropdown
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
            
            if (dropdown.classList.contains('show')) {
                loadNotifications();
            }
        }

        // Load notifications
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

        // Display notifications
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

        // Update notification badge
        function updateNotificationBadge(count) {
            const badge = document.getElementById('notificationBadge');
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Mark notification as read
        function markAsRead(notificationId, orderId) {
            fetch(`/fluffy-admin/api/notifications/${notificationId}/read`, {
                method: 'PUT'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    
                    // Redirect to order page if applicable
                    if (orderId) {
                        window.location.href = '/fluffy-admin/orders';
                    }
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        // Mark all notifications as read
        function markAllAsRead() {
            fetch('/fluffy-admin/api/notifications/mark-all-read', {
                method: 'PUT'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error marking all as read:', error));
        }

        // Helper: Time ago function
        function timeAgo(dateString) {
            const date = new Date(dateString);
            const seconds = Math.floor((new Date() - date) / 1000);
            
            const intervals = {
                year: 31536000,
                month: 2592000,
                week: 604800,
                day: 86400,
                hour: 3600,
                minute: 60
            };
            
            for (let [name, seconds_in] of Object.entries(intervals)) {
                const interval = Math.floor(seconds / seconds_in);
                if (interval >= 1) {
                    return interval === 1 ? `1 ${name} ago` : `${interval} ${name}s ago`;
                }
            }
            
            return 'Just now';
        }

        // Helper: Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Load notifications on page load
        loadNotifications();

        // Refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);

loadUsers();

let usersTable = null;

function loadUsers(){
  fetch('/fluffy-admin/api/users')
    .then(r=>r.json())
    .then(({success, data})=>{
      const tbody = document.querySelector('#usersTable tbody');
      if(!success){ tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load</td></tr>'; 
        if (usersTable) { usersTable.destroy(); usersTable = null; }
        return; 
      }
      if(!data.length){ tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No users</td></tr>'; 
        if (usersTable) { usersTable.destroy(); usersTable = null; }
        return; 
      }
      tbody.innerHTML = data.map(u=>{
        // Ensure status is properly set
        const status = u.status || 'active';
        const isActive = status === 'active';
        
        return `
        <tr>
          <td>${u.name}</td>
          <td>${u.email}</td>
          <td><span class="badge bg-${u.role==='admin'?'dark':u.role==='staff'?'info':'secondary'}">${u.role}</span></td>
          <td><span class="badge bg-${isActive ? 'success' : 'danger'}">${status}</span></td>
          <td>
            <button class="btn btn-sm ${isActive ? 'btn-outline-danger' : 'btn-outline-success'}" onclick="toggleUserStatus(${u.id}, '${status}', event)">
              <i class="fas fa-${isActive ? 'user-times' : 'user-check'}"></i> ${isActive ? 'Deactivate' : 'Activate'}
            </button>
          </td>
        </tr>
        `;
      }).join('');
      
      // Destroy existing DataTable if it exists
      if (usersTable) {
        usersTable.destroy();
      }
      
      // Initialize DataTables
      usersTable = $('#usersTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'asc']],
        language: {
          search: "Search:",
          lengthMenu: "Show _MENU_ entries",
          info: "Showing _START_ to _END_ of _TOTAL_ entries",
          infoEmpty: "Showing 0 to 0 of 0 entries",
          infoFiltered: "(filtered from _MAX_ total entries)"
        }
      });
    })
    .catch(() => {
      document.querySelector('#usersTable tbody').innerHTML = '<tr><td colspan="5" class="text-center text-danger">Network error</td></tr>';
      if (usersTable) { usersTable.destroy(); usersTable = null; }
    });
}

document.getElementById('addUserForm').addEventListener('submit', function(e){
  e.preventDefault();
  const fd = new URLSearchParams(new FormData(this));
  fetch('/fluffy-admin/api/users', { method:'POST', body:fd })
    .then(r=>r.json()).then(res=>{ if(res.success){ bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide(); this.reset(); loadUsers(); Swal.fire({icon: 'success', title: 'Success!', text: 'User created successfully!'}); } else { Swal.fire({icon: 'error', title: 'Error', text: res.message||'Failed'}); } });
});

function toggleUserStatus(userId, currentStatus, event) {
  const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
  const action = newStatus === 'active' ? 'activate' : 'deactivate';
  
  Swal.fire({
    title: 'Confirm Action',
    text: `Are you sure you want to ${action} this user?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#ff6b35',
    cancelButtonColor: '#6c757d',
    confirmButtonText: `Yes, ${action} it!`
  }).then((result) => {
    if (!result.isConfirmed) return;
  
  // Show loading state - try multiple ways to find the button and row
  let button = null;
  let row = null;
  
  // Try to get button from event if provided
  if (event && event.target) {
    button = event.target.closest ? event.target.closest('button') : null;
    if (!button && event.target.tagName === 'BUTTON') {
      button = event.target;
    }
    if (!button && event.target.parentElement && event.target.parentElement.tagName === 'BUTTON') {
      button = event.target.parentElement;
    }
  }
  
  // If still no button, try to find it by userId in the table
  if (!button && usersTable) {
    const tableRows = document.querySelectorAll('#usersTable tbody tr');
    for (let tr of tableRows) {
      const btn = tr.querySelector(`button[onclick*="toggleUserStatus(${userId}"]`);
      if (btn) {
        button = btn;
        row = tr;
        break;
      }
    }
  }
  
  // Fallback: find by userId in any button
  if (!button) {
    const allButtons = document.querySelectorAll(`button[onclick*="toggleUserStatus(${userId}"]`);
    if (allButtons.length > 0) {
      button = allButtons[0];
      row = button.closest('tr');
    }
  }
  
  // If we have button but no row, try to find row
  if (button && !row) {
    row = button.closest('tr');
  }
  
  if (!button) {
    console.error('Button not found for userId:', userId);
    Swal.fire({icon: 'error', title: 'Error', text: 'Button not found. Please refresh the page.'});
    return;
  }
  
  if (!row) {
    console.error('Row not found for userId:', userId);
    // Try to reload the table as fallback
    loadUsers();
    Swal.fire({icon: 'error', title: 'Error', text: 'Row not found. Table will be refreshed.'});
    return;
  }
  
  const originalText = button.innerHTML;
  button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
  button.disabled = true;
  
  console.log('Sending status:', newStatus, 'for user:', userId);
  
  fetch(`/fluffy-admin/api/users/${userId}/toggle-status`, {
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
      const isActive = newStatus === 'active';
      
      // Update the row cells directly - check if row still exists
      if (row && row.parentNode) {
        const statusCell = row.querySelector('td:nth-child(4)');
        const actionCell = row.querySelector('td:nth-child(5)');
        
        if (statusCell) {
          statusCell.innerHTML = `<span class="badge bg-${isActive ? 'success' : 'danger'}">${newStatus}</span>`;
        }
        
        if (actionCell) {
          actionCell.innerHTML = `
            <button class="btn btn-sm ${isActive ? 'btn-outline-danger' : 'btn-outline-success'}" onclick="toggleUserStatus(${userId}, '${newStatus}', event)">
              <i class="fas fa-${isActive ? 'user-times' : 'user-check'}"></i> ${isActive ? 'Deactivate' : 'Activate'}
            </button>
          `;
        }
      }
      
      // Update DataTables row data if available
      if (usersTable && row) {
        try {
          const dtRow = usersTable.row(row);
          if (dtRow && dtRow.node()) {
            const rowData = dtRow.data();
            if (rowData && Array.isArray(rowData) && rowData.length >= 5) {
              // Update the status in the row data
              rowData[3] = `<span class="badge bg-${isActive ? 'success' : 'danger'}">${newStatus}</span>`;
              rowData[4] = `
                <button class="btn btn-sm ${isActive ? 'btn-outline-danger' : 'btn-outline-success'}" onclick="toggleUserStatus(${userId}, '${newStatus}', event)">
                  <i class="fas fa-${isActive ? 'user-times' : 'user-check'}"></i> ${isActive ? 'Deactivate' : 'Activate'}
                </button>
              `;
              dtRow.data(rowData).draw(false);
            } else {
              // Fallback: just redraw
              usersTable.draw(false);
            }
          } else {
            // Fallback: redraw the table
            usersTable.draw(false);
          }
        } catch (e) {
          console.error('DataTables update error:', e);
          // Fallback: reload the table
      loadUsers();
        }
    } else {
        // If no DataTables, just reload
        loadUsers();
      }
      
      Swal.fire({icon: 'success', title: 'Success!', text: `User ${action}d successfully!`, timer: 2000, timerProgressBar: true});
    } else {
      // Restore button on error
      if (button) {
        button.innerHTML = originalText;
        button.disabled = false;
      }
      Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to update user status'});
    }
  })
  .catch(error => {
    console.error('Error:', error);
    // Restore button on error
    if (button) {
    button.innerHTML = originalText;
    button.disabled = false;
    }
    Swal.fire({icon: 'error', title: 'Error', text: 'Network error: ' + error.message});
  });
  });
}

</script>
</body>
</html>
