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

        .product-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .product-info {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .product-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--accent-color);
            margin-bottom: 15px;
        }

        .product-price {
            font-size: 2rem;
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 20px;
        }

        .product-details {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--accent-color);
        }

        .detail-value {
            color: #6c757d;
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

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
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

        .related-animals {
            margin-top: 60px;
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

        .breadcrumb {
            background: none;
            padding: 20px 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: var(--primary-color);
        }

        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-available {
            background: #d4edda;
            color: #155724;
        }

        .status-sold {
            background: #f8d7da;
            color: #721c24;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .quantity-input {
            width: 60px;
            height: 40px;
            border: 1px solid #ddd;
            border-left: none;
            border-right: none;
            text-align: center;
            background: white;
        }

        @media (max-width: 768px) {
            .product-title {
                font-size: 2rem;
            }
            
            .product-info {
                margin-top: 20px;
                position: relative;
                top: auto;
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

    <!-- Breadcrumb -->
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/shop">Shop</a></li>
                <?php if ($animal['category_name']): ?>
                    <li class="breadcrumb-item"><a href="/shop/<?= $animal['category_id'] ?>"><?= esc($animal['category_name']) ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active"><?= esc($animal['name']) ?></li>
            </ol>
        </nav>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('msg')): ?>
        <div class="container">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('msg') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Product Details -->
    <div class="container">
        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-6">
                <img src="/uploads/<?= $animal['image'] ?>" alt="<?= esc($animal['name']) ?>" class="product-image" onerror="this.src='/web/default-pet.jpg'">
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <h1 class="product-title"><?= esc($animal['name']) ?></h1>
                    
                    <div class="mb-3">
                        <span class="status-badge status-<?= $animal['status'] ?>">
                            <?= ucfirst($animal['status']) ?>
                        </span>
                    </div>
                    
                    <div class="product-price">₱<?= number_format($animal['price'], 2) ?></div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted">Category</h6>
                        <p class="mb-0">
                            <i class="fas fa-tag me-2 text-primary"></i><?= esc($animal['category_name']) ?>
                        </p>
                    </div>
                    
                    <?php if ($animal['status'] === 'available'): ?>
                        <?php if ($isLoggedIn): ?>
                            <!-- Quantity Selector -->
                            <div class="mb-3">
                                <label class="form-label">Quantity</label>
                                <div class="quantity-selector">
                                    <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" class="quantity-input" value="1" min="1" max="1" readonly>
                                    <button type="button" class="quantity-btn" onclick="changeQuantity(1)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Each pet is unique - maximum quantity is 1</small>
                            </div>
                            
                            <?php if ($inCart): ?>
                                <button class="btn btn-success" disabled>
                                    <i class="fas fa-check me-2"></i>Already in Cart
                                </button>
                                <a href="/cart" class="btn btn-outline-primary">
                                    <i class="fas fa-shopping-cart me-2"></i>View Cart
                                </a>
                            <?php else: ?>
                                <button onclick="addToCart(<?= $animal['id'] ?>)" class="btn btn-primary">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="/login" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Login to Purchase
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn btn-danger" disabled>
                            <i class="fas fa-times-circle me-2"></i>Sold
                        </button>
                    <?php endif; ?>
                    
                    <a href="/shop" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="product-details">
            <h3 class="mb-4">
                <i class="fas fa-info-circle me-2 text-primary"></i>Pet Details
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-birthday-cake me-2"></i>Age
                        </span>
                        <span class="detail-value"><?= $animal['age'] ?> months old</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-<?= $animal['gender'] === 'male' ? 'mars' : 'venus' ?> me-2"></i>Gender
                        </span>
                        <span class="detail-value"><?= ucfirst($animal['gender']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-tag me-2"></i>Category
                        </span>
                        <span class="detail-value"><?= esc($animal['category_name']) ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-money-bill-wave me-2"></i>Price
                        </span>
                        <span class="detail-value">₱<?= number_format($animal['price'], 2) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-info-circle me-2"></i>Status
                        </span>
                        <span class="detail-value"><?= ucfirst($animal['status']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-calendar me-2"></i>Available Since
                        </span>
                        <span class="detail-value"><?= 
                            !empty($animal['created_at']) && $animal['created_at'] !== '0000-00-00 00:00:00' 
                                ? date('M d, Y', strtotime($animal['created_at'])) 
                                : date('M d, Y') 
                        ?></span>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($animal['description'])): ?>
                <hr class="my-4">
                <h5>
                    <i class="fas fa-file-alt me-2 text-primary"></i>Description
                </h5>
                <p class="text-muted"><?= nl2br(esc($animal['description'])) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Animals -->
    <?php if (!empty($relatedAnimals)): ?>
    <div class="container related-animals">
        <h3 class="text-center mb-5">
            <i class="fas fa-heart me-2 text-primary"></i>You Might Also Like
        </h3>
        <div class="row g-4">
            <?php foreach ($relatedAnimals as $relatedAnimal): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="animal-card">
                        <img src="/uploads/<?= $relatedAnimal['image'] ?>" alt="<?= esc($relatedAnimal['name']) ?>" class="animal-image" onerror="this.src='/web/default-pet.jpg'">
                            <div class="animal-info">
                                <div class="fw-bold mb-2"><?= esc($relatedAnimal['name']) ?></div>
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-tag me-1"></i><?= esc($relatedAnimal['category_name']) ?>
                                    <span class="ms-2">
                                        <i class="fas fa-<?= $relatedAnimal['gender'] === 'male' ? 'mars' : 'venus' ?> me-1"></i><?= ucfirst($relatedAnimal['gender']) ?>
                                    </span>
                                </div>
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-birthday-cake me-1"></i><?= $relatedAnimal['age'] ?> months old
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-primary fw-bold">₱<?= number_format($relatedAnimal['price'], 2) ?></div>
                                    <a href="/animal/<?= $relatedAnimal['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                </div>
                            </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeQuantity(delta) {
            const input = document.getElementById('quantity');
            let newValue = parseInt(input.value) + delta;
            
            // For pets, quantity is always 1 (each pet is unique)
            if (newValue < 1) newValue = 1;
            if (newValue > 1) newValue = 1;
            
            input.value = newValue;
        }

        function addToCart(animalId) {
            const quantity = document.getElementById('quantity').value;
            
            fetch('/add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'animal_id=' + animalId + '&quantity=' + quantity
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

        function showAlert(type, message) {
            // Create a simple alert using browser's alert for now
            if (type === 'success') {
                Swal.fire({icon: 'success', title: 'Success!', text: message});
            } else {
                Swal.fire({icon: 'error', title: 'Error', text: message});
            }
        }
    </script>
</body>
</html>
