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
        justify-content: space-between;
        align-items: center;
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
                <a href="/fluffy-admin/animals">
                    <i class="fas fa-paw"></i>
                    <span class="menu-text">Animals</span>
                </a>
            </li>
            <li>
                <a href="/fluffy-admin/categories">
                    <i class="fas fa-tags"></i>
                    <span class="menu-text">Categories</span>
                </a>
            </li>
            <li>
                <a href="/fluffy-admin/orders" class="active">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="menu-text">Orders</span>
                </a>
            </li>
            <li>
                <a href="/fluffy-admin/users">
                    <i class="fas fa-users"></i>
                    <span class="menu-text">Users</span>
                </a>
            </li>
            <li>
                <a href="/">
                    <i class="fas fa-globe"></i>
                    <span class="menu-text">Visit Site</span>
                </a>
            </li>
            <li>
                <a href="/logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="menu-text">Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h4 class="mb-0"><i class="fas fa-shopping-cart text-primary me-2"></i>Manage Orders</h4>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <div class="page-card">
                <div class="table-responsive">
                    <table class="table align-middle" id="ordersTable">
                        <thead>
                            <tr>
                                <th>#</th>
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
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Payment Status *</label>
              <select class="form-control" name="payment_status" id="edit_payment_status" required>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
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
loadOrders();

function loadOrders(){
  fetch('/fluffy-admin/api/orders')
    .then(r=>r.json())
    .then(({success, data})=>{
      const tbody = document.querySelector('#ordersTable tbody');
      if(!success){ tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Failed to load</td></tr>'; return; }
      if(!data.length){ tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No orders</td></tr>'; return; }
      tbody.innerHTML = data.map(o=>`
        <tr>
          <td>${o.order_number||o.id}</td>
          <td>${o.customer_name||o.user_id}</td>
          <td>₱${parseFloat(o.total_amount).toLocaleString()}</td>
          <td><span class="badge bg-${o.status==='pending'?'warning':o.status==='delivered'?'success':'secondary'}">${o.status}</span></td>
          <td>${o.payment_method||''} / ${o.payment_status||''}</td>
          <td>${o.delivery_type||''}</td>
          <td>${o.created_at||''}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary me-1" onclick="editOrder(${o.id})"><i class="fas fa-edit"></i></button>
            <a class="btn btn-sm btn-outline-info" href="/order/${o.id}" target="_blank"><i class="fas fa-eye"></i></a>
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
        loadOrders();
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
</script>
</body>
</html>
