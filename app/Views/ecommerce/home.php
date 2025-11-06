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
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
            --accent-color: #2c3e50;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .search-bar {
            max-width: 500px;
            margin: 0 auto;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }


        .hero-title {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }


        .category-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            height: 100%;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            text-decoration: none;
            color: inherit;
        }

        .category-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .animal-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .animal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .animal-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .animal-info {
            padding: 20px;
        }

        .animal-name {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .animal-price {
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .section-title {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 3rem;
            color: var(--accent-color);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stats-section {
            background: var(--light-bg);
            padding: 60px 0;
        }

        .stat-card {
            text-align: center;
            padding: 30px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 1.1rem;
            color: var(--accent-color);
            margin-top: 10px;
        }

        .footer {
            background: var(--accent-color);
            color: white;
            padding: 40px 0;
            margin-top: 60px;
        }

        /* Notification Section Styles */
        .notification-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
        }

        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item.unread {
            background-color: #e3f2fd;
            border-left: 4px solid var(--primary-color);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-right: 15px;
        }

        .notification-icon.delivery-ready {
            background-color: #4caf50;
            color: white;
        }

        .notification-icon.delivery-confirmed {
            background-color: #2196f3;
            color: white;
        }

        .notification-icon.delivery-rejected {
            background-color: #f44336;
            color: white;
        }

        .notification-icon.order-status {
            background-color: #ff9800;
            color: white;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #666;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #ff6b35;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .search-bar {
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
                <!-- Search Bar -->
                <div class="mx-auto search-bar">
                    <form action="/shop" method="GET" class="d-flex">
                        <input class="form-control me-2" type="search" name="search" placeholder="Search for pets..." aria-label="Search">
                        <button class="btn btn-light" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/shop">
                            <i class="fas fa-store me-1"></i>Shop
                        </a>
                    </li>
                    
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="/cart">
                                <i class="fas fa-shopping-cart me-1"></i>Cart
                                <?php if ($cartCount > 0): ?>
                                    <span class="cart-badge"><?= $cartCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/my-orders">
                                <i class="fas fa-box me-1"></i>Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="/notifications">
                                <i class="fas fa-bell me-1"></i>Notifications
                                <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
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
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/signup">
                                <i class="fas fa-user-plus me-1"></i>Sign Up
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('msg')): ?>
        <div class="container mt-3">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('msg') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Find Your Perfect Companion</h1>
            <p class="hero-subtitle">Discover loving pets waiting for their forever homes</p>
            <a href="/shop" class="btn btn-light btn-lg">
                <i class="fas fa-paw me-2"></i>Start Shopping
            </a>
        </div>
    </section>

    <!-- Notifications Section (for logged in users) -->

    <!-- Categories Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">Shop by Category</h2>
            <div class="row g-4">
                <?php 
                $categoryIcons = [
                    'Dogs' => 'fas fa-dog',
                    'Cats' => 'fas fa-cat',
                    'Birds' => 'fas fa-dove',
                    'Fish' => 'fas fa-fish',
                    'Small Pets' => 'fas fa-rabbit-fast'
                ];
                ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="/shop/<?= $category['id'] ?>" class="category-card d-block">
                            <div class="category-icon">
                                <i class="<?= $categoryIcons[$category['name']] ?? 'fas fa-paw' ?>"></i>
                            </div>
                            <h5><?= esc($category['name']) ?></h5>
                            <p class="text-muted mb-0"><?= isset($category['description']) ? esc($category['description']) : '' ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Animals Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Featured Pets</h2>
            <div class="row g-4">
                <?php foreach ($featuredAnimals as $animal): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="animal-card">
                            <img src="/uploads/<?= $animal['image'] ?>" alt="<?= esc($animal['name']) ?>" class="animal-image" onerror="this.src='/web/default-pet.jpg'">
                            <div class="animal-info">
                                <div class="animal-name"><?= esc($animal['name']) ?></div>
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-tag me-1"></i><?= esc($animal['category_name']) ?>
                                    <span class="ms-2">
                                        <i class="fas fa-birthday-cake me-1"></i><?= $animal['age'] ?> months
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="animal-price">₱<?= number_format($animal['price'], 2) ?></div>
                                    <a href="/animal/<?= $animal['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                </div>
                                <?php if ($animal['status'] === 'sold'): ?>
                                    <div class="mt-2">
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Sold
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="/shop" class="btn btn-primary btn-lg">
                    <i class="fas fa-store me-2"></i>View All Pets
                </a>
            </div>
        </div>
    </section>

    <!-- New Arrivals Section -->
    <?php if (!empty($newArrivals)): ?>
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">New Arrivals</h2>
            <div class="row g-4">
                <?php foreach ($newArrivals as $animal): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="animal-card">
                            <div class="position-relative">
                                <img src="/uploads/<?= $animal['image'] ?>" alt="<?= esc($animal['name']) ?>" class="animal-image" onerror="this.src='/web/default-pet.jpg'">
                                <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                            </div>
                            <div class="animal-info">
                                <div class="animal-name"><?= esc($animal['name']) ?></div>
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-tag me-1"></i><?= esc($animal['category_name']) ?>
                                    <span class="ms-2">
                                        <i class="fas fa-birthday-cake me-1"></i><?= $animal['age'] ?> months
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="animal-price">₱<?= number_format($animal['price'], 2) ?></div>
                                    <a href="/animal/<?= $animal['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                </div>
                                <?php if ($animal['status'] === 'sold'): ?>
                                    <div class="mt-2">
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Sold
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Happy Pets</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Families Served</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">5</div>
                        <div class="stat-label">Years Experience</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-paw me-2"></i>Fluffy Planet</h5>
                    <p>Your trusted partner in finding the perfect pet companion. We ensure all our animals are healthy, happy, and ready for their forever homes.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/shop" class="text-white-50">Shop All Pets</a></li>
                        <li><a href="/shop/1" class="text-white-50">Dogs</a></li>
                        <li><a href="/shop/2" class="text-white-50">Cats</a></li>
                        <li><a href="/shop/3" class="text-white-50">Birds</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <p class="text-white-50">
                        <i class="fas fa-phone me-2"></i>+63 123 456 7890<br>
                        <i class="fas fa-envelope me-2"></i>info@fluffyplanet.com<br>
                        <i class="fas fa-map-marker-alt me-2"></i>Manila, Philippines
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Fluffy Planet. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add to cart functionality
        function addToCart(animalId) {
            <?php if (!$isLoggedIn): ?>
                Swal.fire({icon: 'warning', title: 'Login Required', text: 'Please login to add items to cart'});
                window.location.href = '/login';
                return;
            <?php endif; ?>
            
            fetch('/add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'animal_id=' + animalId + '&quantity=1'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({icon: 'success', title: 'Success!', text: data.message});
                    window.location.href = '/cart';
                } else {
                    Swal.fire({icon: 'error', title: 'Error', text: data.message});
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({icon: 'error', title: 'Error', text: 'An error occurred. Please try again.'});
            });
        }

        // Load recent notifications for homepage
        function loadRecentNotifications() {
            fetch('/api/notifications/recent', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('recentNotifications');
                if (data.success && data.notifications.length > 0) {
                    let html = '';
                    data.notifications.forEach(notification => {
                        const icons = {
                            'delivery_ready': 'fas fa-truck',
                            'delivery_confirmed': 'fas fa-check-circle',
                            'delivery_rejected': 'fas fa-exclamation-triangle',
                            'order_status': 'fas fa-info-circle'
                        };
                        const icon = icons[notification.type] || 'fas fa-bell';
                        
                        html += `
                            <div class="notification-item ${notification.is_read ? '' : 'unread'}" onclick="markAsRead(${notification.id})">
                                <div class="d-flex align-items-start">
                                    <div class="notification-icon ${notification.type}">
                                        <i class="${icon}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">${notification.title}</h6>
                                        <p class="mb-2">${notification.message}</p>
                                        <small class="notification-time">
                                            <i class="fas fa-clock me-1"></i>
                                            ${formatTime(notification.created_at)}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-bell-slash fa-2x mb-3"></i>
                            <p class="mb-0">No notifications yet</p>
                            <small>You'll receive notifications about your orders and deliveries here.</small>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                document.getElementById('recentNotifications').innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <p class="mb-0">Unable to load notifications</p>
                        <small>Please try refreshing the page.</small>
                    </div>
                `;
            });
        }

        // Mark notification as read
        function markAsRead(notificationId) {
            fetch(`/notifications/mark-read/${notificationId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove unread styling
                    const notification = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                    if (notification) {
                        notification.classList.remove('unread');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Format time for display
        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(diff / 3600000);
            const days = Math.floor(diff / 86400000);
            
            if (minutes < 1) return 'Just now';
            if (minutes < 60) return `${minutes}m ago`;
            if (hours < 24) return `${hours}h ago`;
            if (days < 7) return `${days}d ago`;
            
            return date.toLocaleDateString();
        }

        // Real-time notification updates
        function updateNotificationBadge() {
            fetch('/api/notifications/unread-count', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('notificationBadge');
                    if (badge) {
                        if (data.unread_count > 0) {
                            badge.textContent = data.unread_count;
                            badge.style.display = 'inline';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                }
            })
            .catch(error => console.error('Error updating notification badge:', error));
        }

        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($isLoggedIn): ?>
                loadRecentNotifications();
                updateNotificationBadge();
                
                // Update notifications every 30 seconds
                setInterval(loadRecentNotifications, 30000);
                setInterval(updateNotificationBadge, 30000);
            <?php endif; ?>
        });
    </script>
    <script src="/js/realtime.js"></script>
</body>
</html>