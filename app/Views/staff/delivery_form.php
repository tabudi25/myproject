<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Animal Delivery - All Orders - Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
            --accent-color: #004e89;
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
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.4);
        }

        .photo-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .animal-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .animal-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(255, 107, 53, 0.1);
        }

        .animal-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
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
                <a href="/logout" class="btn btn-outline-danger">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
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
                <a href="/staff/orders" class="sidebar-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="/staff/delivery-confirmations" class="sidebar-item active">
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
                        <i class="fas fa-truck"></i> Confirm Animal Delivery - All Customer Orders
                    </h3>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> This includes all customer orders (both pickup and delivery) that need delivery confirmation.
                    </div>

                    <?php if (session()->getFlashdata('msg')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= session()->getFlashdata('msg') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="/staff/delivery-confirmations/store" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Order *</label>
                                <select class="form-select" name="order_id" id="orderSelect" required>
                                    <option value="">Choose an order...</option>
                                    <?php foreach ($orders as $order): ?>
                                        <option value="<?= $order['id'] ?>">
                                            Order #<?= $order['order_number'] ?> - <?= $order['customer_name'] ?> - ₱<?= number_format($order['total_amount'], 2) ?> (<?= ucfirst($order['delivery_type']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                        <!-- Order Details (will be populated via AJAX) -->
                        <div id="orderDetails" class="mb-4" style="display: none;">
                            <h5>Order Details</h5>
                            <div id="orderInfo"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Delivery Address *</label>
                                <textarea class="form-control" name="delivery_address" id="deliveryAddress" rows="3" required placeholder="Enter the delivery address"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Delivery Notes</label>
                                <textarea class="form-control" name="delivery_notes" rows="3" placeholder="Any additional notes about the delivery"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Amount Received *</label>
                                <input type="number" step="0.01" class="form-control" name="payment_amount" required placeholder="0.00">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Method *</label>
                                <select class="form-select" name="payment_method" required>
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
                                <label class="form-label">Delivery Photo *</label>
                                <input type="file" class="form-control" name="delivery_photo" accept="image/*" required>
                                <small class="text-muted">Take a photo of the animal being delivered to the customer</small>
                                <div id="deliveryPhotoPreview"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Proof Photo *</label>
                                <input type="file" class="form-control" name="payment_photo" accept="image/*" required>
                                <small class="text-muted">Photo of payment receipt or transaction proof</small>
                                <div id="paymentPhotoPreview"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/staff/delivery-confirmations" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Deliveries
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Submit Delivery Confirmation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle order selection
        document.getElementById('orderSelect').addEventListener('change', function() {
            const orderId = this.value;
            const orderDetails = document.getElementById('orderDetails');
            const orderInfo = document.getElementById('orderInfo');
            
            if (orderId) {
                // Load order details
                loadOrderDetails(orderId);
            } else {
                orderDetails.style.display = 'none';
            }
        });

        // Load order details via AJAX
        function loadOrderDetails(orderId) {
            console.log('Loading order details for ID:', orderId);
            
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
                console.log('Order details response:', data);
                if (data.success) {
                    displayOrderDetails(data.order, data.items);
                } else {
                    console.error('Error loading order details:', data.message);
                    alert(data.message || 'Failed to load order details.');
                    document.getElementById('orderDetails').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading order details:', error);
                alert('Error loading order details. Please try again.');
            });
        }

        // Display order details
        function displayOrderDetails(order, items) {
            const orderInfo = document.getElementById('orderInfo');
            const orderDetails = document.getElementById('orderDetails');
            
            // Automatically set the animal_id to the first animal in the order
            if (items.length > 0) {
                // Create a hidden input for animal_id
                let animalIdInput = document.getElementById('animal_id_hidden');
                if (!animalIdInput) {
                    animalIdInput = document.createElement('input');
                    animalIdInput.type = 'hidden';
                    animalIdInput.name = 'animal_id';
                    animalIdInput.id = 'animal_id_hidden';
                    document.querySelector('form').appendChild(animalIdInput);
                }
                animalIdInput.value = items[0].animal_id;
            }
            
            // If the order has a delivery address, auto-fill it
            if (order.delivery_type === 'delivery' && order.delivery_address) {
                const addressField = document.getElementById('deliveryAddress');
                if (addressField && !addressField.value) {
                    addressField.value = order.delivery_address;
                }
            }
            
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Order Number:</strong> #${order.order_number || 'N/A'}</p>
                        <p><strong>Customer:</strong> ${order.customer_name || 'Customer Name Not Available'}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Amount:</strong> ₱${parseFloat(order.total_amount || 0).toLocaleString()}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Animal Being Delivered:</h6>
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <img src="/uploads/${items[0].animal_image}" class="animal-image me-3" alt="${items[0].animal_name}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            <div>
                                <h6 class="mb-1">${items[0].animal_name}</h6>
                                <p class="mb-0 text-muted">₱${parseFloat(items[0].price).toLocaleString()}</p>
                                <small class="text-success"><i class="fas fa-check-circle me-1"></i>Selected for delivery</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                ${items.length > 1 ? `
                <div class="mt-3">
                    <h6>Other animals in this order (${items.length - 1}):</h6>
                    <div class="row">
                ` : ''}
            `;
            
            // Show other animals if there are more than one
            if (items.length > 1) {
                items.slice(1).forEach(item => {
                    html += `
                        <div class="col-md-4">
                            <div class="animal-card">
                                <div class="d-flex align-items-center">
                                    <img src="/uploads/${item.animal_image}" class="animal-image me-3" alt="${item.animal_name}">
                                    <div>
                                        <h6 class="mb-1">${item.animal_name}</h6>
                                        <p class="mb-0 text-muted">₱${parseFloat(item.price).toLocaleString()}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += `</div></div>`;
            }
            
            html += `
                    </div>
                </div>
            `;
            
            orderInfo.innerHTML = html;
            orderDetails.style.display = 'block';
        }

        // Preview uploaded images
        document.querySelector('input[name="delivery_photo"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('deliveryPhotoPreview').innerHTML = 
                        `<img src="${e.target.result}" class="photo-preview" alt="Delivery photo preview">`;
                };
                reader.readAsDataURL(file);
            }
        });

        document.querySelector('input[name="payment_photo"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('paymentPhotoPreview').innerHTML = 
                        `<img src="${e.target.result}" class="photo-preview" alt="Payment photo preview">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>

