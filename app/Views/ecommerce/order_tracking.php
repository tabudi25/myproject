<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking - Fluffy Planet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .tracking-container {
            max-width: 800px;
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
        :root {
            --primary-color: #4DD0E1;
            --secondary-color: #FF8A65;
            --dark-orange: #FF7043;
            --black: #444444;
            --dark-black: #333333;
            --light-black: #555555;
            --accent-color: #FF8A65;
            --sidebar-bg: #37474F;
            --sidebar-hover: #4DD0E1;
            --cream-bg: #F9F9F9;
            --warm-beige: #F5E6D3;
            --light-gray: #f5f5f5;
        }

        body {
            background-color: var(--cream-bg);
        }

        .tracking-card {
            border-top: 4px solid var(--primary-color);
        }

        .tracking-icon {
            background: var(--sidebar-hover); color: var(--black);
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
            color: var(--black);
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
            background: var(--sidebar-hover); color: var(--black);
        }
        .timeline-icon.current {
            background: var(--sidebar-hover); color: var(--black);
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
            color: var(--black);
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
            color: var(--black);
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
    </style>
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="tracking-container">
        <div class="tracking-card">
            <div class="tracking-header">
                <div class="tracking-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h1 class="tracking-title">Order Tracking</h1>
            </div>

            <!-- Order Information -->
            <div class="order-info">
                <h5><i class="fas fa-info-circle"></i> Order Information</h5>
                <div class="info-row">
                    <span class="info-label">Order Number:</span>
                    <span class="info-value"><?= $order['order_number'] ?? 'N/A' ?></span>
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
                        <p class="timeline-description">Your order has been received</p>
                    </div>
                </div>

                <!-- Order Confirmed -->
                <div class="timeline-item">
                    <div class="timeline-icon <?= in_array($order['status'], ['confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : ($order['status'] === 'pending' ? 'current' : 'pending') ?>">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">Order Confirmed</h4>
                        <p class="timeline-description">We have confirmed your order</p>
                    </div>
                </div>

                <!-- Preparing Pet -->
                <div class="timeline-item">
                    <div class="timeline-icon <?= in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'completed' : (in_array($order['status'], ['confirmed']) ? 'current' : 'pending') ?>">
                        <i class="fas fa-paw"></i>
                    </div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">Preparing Pet</h4>
                        <p class="timeline-description">
                            <?php if ($order['status'] === 'processing'): ?>
                                We are now preparing your pet for delivery. This may take some time to ensure your pet is healthy and ready.
                            <?php elseif (in_array($order['status'], ['shipped', 'delivered'])): ?>
                                Your pet has been prepared and is ready for <?= $order['delivery_type'] === 'pickup' ? 'pickup' : 'delivery' ?>.
                            <?php else: ?>
                                Your pet will be prepared once the order is confirmed.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <!-- Ready for Pickup/Delivery -->
                <div class="timeline-item">
                    <div class="timeline-icon <?= in_array($order['status'], ['shipped', 'delivered']) ? 'completed' : (in_array($order['status'], ['processing']) ? 'current' : 'pending') ?>">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">Ready for <?= $order['delivery_type'] === 'pickup' ? 'Pickup' : 'Delivery' ?></h4>
                        <p class="timeline-description">
                            <?php if ($order['status'] === 'shipped'): ?>
                                Your order is ready for <?= $order['delivery_type'] === 'pickup' ? 'pickup' : 'delivery' ?>! <?= $order['delivery_type'] === 'pickup' ? 'Please come to our store to collect your pet.' : 'We will deliver your pet to the specified address.' ?>
                            <?php elseif ($order['status'] === 'delivered'): ?>
                                Your order has been successfully <?= $order['delivery_type'] === 'pickup' ? 'picked up' : 'delivered' ?>!
                            <?php else: ?>
                                Your order will be prepared and made ready for <?= $order['delivery_type'] === 'pickup' ? 'pickup' : 'delivery' ?>.
                            <?php endif; ?>
                        </p>
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
                <a href="<?= base_url('ecommerce/orders') ?>" class="btn btn-outline-primary me-2">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
                <a href="<?= base_url('ecommerce') ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enhanced real-time order tracking updates
        let refreshInterval;
        let lastStatus = '<?= $order['status'] ?>';
        let updateCount = 0;
        
        function startAutoRefresh() {
            // Only refresh if order is not completed
            const currentStatus = '<?= $order['status'] ?>';
            if (!['delivered', 'cancelled'].includes(currentStatus)) {
                // More frequent updates for better real-time experience
                refreshInterval = setInterval(() => {
                    checkForUpdates();
                }, 10000); // Check every 10 seconds
            }
        }
        
        function stopAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        }
        
        // Check for order status updates without full page reload
        function checkForUpdates() {
            fetch(`/api/order-status/<?= $order['id'] ?>`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.status !== lastStatus) {
                    // Status has changed, reload the page to show updates
                    showStatusUpdateNotification(data.status, lastStatus);
                    setTimeout(() => {
                        location.reload();
                    }, 2000); // Reload after showing notification
                }
            })
            .catch(error => {
                console.log('Status check failed, will retry...');
            });
        }
        
        // Show notification when status changes
        function showStatusUpdateNotification(newStatus, oldStatus) {
            const statusMessages = {
                'confirmed': 'Order Confirmed!',
                'processing': 'Pet Preparation Started!',
                'shipped': 'Order Ready for Pickup/Delivery!',
                'delivered': 'Order Delivered Successfully!'
            };
            
            const notification = document.createElement('div');
            notification.innerHTML = `
                <div style="display: flex; align-items: center;">
                    <i class="fas fa-bell" style="margin-right: 10px; color: #ff6b35;"></i>
                    <div>
                        <strong>Status Update!</strong><br>
                        ${statusMessages[newStatus] || 'Order status updated'}
                    </div>
                </div>
            `;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #ff6b35, #f7931e);
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                font-size: 14px;
                z-index: 1000;
                box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                max-width: 300px;
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
        
        // Show live indicator
        function showLiveIndicator() {
            const indicator = document.createElement('div');
            indicator.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> Live Tracking';
            indicator.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #28a745;
                color: white;
                padding: 8px 15px;
                border-radius: 20px;
                font-size: 12px;
                z-index: 1000;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            `;
            document.body.appendChild(indicator);
            
            // Hide after 3 seconds
            setTimeout(() => {
                if (indicator.parentNode) {
                    indicator.parentNode.removeChild(indicator);
                }
            }, 3000);
        }
        
        // Start auto-refresh when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startAutoRefresh();
            showLiveIndicator();
            
            // Stop auto-refresh when user leaves the page
            window.addEventListener('beforeunload', stopAutoRefresh);
        });
    </script>
</body>
</html>
