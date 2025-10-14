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

        .search-bar {
            max-width: 500px;
        }

        .filter-sidebar {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
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
            height: 250px;
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
            font-size: 1.3rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
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

        .category-filter {
            border: none;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 10px;
            width: 100%;
            text-align: left;
            transition: all 0.3s ease;
        }

        .category-filter:hover, .category-filter.active {
            background: var(--primary-color);
            color: white;
        }

        .sort-dropdown {
            border-radius: 8px;
        }

        .page-header {
            background: white;
            padding: 30px 0;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .breadcrumb {
            background: none;
            padding: 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: var(--primary-color);
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .no-results i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }

        @media (max-width: 768px) {
            .filter-sidebar {
                margin-bottom: 20px;
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
                <!-- Search Bar -->
                <div class="mx-auto search-bar">
                    <form action="/shop" method="GET" class="d-flex">
                        <input class="form-control me-2" type="search" name="search" value="<?= esc($search ?? '') ?>" placeholder="Search for pets..." aria-label="Search">
                        <button class="btn btn-light" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/shop">
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

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                    <?php if ($currentCategory): ?>
                        <li class="breadcrumb-item active"><?= esc($currentCategory['name']) ?></li>
                    <?php endif; ?>
                </ol>
            </nav>
            <h1 class="h2 mb-0">
                <?php if ($currentCategory): ?>
                    <?= esc($currentCategory['name']) ?>
                <?php elseif ($search): ?>
                    Search Results for "<?= esc($search) ?>"
                <?php else: ?>
                    All Pets
                <?php endif; ?>
            </h1>
        </div>
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

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <h5 class="mb-3">
                        <i class="fas fa-filter me-2"></i>Filters
                    </h5>
                    
                    <!-- Categories -->
                    <h6 class="mb-3">Categories</h6>
                    <a href="/shop" class="category-filter <?= !$currentCategory ? 'active' : '' ?>">
                        <i class="fas fa-th-large me-2"></i>All Categories
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="/shop/<?= $category['id'] ?>" class="category-filter <?= $currentCategory && $currentCategory['id'] == $category['id'] ? 'active' : '' ?>">
                            <i class="fas fa-paw me-2"></i><?= esc($category['name']) ?>
                        </a>
                    <?php endforeach; ?>
                    
                    <!-- Clear Filters -->
                    <?php if ($search || $currentCategory): ?>
                        <hr>
                        <a href="/shop" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Sort and Results Info -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="text-muted">
                        <?php if (isset($pager)): ?>
                            Showing <?= count($animals) ?> results
                        <?php endif; ?>
                    </div>
                    
                    <form method="GET" class="d-flex align-items-center">
                        <?php if ($search): ?>
                            <input type="hidden" name="search" value="<?= esc($search) ?>">
                        <?php endif; ?>
                        <label class="me-2">Sort by:</label>
                        <select name="sort" class="form-select sort-dropdown" style="width: auto;" onchange="this.form.submit()">
                            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                            <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                            <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                            <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name A-Z</option>
                        </select>
                    </form>
                </div>
                
                <!-- Animals Grid -->
                <?php if (!empty($animals)): ?>
                    <div class="row g-4">
                        <?php foreach ($animals as $animal): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="animal-card">
                                    <img src="/uploads/<?= $animal['image'] ?>" alt="<?= esc($animal['name']) ?>" class="animal-image" onerror="this.src='/web/default-pet.jpg'">
                                    <div class="animal-info">
                                        <div class="animal-name"><?= esc($animal['name']) ?></div>
                                        <div class="text-muted small mb-2">
                                            <i class="fas fa-tag me-1"></i><?= esc($animal['category_name']) ?>
                                            <span class="ms-2">
                                                <i class="fas fa-<?= $animal['gender'] === 'male' ? 'mars' : 'venus' ?> me-1"></i><?= ucfirst($animal['gender']) ?>
                                            </span>
                                        </div>
                                        <div class="text-muted small mb-2">
                                            <i class="fas fa-birthday-cake me-1"></i><?= $animal['age'] ?> months old
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="animal-price">₱<?= number_format($animal['price'], 2) ?></div>
                                            <div>
                                                <a href="/animal/<?= $animal['id'] ?>" class="btn btn-outline-primary btn-sm me-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($isLoggedIn): ?>
                                                    <button onclick="addToCart(<?= $animal['id'] ?>)" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <a href="/login" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-sign-in-alt"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($pager)): ?>
                        <div class="d-flex justify-content-center mt-5">
                            <?= $pager->links() ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- No Results -->
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h4>No pets found</h4>
                        <p>Try adjusting your search criteria or browse all categories.</p>
                        <a href="/shop" class="btn btn-primary">
                            <i class="fas fa-th-large me-2"></i>Browse All Pets
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add to cart functionality
        function addToCart(animalId) {
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
                    // Update cart count
                    const cartBadge = document.querySelector('.cart-badge');
                    if (cartBadge) {
                        cartBadge.textContent = data.cartCount;
                    } else if (data.cartCount > 0) {
                        // Create cart badge if it doesn't exist
                        const cartLink = document.querySelector('a[href="/cart"]');
                        if (cartLink) {
                            cartLink.innerHTML += '<span class="cart-badge">' + data.cartCount + '</span>';
                        }
                    }
                    
                    // Show success message
                    showAlert('success', data.message);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred. Please try again.');
            });
        }

        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Insert at the top of the container
            const container = document.querySelector('.container');
            container.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Auto-dismiss after 3 seconds
            setTimeout(() => {
                const alert = container.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 3000);
        }
    </script>
</body>
</html>
