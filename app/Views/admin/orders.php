<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Orders - Admin</title>
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
        border-bottom: 1px solid #495057;
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
        border-bottom: 1px solid #495057;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        color: #adb5bd;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
        background: var(--sidebar-hover);
        color: white;
    }

    .sidebar-menu i {
        width: 20px;
        margin-right: 15px;
        text-align: center;
    }

    .main-content {
        flex: 1;
        margin-left: 280px;
        transition: all 0.3s ease;
    }

    .top-navbar {
        background: white;
        padding: 15px 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .sidebar-toggle {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: var(--accent-color);
        cursor: pointer;
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
        color: var(--accent-color);
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
        background: #dc3545;
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
    }

    .notification-item:hover {
        background: #f8f9fa;
    }

    .notification-item.unread {
        background: #e3f2fd;
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

    .notification-title {
        font-weight: 600;
        color: var(--accent-color);
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

    /* Profile Dropdown Styles */
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
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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

    .content-area {
        padding: 30px;
    }

    .page-card { 
        background:#fff; 
        border-radius:12px; 
        padding:20px; 
        box-shadow:0 2px 10px rgba(0,0,0,.05); 
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
                <a href="/fluffy-admin/orders" class="active">
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
                <h4 class="mb-0">List of Adoption</h4>
            </div>
            <div class="page-card">
                <div class="table-responsive">
                    <table class="table align-middle" id="ordersTable">
                        <thead>
                            <tr>
                                <th>Adoptions #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Delivery</th>
                                <th>Created</th>
                                <th style="width:160px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="8" class="text-center py-4">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Delivery Confirmations -->
            <div class="page-card mb-4" id="deliveryConfirmationsSection">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-truck"></i> Delivery Confirmations
                    </h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="filterConfirmations('all')">All</button>
                        <button type="button" class="btn btn-outline-warning" onclick="filterConfirmations('pending')">Pending</button>
                        <button type="button" class="btn btn-outline-success" onclick="filterConfirmations('confirmed')">Confirmed</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="deliveryConfirmationsTable">
                        <thead>
                            <tr>
                                <th>Adoptions #</th>
                                <th>Customer</th>
                                <th>Pet</th>
                                <th>Staff</th>
                                <th>Payment Amount</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center text-muted">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Order Modal -->
<div class="modal fade" id="editOrderModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editOrderForm">
        <input type="hidden" name="edit_id" id="edit_id">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Order Number</label>
              <input type="text" class="form-control" id="edit_order_number" readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Customer</label>
              <input type="text" class="form-control" id="edit_customer" readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Status *</label>
              <select class="form-control" name="status" id="edit_status" required>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Payment Status *</label>
              <select class="form-control" name="payment_status" id="edit_payment_status" required>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Delivery Type</label>
              <input type="text" class="form-control" id="edit_delivery_type" readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Total Amount</label>
              <input type="text" class="form-control" id="edit_total_amount" readonly>
            </div>
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

loadOrders();

function loadOrders(){
  fetch('/fluffy-admin/api/orders')
    .then(r=>r.json())
    .then(({success, data})=>{
      const tbody = document.querySelector('#ordersTable tbody');
      if(!success){ tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Failed to load</td></tr>'; return; }
      if(!data.length){ tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No orders</td></tr>'; return; }
      tbody.innerHTML = data.map(o=>`
        <tr data-order-id="${o.id}">
          <td>${o.order_number||o.id}</td>
          <td>${o.customer_name||o.user_id}</td>
          <td>₱${parseFloat(o.total_amount).toLocaleString()}</td>
          <td><span class="badge bg-${o.status==='pending'?'warning':o.status==='delivered'?'success':'secondary'}">${o.status}</span></td>
          <td>${o.payment_method||''} / <span class="badge bg-${o.payment_status==='pending'?'warning':o.payment_status==='paid'?'success':o.payment_status==='failed'?'danger':'secondary'} text-white">${o.payment_status||''}</span></td>
          <td>${o.delivery_type||''}</td>
          <td>${formatDate(o.created_at)}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary" onclick="editOrder(${o.id})" title="Edit Order">
              <i class="fas fa-edit"></i>
            </button>
          </td>
        </tr>
      `).join('');
    });
}

function editOrder(id){
  fetch('/fluffy-admin/api/orders')
    .then(r=>r.json())
    .then(({success, data})=>{
      const order = data.find(o => o.id == id);
      if(!order) return alert('Order not found');
      
      document.getElementById('edit_id').value = order.id;
      document.getElementById('edit_order_number').value = order.order_number || order.id;
      document.getElementById('edit_customer').value = order.customer_name || order.user_id;
      document.getElementById('edit_status').value = order.status;
      document.getElementById('edit_payment_status').value = order.payment_status || 'pending';
      document.getElementById('edit_delivery_type').value = order.delivery_type || 'N/A';
      document.getElementById('edit_total_amount').value = '₱' + parseFloat(order.total_amount).toLocaleString();
      
      new bootstrap.Modal(document.getElementById('editOrderModal')).show();
    });
}

document.getElementById('editOrderForm').addEventListener('submit', function(e){
  e.preventDefault();
  const id = document.getElementById('edit_id').value;
  const status = document.getElementById('edit_status').value;
  const paymentStatus = document.getElementById('edit_payment_status').value;
  
  if (!status) {
    alert('Please select a status');
    return;
  }
  
  if (!paymentStatus) {
    alert('Please select a payment status');
    return;
  }
  
  // Add confirmation dialog
  if (!confirm(`Are you sure you want to update this order?\n\nOrder Status: ${status}\nPayment Status: ${paymentStatus}`)) {
    return;
  }
  
  // Use POST with _method override for better compatibility
  const formData = new URLSearchParams();
  formData.append('status', status);
  formData.append('payment_status', paymentStatus);
  formData.append('_method', 'PUT');
  
  fetch('/fluffy-admin/api/orders/'+id+'/status', { 
    method:'POST', 
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: formData 
  })
    .then(r=>r.json())
    .then(res=>{
      if(res.success){
        bootstrap.Modal.getInstance(document.getElementById('editOrderModal')).hide();
        // If delivered and paid, create a delivery confirmation server-side, then refresh confirmations
        if (status === 'delivered' && paymentStatus === 'paid') {
          fetch(`/fluffy-admin/api/orders/${id}/delivery-confirmation`, { method: 'POST' })
            .then(r => r.json())
            .then(dcRes => {
              // Remove from list if present and refresh confirmations
              const row = document.querySelector(`#ordersTable tbody tr[data-order-id="${id}"]`);
              if (row) row.remove();
              loadDeliveryConfirmations();
            })
            .catch(() => {
              // Fallback: refresh both lists
              loadOrders();
              loadDeliveryConfirmations();
            });
        } else {
          loadOrders();
        }
        alert('Order updated successfully!');
      } else { 
        alert(res.message || 'Failed to update order'); 
      }
    })
    .catch(err=>{
      console.error('Error:', err);
      alert('Network error. Please try again.');
    });
});

// Delivery Confirmation Functions
let currentFilter = 'all';

function loadDeliveryConfirmations() {
    const tbody = document.querySelector('#deliveryConfirmationsTable tbody');
    tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Loading...</td></tr>';
    
    fetch('/fluffy-admin/api/delivery-confirmations')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                let filteredData = data.data;
                if (currentFilter !== 'all') {
                    filteredData = data.data.filter(confirmation => confirmation.status === currentFilter);
                }
                
                tbody.innerHTML = filteredData.map(confirmation => `
                    <tr>
                        <td>${confirmation.order_number}</td>
                        <td>${confirmation.customer_name}</td>
                        <td>${confirmation.animal_name}</td>
                        <td>${confirmation.staff_name}</td>
                        <td>₱${parseFloat(confirmation.payment_amount).toLocaleString()}</td>
                        <td>
                            <span class="badge bg-${confirmation.status === 'pending' ? 'warning' : confirmation.status === 'confirmed' ? 'success' : 'danger'}">
                                ${confirmation.status.charAt(0).toUpperCase() + confirmation.status.slice(1)}
                            </span>
                        </td>
                        <td>${new Date(confirmation.created_at).toLocaleDateString()}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="viewConfirmation(${confirmation.id})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${confirmation.status === 'pending' ? `
                                    <button class="btn btn-outline-success" onclick="approveConfirmation(${confirmation.id})" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            <i class="fas fa-truck fa-2x mb-2"></i><br>
                            No delivery confirmations found.<br>
                            <small>Staff members haven't submitted any delivery confirmations yet.</small>
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading delivery confirmations:', error);
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error loading data</td></tr>';
        });
}

function filterConfirmations(status) {
    currentFilter = status;
    
    // Update button states
    document.querySelectorAll('#deliveryConfirmationsSection .btn-group button').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    loadDeliveryConfirmations();
}

function viewConfirmation(confirmationId) {
    // Fetch confirmation details and show modal
    fetch(`/fluffy-admin/api/delivery-confirmations/${confirmationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showConfirmationModal(data.data);
            } else {
                alert('Failed to load confirmation details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        });
}

function showConfirmationModal(confirmation) {
    const modalHtml = `
        <div class="modal fade" id="confirmationViewModal" tabindex="-1" aria-labelledby="confirmationViewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationViewModalLabel">
                            <i class="fas fa-truck"></i> Delivery Confirmation Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-info-circle"></i> Order Information</h6>
                                <p><strong>Order #:</strong> ${confirmation.order_number}</p>
                                <p><strong>Customer:</strong> ${confirmation.customer_name}</p>
                                <p><strong>Pet:</strong> ${confirmation.animal_name}</p>
                                <p><strong>Staff:</strong> ${confirmation.staff_name}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-money-bill"></i> Payment Information</h6>
                                <p><strong>Amount:</strong> ₱${parseFloat(confirmation.payment_amount).toLocaleString()}</p>
                                <p><strong>Method:</strong> ${confirmation.payment_method}</p>
                                <p><strong>Submitted:</strong> ${new Date(confirmation.created_at).toLocaleString()}</p>
                                <p><strong>Status:</strong> <span class="badge bg-warning">Pending</span></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <h6><i class="fas fa-map-marker-alt"></i> Delivery Address</h6>
                            <p class="bg-light p-3 rounded">${confirmation.delivery_address || 'N/A'}</p>
                        </div>
                        
                        ${confirmation.delivery_notes ? `
                            <div class="mb-3">
                                <h6><i class="fas fa-sticky-note"></i> Delivery Notes</h6>
                                <p class="bg-light p-3 rounded">${confirmation.delivery_notes}</p>
                            </div>
                        ` : ''}
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-camera"></i> Delivery Photo</h6>
                                <div class="bg-light p-3 rounded text-center">
                                    <img src="/uploads/delivery_confirmations/${confirmation.delivery_photo}" class="img-fluid" style="max-height: 200px;" alt="Delivery photo">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-receipt"></i> Payment Proof Photo</h6>
                                <div class="bg-light p-3 rounded text-center">
                                    <img src="/uploads/delivery_confirmations/${confirmation.payment_photo}" class="img-fluid" style="max-height: 200px;" alt="Payment proof">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="approveConfirmation(${confirmation.id})">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('confirmationViewModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('confirmationViewModal'));
    modal.show();
}

function approveConfirmation(confirmationId) {
    console.log('Approving confirmation ID:', confirmationId);
    
    if (confirm('Are you sure you want to approve this delivery confirmation?')) {
        const notes = prompt('Add admin notes (optional):') || '';
        
        const formData = new URLSearchParams();
        formData.append('admin_notes', notes);
        
        console.log('Sending approval request to:', `/fluffy-admin/api/delivery-confirmations/${confirmationId}/approve`);
        
        fetch(`/fluffy-admin/api/delivery-confirmations/${confirmationId}/approve`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert('Delivery confirmation approved successfully!');
                loadDeliveryConfirmations();
                loadOrders(); // Refresh orders
                // Close modal if open
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmationViewModal'));
                if (modal) modal.hide();
            } else {
                alert('Failed to approve delivery confirmation: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        });
    }
}

// Load data on page load
document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
    loadDeliveryConfirmations();
});
</script>
</body>
</html>
