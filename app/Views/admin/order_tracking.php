<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .tracking-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .tracking-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
        }
        .tracking-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8f9fa;
        }
        .tracking-icon {
            background: #ff6b35;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 20px;
        }
        .tracking-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
        }
        .tracking-timeline {
            position: relative;
        }
        .timeline-line {
            position: absolute;
            left: 25px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #e9ecef;
            z-index: 1;
        }
        .timeline-line.active {
            background: #ff6b35;
        }
        .timeline-item {
            position: relative;
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
            z-index: 2;
        }
        .timeline-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 18px;
            flex-shrink: 0;
        }
        .timeline-icon.completed {
            background: #ff6b35;
            color: white;
        }
        .timeline-icon.current {
            background: #ff6b35;
            color: white;
            animation: pulse 2s infinite;
        }
        .timeline-icon.pending {
            background: #e9ecef;
            color: #6c757d;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .timeline-content {
            flex: 1;
        }
        .timeline-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0 0 5px 0;
        }
        .timeline-description {
            color: #6c757d;
            font-size: 14px;
            margin: 0;
        }
        .order-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-info h5 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            color: #6c757d;
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d4edda; color: #155724; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-shipped { background: #e2e3e5; color: #383d41; }
        .status-delivered { background: #d1ecf1; color: #0c5460; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .admin-actions {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .status-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 10px;
            width: 100%;
        }
        .status-select:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
    </style>
</head>
<body style="background: #f8f9fa;">
    <div class="tracking-container">
        <div class="tracking-card">
            <div class="tracking-header">
                <div class="tracking-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h1 class="tracking-title">Order Tracking - Admin View</h1>
            </div>

            <!-- Order Information -->
            <div class="order-info">
                <h5><i class="fas fa-info-circle"></i> Order Information</h5>
                <div class="info-row">
                    <span class="info-label">Order Number:</span>
                    <span class="info-value"><?= $order['order_number'] ?? 'N/A' ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Customer:</span>
                    <span class="info-value"><?= $order['customer_name'] ?? 'N/A' ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= $order['customer_email'] ?? 'N/A' ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order Date:</span>
                    <span class="info-value"><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Amount:</span>
                    <span class="info-value">₱<?= number_format($order['total_amount'], 2) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Delivery Type:</span>
                    <span class="info-value"><?= ucfirst($order['delivery_type']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value"><?= strtoupper($order['payment_method']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Current Status:</span>
                    <span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
                </div>
            </div>

            <!-- Admin Status Update -->
            <div class="admin-actions">
                <h5><i class="fas fa-cog"></i> Update Order Status</h5>
                <form id="statusUpdateForm">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Order Status</label>
                            <select class="status-select" name="status" id="statusSelect">
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Status</label>
                            <select class="status-select" name="payment_status" id="paymentStatusSelect">
                                <option value="pending" <?= $order['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="paid" <?= $order['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tracking Timeline -->
            <div class="tracking-timeline">
                <div class="timeline-line <?= in_array($order['status'], ['confirmed', 'processing', 'shipped', 'delivered']) ? 'active' : '' ?>"></div>
                
                <!-- Order Placed -->
                <div class="timeline-item">
                    <div class="timeline-icon completed">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">Order Placed</h4>
                        <p class="timeline-description">Customer placed the order</p>
                    </div>
                </div>

                <!-- Order Confirmed -->
                <div class="timeline-item">
                    <div class="timeline-icon <?= in_array($order['status'], ['confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : ($order['status'] === 'pending' ? 'current' : 'pending') ?>">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">Order Confirmed</h4>
                        <p class="timeline-description">Order has been confirmed by admin/staff</p>
                    </div>
                </div>

                <!-- Preparing Pet -->
                <div class="timeline-item">
                    <div class="timeline-icon <?= in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'completed' : (in_array($order['status'], ['confirmed']) ? 'current' : 'pending') ?>">
                        <i class="fas fa-paw"></i>
                    </div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">Preparing Pet</h4>
                        <p class="timeline-description">Pet is being prepared for delivery</p>
                    </div>
                </div>

                <!-- Ready for Pickup/Delivery -->
                <div class="timeline-item">
                    <div class="timeline-icon <?= in_array($order['status'], ['shipped', 'delivered']) ? 'completed' : (in_array($order['status'], ['processing']) ? 'current' : 'pending') ?>">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">Ready for <?= $order['delivery_type'] === 'pickup' ? 'Pickup' : 'Delivery' ?></h4>
                        <p class="timeline-description">Order is ready for <?= $order['delivery_type'] ?></p>
                    </div>
                </div>

                <!-- Completed -->
                <div class="timeline-item">
                    <div class="timeline-icon <?= $order['status'] === 'delivered' ? 'completed' : (in_array($order['status'], ['shipped']) ? 'current' : 'pending') ?>">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">Completed</h4>
                        <p class="timeline-description">Order has been completed</p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <?php if (!empty($order['items'])): ?>
            <div class="order-info">
                <h5><i class="fas fa-list"></i> Order Items</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Animal</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td><?= $item['animal_name'] ?? 'N/A' ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>₱<?= number_format($item['price'], 2) ?></td>
                                <td>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Additional Information -->
            <?php if (!empty($order['notes'])): ?>
            <div class="order-info">
                <h5><i class="fas fa-sticky-note"></i> Additional Notes</h5>
                <p><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
            </div>
            <?php endif; ?>

            <!-- Delivery Address (if delivery) -->
            <?php if ($order['delivery_type'] === 'delivery' && !empty($order['delivery_address'])): ?>
            <div class="order-info">
                <h5><i class="fas fa-map-marker-alt"></i> Delivery Address</h5>
                <p><?= nl2br(htmlspecialchars($order['delivery_address'])) ?></p>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline-primary me-2">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
                <a href="<?= base_url('admin') ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> Admin Dashboard
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('statusUpdateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const orderId = <?= $order['id'] ?>;
            
            fetch(`<?= base_url('admin/orders/update-status/') ?>${orderId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order status updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the order status');
            });
        });
    </script>
</body>
</html>
