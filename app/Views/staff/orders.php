<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Adoptions - Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .badge-pending { background: #ffc107; color: #000; }
        .badge-confirmed { background: #28a745; color: #fff; }
        .badge-processing { background: #17a2b8; color: #fff; }
        .badge-shipped { background: #6610f2; color: #fff; }
        .badge-delivered { background: #198754; color: #fff; }
        .badge-cancelled { background: #dc3545; color: #fff; }
        
        /* Payment status badges */
        .badge-paid { background: #28a745; color: #fff; }
        .badge-failed { background: #dc3545; color: #fff; }
        .badge-refunded { background: #6c757d; color: #fff; }
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
                <a href="/staff/add-animal" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add New Animals</span>
                </a>
                <a href="/staff/animals" class="sidebar-item">
                    <i class="fas fa-paw"></i>
                    <span>Manage Animals</span>
                </a>
                <a href="/staff/orders" class="sidebar-item active">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Adoptions</span>
                </a>
                <a href="/staff/delivery-confirmations" class="sidebar-item">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="/staff/payments" class="sidebar-item">
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
                        <i class="fas fa-shopping-cart"></i> Customer Adoptions
                    </h3>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Adoption #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Delivery Type</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                <tr>
                                    <td colspan="9" class="text-center">
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

        loadAdoptions();

        function loadAdoptions() {
            fetch('/staff/api/orders')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderAdoptions(data.data);
                    } else {
                        document.getElementById('ordersTableBody').innerHTML = 
                            '<tr><td colspan="9" class="text-center text-danger">Failed to load adoptions</td></tr>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('ordersTableBody').innerHTML = 
                        '<tr><td colspan="9" class="text-center text-danger">Network error</td></tr>';
                });
        }

        function renderAdoptions(orders) {
            const tbody = document.getElementById('ordersTableBody');
            
            if (orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center">No adoptions found</td></tr>';
                return;
            }

            tbody.innerHTML = orders.map(order => `
                <tr>
                    <td><strong>#${order.order_number}</strong></td>
                    <td>
                        ${order.customer_name}<br>
                        <small class="text-muted">${order.customer_email}</small>
                    </td>
                    <td>₱${parseFloat(order.total_amount).toLocaleString()}</td>
                    <td>${order.delivery_type}</td>
                    <td>${order.payment_method}</td>
                    <td><span class="badge badge-${order.payment_status} text-white">${order.payment_status}</span></td>
                    <td><span class="badge badge-${order.status} text-white">${order.status}</span></td>
                    <td>${formatDate(order.created_at)}</td>
                    <td>
                        <div class="btn-group" role="group">
                            ${order.status === 'pending' ? `
                                <button class="btn btn-sm btn-success" onclick="confirmAdoption(${order.id})">
                                    <i class="fas fa-check"></i> Confirm
                                </button>
                            ` : ''}
                            ${order.status === 'confirmed' ? `
                                <button class="btn btn-sm btn-info" onclick="startPreparation(${order.id})">
                                    <i class="fas fa-paw"></i> Start Prep
                                </button>
                            ` : ''}
                            ${order.status === 'processing' ? `
                                <button class="btn btn-sm btn-warning" onclick="markReadyForDelivery(${order.id})">
                                    <i class="fas fa-box"></i> Ready
                                </button>
                            ` : ''}
                            ${order.status === 'shipped' ? `
                                <button class="btn btn-sm btn-primary" onclick="markDelivered(${order.id})">
                                    <i class="fas fa-truck"></i> Delivered
                                </button>
                                <button class="btn btn-sm btn-success" onclick="confirmAdoption(${order.id})">
                                    <i class="fas fa-check-circle"></i> Confirm Delivery
                                </button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function confirmAdoption(id) {
            Swal.fire({
                title: 'Confirm Adoption?',
                text: 'Are you sure you want to confirm this adoption?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, confirm it!'
            }).then((result) => {
                if (!result.isConfirmed) return;
            
            const formData = new URLSearchParams();
            formData.append('_method', 'PUT');
            
            fetch('/staff/api/orders/' + id + '/confirm', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    loadAdoptions();
                        Swal.fire({icon: 'success', title: 'Success!', text: 'Adoption confirmed successfully!'});
                } else {
                        Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to confirm adoption'});
                }
            })
            .catch(err => {
                console.error(err);
                    Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
                });
            });
        }

        function startPreparation(id) {
            Swal.fire({
                title: 'Start Preparation?',
                text: 'Start preparing this pet for delivery?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, start it!'
            }).then((result) => {
                if (!result.isConfirmed) return;
            
            const formData = new URLSearchParams();
            formData.append('status', 'processing');
            
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting...';
            button.disabled = true;
            
            fetch('/staff/api/orders/' + id + '/update-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    loadAdoptions();
                    showSuccessNotification('Pet preparation started! Customer has been notified and their adoption tracking page will update automatically.');
                } else {
                        Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to start preparation'});
                }
            })
            .catch(err => {
                console.error(err);
                    Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                });
            });
        }

        function markReadyForDelivery(id) {
            Swal.fire({
                title: 'Mark as Ready?',
                text: 'Mark this adoption as ready for delivery?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, mark it!'
            }).then((result) => {
                if (!result.isConfirmed) return;
            
            const formData = new URLSearchParams();
            formData.append('status', 'shipped');
            
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marking...';
            button.disabled = true;
            
            fetch('/staff/api/orders/' + id + '/update-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    loadAdoptions();
                    showSuccessNotification('Adoption marked as ready for delivery! Customer has been notified and their adoption tracking page will update automatically.');
                } else {
                        Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to mark as ready'});
                }
            })
            .catch(err => {
                console.error(err);
                    Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                });
            });
        }

        function markDelivered(id) {
            Swal.fire({
                title: 'Mark as Delivered?',
                text: 'Mark this adoption as delivered?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, mark it!'
            }).then((result) => {
                if (!result.isConfirmed) return;
            
            const formData = new URLSearchParams();
            formData.append('status', 'delivered');
            
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marking...';
            button.disabled = true;
            
            fetch('/staff/api/orders/' + id + '/update-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    loadAdoptions();
                    showSuccessNotification('Adoption marked as delivered! Customer has been notified and their adoption tracking page will update automatically.');
                } else {
                        Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to mark as delivered'});
                }
            })
            .catch(err => {
                console.error(err);
                    Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                });
            });
        }

        // Show success notification
        function showSuccessNotification(message) {
            const notification = document.createElement('div');
            notification.innerHTML = `
                <div style="display: flex; align-items: center;">
                    <i class="fas fa-check-circle" style="margin-right: 10px; color: #28a745;"></i>
                    <div>
                        <strong>Success!</strong><br>
                        ${message}
                    </div>
                </div>
            `;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #28a745, #20c997);
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                font-size: 14px;
                z-index: 1000;
                box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                max-width: 350px;
                animation: slideInRight 0.5s ease-out;
            `;
            
            // Add animation keyframes
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
            
            document.body.appendChild(notification);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.animation = 'slideInRight 0.5s ease-out reverse';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 500);
                }
            }, 5000);
        }
    </script>

    <!-- Delivery Confirmation Modal -->
    <div class="modal fade" id="deliveryConfirmationModal" tabindex="-1" aria-labelledby="deliveryConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deliveryConfirmationModalLabel">
                        <i class="fas fa-check-circle"></i> Confirm Animal Adoption
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deliveryConfirmationForm" enctype="multipart/form-data">
                        <input type="hidden" id="confirmationOrderId" name="order_id">
                        
                        <!-- Adoption Details Section -->
                        <div id="confirmationOrderDetails" class="mb-4" style="display: none;">
                            <h6><i class="fas fa-info-circle"></i> Adoption Details</h6>
                            <div id="confirmationOrderInfo" class="bg-light p-3 rounded"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3" id="confirmationDeliveryAddressField">
                                <label for="confirmationDeliveryAddress" class="form-label">Delivery Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="confirmationDeliveryAddress" name="delivery_address" rows="3" required placeholder="Enter the delivery address"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirmationDeliveryNotes" class="form-label" id="confirmationNotesLabel">Delivery Notes</label>
                                <textarea class="form-control" id="confirmationDeliveryNotes" name="delivery_notes" rows="3" placeholder="Any additional notes about the delivery"></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="confirmationPaymentAmount" class="form-label">Payment Amount Received <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="confirmationPaymentAmount" name="payment_amount" required placeholder="0.00">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirmationPaymentMethod" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select" id="confirmationPaymentMethod" name="payment_method" required>
                                    <option value="">Select payment method...</option>
                                    <option value="cash">Cash</option>
                                    <option value="gcash">GCash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="confirmationDeliveryPhoto" class="form-label">Delivery Photo <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="confirmationDeliveryPhoto" name="delivery_photo" accept="image/*" required>
                                <div class="form-text">Take a photo of the animal being delivered to the customer</div>
                                <div id="confirmationDeliveryPhotoPreview"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirmationPaymentPhoto" class="form-label">Payment Proof Photo <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="confirmationPaymentPhoto" name="payment_photo" accept="image/*" required>
                                <div class="form-text">Photo of payment receipt or transaction proof</div>
                                <div id="confirmationPaymentPhotoPreview"></div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> This confirmation will be reviewed by admin and the adoption status will be updated accordingly.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="submitDeliveryConfirmation()">
                        <i class="fas fa-check"></i> Submit Adoption Confirmation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Delivery Confirmation Functions
        function confirmAdoption(orderId) {
            document.getElementById('confirmationOrderId').value = orderId;
            loadConfirmationOrderDetails(orderId);
            const modal = new bootstrap.Modal(document.getElementById('deliveryConfirmationModal'));
            modal.show();
        }
        
        function loadConfirmationOrderDetails(orderId) {
            fetch('/staff/delivery-confirmations/get-order-details', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'order_id=' + orderId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayConfirmationOrderDetails(data.order, data.items);
                } else {
                    Swal.fire({icon: 'error', title: 'Error', text: 'Failed to load adoption details: ' + data.message});
                }
            })
            .catch(error => {
                console.error('Error loading adoption details:', error);
                Swal.fire({icon: 'error', title: 'Error', text: 'Error loading adoption details. Please try again.'});
            });
        }
        
        function displayConfirmationOrderDetails(order, items) {
            const orderInfo = document.getElementById('confirmationOrderInfo');
            const orderDetails = document.getElementById('confirmationOrderDetails');
            
            // Handle delivery type - hide/show delivery address and change labels
            const deliveryAddressField = document.getElementById('confirmationDeliveryAddressField');
            const deliveryAddress = document.getElementById('confirmationDeliveryAddress');
            const notesLabel = document.getElementById('confirmationNotesLabel');
            const deliveryNotes = document.getElementById('confirmationDeliveryNotes');
            
            if (order.delivery_type === 'pickup') {
                deliveryAddressField.style.display = 'none';
                deliveryAddress.required = false;
                notesLabel.textContent = 'Notes';
                deliveryNotes.placeholder = 'Any additional notes about the pickup';
            } else {
                deliveryAddressField.style.display = 'block';
                deliveryAddress.required = true;
                notesLabel.textContent = 'Delivery Notes';
                deliveryNotes.placeholder = 'Any additional notes about the delivery';
                if (order.delivery_address) {
                    if (deliveryAddress && !deliveryAddress.value) {
                        deliveryAddress.value = order.delivery_address;
                    }
                }
            }
            
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Adoption Number:</strong> #${order.order_number || 'N/A'}</p>
                        <p><strong>Customer:</strong> ${order.customer_name || 'Customer Name Not Available'}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Amount:</strong> ₱${parseFloat(order.total_amount || 0).toLocaleString()}</p>
                        <p><strong>Delivery Type:</strong> ${order.delivery_type}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Animal Being Delivered:</h6>
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <img src="/uploads/${items[0].animal_image}" class="me-3" alt="${items[0].animal_name}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            <div>
                                <h6 class="mb-1">${items[0].animal_name}</h6>
                                <p class="mb-0 text-muted">₱${parseFloat(items[0].price).toLocaleString()}</p>
                                <small class="text-success"><i class="fas fa-check-circle me-1"></i>Selected for delivery</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            orderInfo.innerHTML = html;
            orderDetails.style.display = 'block';
        }
        
        function submitDeliveryConfirmation() {
            const form = document.getElementById('deliveryConfirmationForm');
            const formData = new FormData(form);
            
            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show loading state
            const submitBtn = document.querySelector('#deliveryConfirmationModal .btn-success');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            submitBtn.disabled = true;
            
            fetch('/staff/delivery-confirmations/store', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessNotification('Adoption confirmation submitted successfully! Admin will review it.');
                    bootstrap.Modal.getInstance(document.getElementById('deliveryConfirmationModal')).hide();
                    form.reset();
                    loadAdoptions(); // Refresh adoptions
                } else {
                    Swal.fire({icon: 'error', title: 'Error', text: 'Failed to submit adoption confirmation: ' + (data.message || 'Unknown error')});
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
        
        // Preview uploaded images for delivery confirmation
        document.getElementById('confirmationDeliveryPhoto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('confirmationDeliveryPhotoPreview').innerHTML = 
                        `<img src="${e.target.result}" class="img-thumbnail mt-2" style="max-width: 200px; max-height: 200px;" alt="Delivery photo preview">`;
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('confirmationPaymentPhoto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('confirmationPaymentPhotoPreview').innerHTML = 
                        `<img src="${e.target.result}" class="img-thumbnail mt-2" style="max-width: 200px; max-height: 200px;" alt="Payment photo preview">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>

