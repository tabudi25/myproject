<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
            --accent-color: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .checkout-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .order-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            position: sticky;
            top: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-item:last-child {
            border-bottom: 2px solid var(--primary-color);
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-details {
            flex-grow: 1;
            margin-left: 15px;
        }

        .item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .item-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .section-title {
            color: var(--accent-color);
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 15px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            margin-bottom: 15px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
        }

        .delivery-option {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .delivery-option:hover {
            border-color: var(--primary-color);
            background: #fff5f2;
        }

        .delivery-option.selected {
            border-color: var(--primary-color);
            background: #fff5f2;
        }

        .delivery-option input[type="radio"] {
            margin-right: 15px;
        }

        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: var(--primary-color);
            background: #fff5f2;
        }

        .payment-method.selected {
            border-color: var(--primary-color);
            background: #fff5f2;
        }

        .payment-method input[type="radio"] {
            margin-right: 15px;
        }

        .breadcrumb {
            background: none;
            padding: 20px 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: var(--primary-color);
        }

        .security-info {
            background: #e8f5e8;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            color: #155724;
            margin-top: 20px;
        }

        .progress-steps {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .step {
            display: flex;
            align-items: center;
            margin: 0 20px;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }

        .step-inactive .step-number {
            background: #dee2e6;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .checkout-container {
                padding: 20px;
                margin-top: 20px;
            }
            
            .order-summary {
                margin-top: 30px;
                position: relative;
                top: auto;
            }
            
            .progress-steps {
                flex-direction: column;
                align-items: center;
            }
            
            .step {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-paw me-2"></i>Fluffy Planet
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/shop">
                            <i class="fas fa-store me-1"></i>Shop
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/cart">
                            <i class="fas fa-shopping-cart me-1"></i>Cart
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/my-orders">
                            <i class="fas fa-box me-1"></i>Orders
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?= esc($userName) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if ($userRole === 'admin'): ?>
                                <li><a class="dropdown-item" href="/fluffy-admin">
                                    <i class="fas fa-cog me-2"></i>Admin Panel
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/shop">Shop</a></li>
                <li class="breadcrumb-item"><a href="/cart">Cart</a></li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </nav>
    </div>

    <!-- Progress Steps -->
    <div class="container">
        <div class="progress-steps">
            <div class="step step-inactive">
                <div class="step-number">1</div>
                <span>Cart</span>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <span>Checkout</span>
            </div>
            <div class="step step-inactive">
                <div class="step-number">3</div>
                <span>Payment</span>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('msg')): ?>
        <div class="container">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('msg') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <div class="checkout-container">
                    <h2 class="mb-4">
                        <i class="fas fa-credit-card me-2 text-primary"></i>
                        Checkout
                    </h2>
                    
                    <form method="POST" action="/process-checkout" id="checkoutForm">
                        <!-- Delivery Information -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-truck"></i>
                                Delivery Information
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="delivery-option" onclick="selectDeliveryType('pickup')">
                                        <input type="radio" name="delivery_type" value="pickup" id="pickup" checked>
                                        <label for="pickup" class="mb-0">
                                            <strong><i class="fas fa-store me-2"></i>Store Pickup</strong><br>
                                            <small class="text-muted">Pick up from our store - FREE</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="delivery-option" onclick="selectDeliveryType('delivery')">
                                        <input type="radio" name="delivery_type" value="delivery" id="delivery">
                                        <label for="delivery" class="mb-0">
                                            <strong><i class="fas fa-shipping-fast me-2"></i>Home Delivery</strong><br>
                                            <small class="text-muted">Delivered to your address - ₱<?= number_format($deliveryFee, 2) ?></small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="deliveryAddressSection" style="display: none;">
                                <div class="mt-3">
                                    <label for="delivery_address" class="form-label">Delivery Address *</label>
                                    <textarea name="delivery_address" id="delivery_address" class="form-control" rows="3" placeholder="Enter your complete delivery address..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-credit-card"></i>
                                Payment Method
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="payment-method" onclick="selectPaymentMethod('cod')">
                                        <input type="radio" name="payment_method" value="cod" id="cod" checked>
                                        <label for="cod" class="mb-0">
                                            <strong><i class="fas fa-money-bill-wave me-2"></i>Cash on Delivery</strong><br>
                                            <small class="text-muted">Pay when you receive</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-method" onclick="selectPaymentMethod('gcash')">
                                        <input type="radio" name="payment_method" value="gcash" id="gcash">
                                        <label for="gcash" class="mb-0">
                                            <strong><i class="fas fa-mobile-alt me-2"></i>GCash</strong><br>
                                            <small class="text-muted">Mobile payment</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-method" onclick="selectPaymentMethod('bank_transfer')">
                                        <input type="radio" name="payment_method" value="bank_transfer" id="bank_transfer">
                                        <label for="bank_transfer" class="mb-0">
                                            <strong><i class="fas fa-university me-2"></i>Bank Transfer</strong><br>
                                            <small class="text-muted">Direct bank payment</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-sticky-note"></i>
                                Additional Notes (Optional)
                            </h4>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Any special instructions or notes for your order..."></textarea>
                        </div>

                        <!-- Order Actions -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="placeOrderBtn">
                                <i class="fas fa-check me-2"></i>Place Order
                            </button>
                            <a href="/cart" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Cart
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h4 class="mb-4">
                        <i class="fas fa-receipt me-2 text-primary"></i>Order Summary
                    </h4>
                    
                    <!-- Cart Items -->
                    <?php foreach ($cartItems as $item): ?>
                        <div class="summary-item">
                            <img src="/uploads/<?= $item['image'] ?>" alt="<?= esc($item['name']) ?>" class="item-image" onerror="this.src='/web/default-pet.jpg'">
                            <div class="item-details">
                                <div class="item-name"><?= esc($item['name']) ?></div>
                                <div class="item-meta">Qty: <?= $item['quantity'] ?></div>
                            </div>
                            <div class="text-end">
                                <strong>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Totals -->
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>₱<?= number_format($subtotal, 2) ?></span>
                    </div>
                    
                    <div class="summary-item">
                        <span>Delivery Fee</span>
                        <span id="deliveryFeeDisplay">FREE</span>
                    </div>
                    
                    <div class="summary-item">
                        <span>Total</span>
                        <span id="totalDisplay">₱<?= number_format($subtotal, 2) ?></span>
                    </div>
                    
                    <!-- Security Info -->
                    <div class="security-info">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>Secure Checkout</strong><br>
                        <small>Your information is protected with SSL encryption</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const subtotal = <?= $subtotal ?>;
        const deliveryFee = <?= $deliveryFee ?>;

        function selectDeliveryType(type) {
            // Update radio button
            document.getElementById(type).checked = true;
            
            // Update visual selection
            document.querySelectorAll('.delivery-option').forEach(option => {
                option.classList.remove('selected');
            });
            document.querySelector(`#${type}`).closest('.delivery-option').classList.add('selected');
            
            // Show/hide delivery address
            const addressSection = document.getElementById('deliveryAddressSection');
            const addressInput = document.getElementById('delivery_address');
            
            if (type === 'delivery') {
                addressSection.style.display = 'block';
                addressInput.required = true;
            } else {
                addressSection.style.display = 'none';
                addressInput.required = false;
                addressInput.value = '';
            }
            
            // Update totals
            updateTotals();
        }

        function selectPaymentMethod(method) {
            // Update radio button
            document.getElementById(method).checked = true;
            
            // Update visual selection
            document.querySelectorAll('.payment-method').forEach(option => {
                option.classList.remove('selected');
            });
            document.querySelector(`#${method}`).closest('.payment-method').classList.add('selected');
        }

        function updateTotals() {
            const deliveryType = document.querySelector('input[name="delivery_type"]:checked').value;
            const currentDeliveryFee = deliveryType === 'delivery' ? deliveryFee : 0;
            const total = subtotal + currentDeliveryFee;
            
            document.getElementById('deliveryFeeDisplay').textContent = 
                currentDeliveryFee > 0 ? '₱' + currentDeliveryFee.toLocaleString('en-PH', {minimumFractionDigits: 2}) : 'FREE';
            document.getElementById('totalDisplay').textContent = 
                '₱' + total.toLocaleString('en-PH', {minimumFractionDigits: 2});
        }

        // Form validation
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const deliveryType = document.querySelector('input[name="delivery_type"]:checked').value;
            const deliveryAddress = document.getElementById('delivery_address').value.trim();
            
            if (deliveryType === 'delivery' && !deliveryAddress) {
                e.preventDefault();
                alert('Please enter your delivery address.');
                document.getElementById('delivery_address').focus();
                return false;
            }
            
            // Disable submit button to prevent double submission
            const submitBtn = document.getElementById('placeOrderBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial selection styles
            selectDeliveryType('pickup');
            selectPaymentMethod('cod');
        });
    </script>
</body>
</html>
