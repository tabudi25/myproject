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
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            height: fit-content;
            position: sticky;
            top: 20px;
            border: 1px solid #e9ecef;
        }

        .filter-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .filter-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .filter-title {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--accent-color);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-title i {
            color: var(--primary-color);
            margin-right: 8px;
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
            border: 2px solid #e9ecef;
            background: white;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 10px;
            width: 100%;
            text-align: left;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #495057;
            font-size: 0.95rem;
        }

        .category-filter:hover {
            background: #f8f9fa;
            border-color: var(--primary-color);
            color: var(--accent-color);
            transform: translateX(5px);
        }

        .category-filter.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .category-filter i {
            font-size: 1.1rem;
        }

        .filter-badge {
            background: rgba(0,0,0,0.1);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .filter-checkbox {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .filter-option {
            display: flex;
            align-items: center;
            padding: 10px 0;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-option:hover {
            color: var(--primary-color);
        }

        .filter-option label {
            cursor: pointer;
            margin-bottom: 0;
            flex: 1;
        }

        .price-range-inputs {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .price-input {
            width: 100%;
            padding: 8px 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .price-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .mobile-filter-toggle {
            display: none;
        }

        @media (max-width: 991px) {
            .mobile-filter-toggle {
                display: block;
                margin-bottom: 20px;
            }
            
            .filter-sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                height: 100vh;
                z-index: 9999;
                transition: left 0.3s ease;
                overflow-y: auto;
                max-width: 300px;
                width: 100%;
            }

            .filter-sidebar.show {
                left: 0;
            }

            .filter-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 9998;
                display: none;
            }

            .filter-overlay.show {
                display: block;
            }
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

        .clear-filters-btn {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            color: #6c757d;
            border-radius: 10px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .clear-filters-btn:hover {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .filter-header h5 {
            margin: 0;
            font-weight: 700;
            color: var(--accent-color);
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
                <!-- Mobile Filter Toggle -->
                <div class="mobile-filter-toggle">
                    <button class="btn btn-primary w-100" onclick="toggleFilters()">
                        <i class="fas fa-filter me-2"></i>Show Filters
                    </button>
                </div>

                <!-- Filter Overlay for Mobile -->
                <div class="filter-overlay" onclick="toggleFilters()"></div>

                <div class="filter-sidebar" id="filterSidebar">
                    <!-- Filter Header -->
                    <div class="filter-header">
                        <h5>
                            <i class="fas fa-sliders-h me-2"></i>Filters
                        </h5>
                        <button class="btn-close d-lg-none" onclick="toggleFilters()"></button>
                    </div>
                    
                    <!-- Categories Section -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="fas fa-tags"></i>Categories
                        </div>
                        <a href="/shop" class="category-filter <?= !$currentCategory ? 'active' : '' ?>">
                            <span>
                                <i class="fas fa-th-large me-2"></i>All Categories
                            </span>
                        </a>
                        <?php foreach ($categories as $category): ?>
                            <a href="/shop/<?= $category['id'] ?>" class="category-filter <?= $currentCategory && $currentCategory['id'] == $category['id'] ? 'active' : '' ?>">
                                <span>
                                    <i class="fas fa-paw me-2"></i><?= esc($category['name']) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- Price Range Section -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="fas fa-dollar-sign"></i>Price Range
                        </div>
                        <form method="GET" action="/shop" id="priceFilterForm">
                            <?php if ($currentCategory): ?>
                                <input type="hidden" name="category" value="<?= $currentCategory['id'] ?>">
                            <?php endif; ?>
                            <?php if ($search): ?>
                                <input type="hidden" name="search" value="<?= esc($search) ?>">
                            <?php endif; ?>
                            <div class="price-range-inputs">
                                <input type="number" name="min_price" class="price-input" placeholder="Min" min="0" step="100" value="<?= $_GET['min_price'] ?? '' ?>">
                                <span>-</span>
                                <input type="number" name="max_price" class="price-input" placeholder="Max" min="0" step="100" value="<?= $_GET['max_price'] ?? '' ?>">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-2">
                                <i class="fas fa-check me-2"></i>Apply
                            </button>
                        </form>
                    </div>

                    <!-- Gender Filter Section -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="fas fa-venus-mars"></i>Gender
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" class="filter-checkbox" id="genderMale" onchange="filterByGender('male', this.checked)">
                            <label for="genderMale">
                                <i class="fas fa-mars text-primary me-2"></i>Male
                            </label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" class="filter-checkbox" id="genderFemale" onchange="filterByGender('female', this.checked)">
                            <label for="genderFemale">
                                <i class="fas fa-venus text-danger me-2"></i>Female
                            </label>
                        </div>
                    </div>

                    <!-- Age Range Section -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="fas fa-birthday-cake"></i>Age
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" class="filter-checkbox" id="ageYoung" onchange="filterByAge('0-6', this.checked)">
                            <label for="ageYoung">0-6 months (Puppy/Kitten)</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" class="filter-checkbox" id="ageAdult" onchange="filterByAge('7-24', this.checked)">
                            <label for="ageAdult">7-24 months (Young)</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" class="filter-checkbox" id="ageMature" onchange="filterByAge('25+', this.checked)">
                            <label for="ageMature">25+ months (Adult)</label>
                        </div>
                    </div>
                    
                    <!-- Clear All Filters -->
                    <?php if ($search || $currentCategory || isset($_GET['min_price']) || isset($_GET['max_price'])): ?>
                        <a href="/shop" class="btn clear-filters-btn w-100">
                            <i class="fas fa-times-circle me-2"></i>Clear All Filters
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
                                                <?php if ($animal['status'] === 'available'): ?>
                                                    <?php if ($isLoggedIn): ?>
                                                        <button onclick="addToCart(<?= $animal['id'] ?>)" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-cart-plus"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <a href="/login" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-sign-in-alt"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Sold</span>
                                                <?php endif; ?>
                                            </div>
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
        // Toggle mobile filters
        function toggleFilters() {
            const sidebar = document.getElementById('filterSidebar');
            const overlay = document.querySelector('.filter-overlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        // Filter by gender
        function filterByGender(gender, checked) {
            const urlParams = new URLSearchParams(window.location.search);
            let genders = urlParams.getAll('gender[]');
            
            if (checked) {
                genders.push(gender);
            } else {
                genders = genders.filter(g => g !== gender);
            }
            
            // Clear existing gender params
            urlParams.delete('gender[]');
            
            // Add selected genders
            genders.forEach(g => urlParams.append('gender[]', g));
            
            // Redirect with new params
            window.location.search = urlParams.toString();
        }

        // Filter by age range
        function filterByAge(ageRange, checked) {
            const urlParams = new URLSearchParams(window.location.search);
            let ages = urlParams.getAll('age[]');
            
            if (checked) {
                ages.push(ageRange);
            } else {
                ages = ages.filter(a => a !== ageRange);
            }
            
            // Clear existing age params
            urlParams.delete('age[]');
            
            // Add selected age ranges
            ages.forEach(a => urlParams.append('age[]', a));
            
            // Redirect with new params
            window.location.search = urlParams.toString();
        }

        // Preserve filter checkboxes on page load
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            
            // Check gender filters
            const genders = urlParams.getAll('gender[]');
            genders.forEach(gender => {
                const checkbox = document.getElementById('gender' + gender.charAt(0).toUpperCase() + gender.slice(1));
                if (checkbox) checkbox.checked = true;
            });
            
            // Check age filters
            const ages = urlParams.getAll('age[]');
            ages.forEach(age => {
                let id = '';
                if (age === '0-6') id = 'ageYoung';
                else if (age === '7-24') id = 'ageAdult';
                else if (age === '25+') id = 'ageMature';
                
                const checkbox = document.getElementById(id);
                if (checkbox) checkbox.checked = true;
            });
        });

        // Add to cart functionality
        function addToCart(animalId) {
            // Check if user is logged in
            <?php if (!$isLoggedIn): ?>
                alert('Please login to add items to cart');
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
                    alert('✅ ' + data.message);
                    window.location.href = '/cart';
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ An error occurred. Please try again.');
            });
        }

        function showAlert(type, message) {
            // Create a simple alert using browser's alert for now
            if (type === 'success') {
                alert('✅ ' + message);
            } else {
                alert('❌ ' + message);
            }
        }
    </script>
</body>
</html>
