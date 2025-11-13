<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            background: var(--black);
            min-height: 100vh;
        }

        .navbar {
            background: var(--black);
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            border-bottom: 2px solid var(--primary-color);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }

        .success-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 50px;
            margin: 50px auto;
            max-width: 600px;
            text-align: center;
            border-top: 4px solid var(--primary-color);
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: var(--success-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: pulse 2s infinite;
        }

        .success-icon i {
            font-size: 3rem;
            color: white;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
        }

        .success-title {
            color: var(--success-color);
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .success-message {
            color: #6c757d;
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .order-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--black);
        }

        .detail-value {
            color: #6c757d;
        }

        .order-number {
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .status-badge {
            background: #fff3cd;
            color: #856404;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            border-radius: 25px;
            padding: 15px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 10px;
            min-width: 200px;
        }

        .btn-primary:hover {
            background-color: var(--dark-orange);
            border-color: var(--dark-orange);
            color: white;
            transform: translateY(-2px);
        }

        /* Navbar Links */
        .navbar-nav .nav-link {
            color: white !important;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .btn-outline-light {
            border-color: white;
            color: white;
        }

        .btn-outline-light:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            margin: 10px;
            min-width: 200px;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .next-steps {
            background: #e8f5e8;
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            text-align: left;
        }

        .next-steps h5 {
            color: var(--success-color);
            margin-bottom: 20px;
        }

        .next-steps ul {
            list-style: none;
            padding: 0;
        }

        .next-steps li {
            padding: 10px 0;
            border-bottom: 1px solid #d4edda;
        }

        .next-steps li:last-child {
            border-bottom: none;
        }

        .next-steps li i {
            color: var(--success-color);
            margin-right: 10px;
            width: 20px;
        }

        .confetti {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
        }

        @media (max-width: 768px) {
            .success-container {
                margin: 20px;
                padding: 30px 20px;
            }
            
            .success-title {
                font-size: 2rem;
            }
            
            .btn-primary, .btn-outline-primary {
                width: 100%;
                margin: 5px 0;
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

    <!-- Success Container -->
    <div class="container">
        <div class="success-container">
            <!-- Success Icon -->
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <!-- Success Message -->
            <h1 class="success-title">Order Placed Successfully!</h1>
            <p class="success-message">
                Thank you for your order! We've received your request and will process it shortly. 
                You'll receive updates about your order status via email.
            </p>
            
            <!-- Order Details -->
            <div class="order-details">
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-receipt me-2"></i>Order Number
                    </span>
                    <span class="order-number">#<?= esc($order['order_number']) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-calendar me-2"></i>Order Date
                    </span>
                    <span class="detail-value"><?= 
                        !empty($order['created_at']) && $order['created_at'] !== '0000-00-00 00:00:00' 
                            ? date('M d, Y - g:i A', strtotime($order['created_at'])) 
                            : date('M d, Y - g:i A') 
                    ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-money-bill-wave me-2"></i>Total Amount
                    </span>
                    <span class="detail-value">₱<?= number_format($order['total_amount'], 2) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-truck me-2"></i>Delivery Method
                    </span>
                    <span class="detail-value">
                        <?= $order['delivery_type'] === 'pickup' ? 'Store Pickup' : 'Home Delivery' ?>
                        <?php if ($order['delivery_type'] === 'delivery'): ?>
                            <br><small class="text-muted"><?= esc($order['delivery_address']) ?></small>
                        <?php endif; ?>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-credit-card me-2"></i>Payment Method
                    </span>
                    <span class="detail-value">
                        <?php
                        $paymentMethods = [
                            'cod' => 'Cash on Delivery',
                            'gcash' => 'GCash',
                            'bank_transfer' => 'Bank Transfer'
                        ];
                        echo $paymentMethods[$order['payment_method']] ?? ucfirst($order['payment_method']);
                        ?>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-info-circle me-2"></i>Status
                    </span>
                    <span class="status-badge">Pending Confirmation</span>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="text-center">
                <a href="/order/<?= $order['id'] ?>" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>View Order Details
                </a>
                <a href="/my-orders" class="btn btn-outline-primary">
                    <i class="fas fa-list me-2"></i>My Orders
                </a>
            </div>
            
            <div class="text-center mt-3">
                <a href="/shop" class="btn btn-outline-primary">
                    <i class="fas fa-store me-2"></i>Continue Shopping
                </a>
            </div>
            
            <!-- Next Steps -->
            <div class="next-steps">
                <h5>
                    <i class="fas fa-tasks me-2"></i>What happens next?
                </h5>
                <ul>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <strong>Order Confirmation:</strong> We'll review and confirm your order within 24 hours
                    </li>
                    <li>
                        <i class="fas fa-paw"></i>
                        <strong>Pet Preparation:</strong> We'll prepare your new companion with health checks and documentation
                    </li>
                    <li>
                        <i class="fas fa-truck"></i>
                        <strong>Delivery/Pickup:</strong> 
                        <?= $order['delivery_type'] === 'pickup' ? 'We\'ll notify you when ready for pickup' : 'We\'ll arrange delivery to your address' ?>
                    </li>
                    <li>
                        <i class="fas fa-heart"></i>
                        <strong>Welcome Home:</strong> Enjoy life with your new furry friend!
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Confetti Animation -->
    <canvas class="confetti" id="confetti"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Confetti Animation
        (function() {
            const canvas = document.getElementById('confetti');
            const ctx = canvas.getContext('2d');
            
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            
            const confettiPieces = [];
            const colors = ['#ff6b35', '#f7931e', '#28a745', '#007bff', '#6f42c1', '#e83e8c'];
            
            class ConfettiPiece {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = -10;
                    this.width = Math.random() * 10 + 5;
                    this.height = Math.random() * 10 + 5;
                    this.color = colors[Math.floor(Math.random() * colors.length)];
                    this.speed = Math.random() * 3 + 2;
                    this.rotation = Math.random() * 360;
                    this.rotationSpeed = Math.random() * 5 - 2.5;
                }
                
                update() {
                    this.y += this.speed;
                    this.rotation += this.rotationSpeed;
                    
                    if (this.y > canvas.height) {
                        this.y = -10;
                        this.x = Math.random() * canvas.width;
                    }
                }
                
                draw() {
                    ctx.save();
                    ctx.translate(this.x + this.width / 2, this.y + this.height / 2);
                    ctx.rotate(this.rotation * Math.PI / 180);
                    ctx.fillStyle = this.color;
                    ctx.fillRect(-this.width / 2, -this.height / 2, this.width, this.height);
                    ctx.restore();
                }
            }
            
            // Create confetti pieces
            for (let i = 0; i < 100; i++) {
                confettiPieces.push(new ConfettiPiece());
            }
            
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                confettiPieces.forEach(piece => {
                    piece.update();
                    piece.draw();
                });
                
                requestAnimationFrame(animate);
            }
            
            animate();
            
            // Stop confetti after 10 seconds
            setTimeout(() => {
                canvas.style.display = 'none';
            }, 10000);
            
            // Resize canvas on window resize
            window.addEventListener('resize', () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            });
        })();

        // Auto-redirect to orders page after 30 seconds
        setTimeout(() => {
            Swal.fire({
                title: 'View Orders?',
                text: 'Would you like to view your orders page?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ff6b35',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, view orders!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/my-orders';
                }
            });
        }, 30000);
    </script>
</body>
</html>
