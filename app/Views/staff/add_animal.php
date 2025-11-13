<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Pet - Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--cream-bg);
            min-height: 100vh;
        }

        .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            border-bottom: 2px solid var(--primary-color);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .sidebar {
            background: var(--black);
            min-height: calc(100vh - 76px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            padding: 20px 0;
        }

        .sidebar-item {
            padding: 12px 20px;
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover, .sidebar-item.active {
            background: var(--sidebar-hover);
            color: var(--black);
            font-weight: 600;
        }

        .sidebar-item i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
        }

        .main-content {
            padding: 30px;
            background-color: var(--cream-bg);
            min-height: calc(100vh - 76px);
        }

        .content-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-top: 4px solid var(--primary-color);
            margin-bottom: 20px;
        }

        /* Custom Button Styles - Orange & Black Theme */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--dark-orange);
            border-color: var(--dark-orange);
            color: white;
        }

        .btn-secondary {
            background-color: var(--dark-orange);
            border-color: var(--dark-orange);
            color: white;
        }

        .btn-secondary:hover {
            background-color: var(--black);
            border-color: var(--black);
            color: white;
        }

        .btn-outline-danger {
            color: var(--dark-orange);
            border-color: var(--dark-orange);
        }

        .btn-outline-danger:hover {
            background-color: var(--dark-orange);
            border-color: var(--dark-orange);
            color: white;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            margin-top: 10px;
        }

        /* Price Selection Buttons */
        .price-option-btn {
            display: inline-block;
            padding: 12px 20px;
            margin: 5px;
            background: white;
            border: 2px solid #4DD0E1;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            min-width: 150px;
        }

        .price-option-btn:hover {
            background: #4DD0E1;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .price-option-btn.selected {
            background: #4DD0E1;
            color: white;
            border-color: #26C6DA;
            font-weight: bold;
        }

        .price-option-btn .price-label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .price-option-btn .price-value {
            display: block;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .price-options-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        #priceInput[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .sidebar {
                min-height: auto;
            }

            .content-card {
                padding: 15px;
            }
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
                <a href="/staff/animals" class="sidebar-item">
                    <i class="fas fa-paw"></i>
                    <span>Pets</span>
                </a>
                <a href="/staff/orders" class="sidebar-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Adoptions</span>
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
                        <i class="fas fa-plus-circle"></i> Add New Pet (Requires Admin Approval)
                    </h3>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Pets added by staff require admin approval before they become visible to customers.
                    </div>

                    <form id="addAnimalForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pet Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" name="category_id" id="categorySelect" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Category Prices Display -->
                        <div class="mb-3" id="categoryPricesContainer" style="display: none;">
                            <label class="form-label fw-bold">Fixed Prices for this Category (Set by Admin) <span class="text-danger">*</span></label>
                            <select class="form-select" id="priceSelect" required>
                                <option value="">Select a fixed price option</option>
                            </select>
                            <small class="text-muted">Select a price option to auto-fill the price field below</small>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="birthdate" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price (₱) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="price" id="priceInput" min="0" required readonly>
                                <small class="text-muted">Select a fixed price option above to auto-fill</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Describe the pet's characteristics, temperament, etc."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="image" accept="image/*" required id="imageInput">
                            <img id="imagePreview" class="preview-image" style="display: none;">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Submit for Approval
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset Form
                            </button>
                            <a href="/staff/animals" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Pets
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Load category prices when category is selected
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('categorySelect');
            const pricesContainer = document.getElementById('categoryPricesContainer');
            const priceSelect = document.getElementById('priceSelect');
            const priceInput = document.getElementById('priceInput');
            
            if (!categorySelect || !pricesContainer || !priceSelect || !priceInput) {
                console.error('Required elements not found');
                return;
            }
            
            // Handle price selection from dropdown
            priceSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                if (selectedValue && selectedValue !== '') {
                    priceInput.value = selectedValue;
                    priceInput.setAttribute('readonly', 'readonly');
                } else {
                    priceInput.value = '';
                    priceInput.setAttribute('readonly', 'readonly');
                }
            });
            
            categorySelect.addEventListener('change', function() {
                const categoryId = parseInt(this.value);
                
                // Reset price input and dropdown when category changes
                if (priceInput) {
                    priceInput.value = '';
                    priceInput.setAttribute('readonly', 'readonly');
                }
                if (priceSelect) {
                    priceSelect.innerHTML = '<option value="">Select a fixed price option</option>';
                    priceSelect.value = '';
                }
                
                if (!categoryId || isNaN(categoryId)) {
                    pricesContainer.style.display = 'none';
                    return;
                }
                
                // Show container and loading state
                pricesContainer.style.display = 'block';
                priceSelect.disabled = true;
                priceSelect.innerHTML = '<option value="">Loading prices...</option>';
                
                // Fetch prices for selected category using staff API
                fetch('/staff/api/category-prices')
                    .then(r => {
                        if (!r.ok) {
                            throw new Error('Network response was not ok: ' + r.status);
                        }
                        return r.json();
                    })
                    .then(response => {
                        console.log('Category prices response:', response);
                        console.log('Selected category ID:', categoryId, 'Type:', typeof categoryId);
                        
                        if (response.success && response.data && Array.isArray(response.data)) {
                            console.log('Total prices received:', response.data.length);
                            console.log('All prices:', response.data);
                            
                            // Filter prices for the selected category (use loose comparison to handle string/int)
                            const categoryPrices = response.data.filter(p => {
                                // Use loose comparison (==) to handle type mismatches (string vs int)
                                const matches = p.category_id == categoryId;
                                console.log(`Comparing: ${p.category_id} (${typeof p.category_id}) == ${categoryId} (${typeof categoryId}) = ${matches}`);
                                return matches;
                            });
                            
                            console.log('Filtered prices for category', categoryId, ':', categoryPrices);
                            
                            if (categoryPrices.length > 0) {
                                // Populate dropdown with price options
                                let optionsHtml = '<option value="">Select a fixed price option</option>';
                                categoryPrices.forEach(price => {
                                    const priceType = price.price_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                    const priceValue = parseFloat(price.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    optionsHtml += `<option value="${price.price}">${priceType} (₱${priceValue})</option>`;
                                });
                                priceSelect.innerHTML = optionsHtml;
                                priceSelect.disabled = false;
                            } else {
                                console.warn('No prices found for category', categoryId);
                                priceSelect.innerHTML = '<option value="">No fixed prices set for this category yet</option>';
                                priceSelect.disabled = true;
                            }
                        } else {
                            console.warn('Unexpected response format:', response);
                            priceSelect.innerHTML = '<option value="">No fixed prices set for this category yet</option>';
                            priceSelect.disabled = true;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading category prices:', error);
                        priceSelect.innerHTML = '<option value="">Error loading prices. Please try again.</option>';
                        priceSelect.disabled = true;
                    });
            });
        });

        // Form submission
        document.getElementById('addAnimalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            
            fetch('/staff/api/animals/add', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({icon: 'success', title: 'Success!', text: 'Pet submitted for admin approval successfully!'});
                    this.reset();
                    document.getElementById('imagePreview').style.display = 'none';
                    
                    // Reset price input and dropdown
                    const priceInput = document.getElementById('priceInput');
                    const priceSelect = document.getElementById('priceSelect');
                    const pricesContainer = document.getElementById('categoryPricesContainer');
                    
                    if (priceInput) {
                        priceInput.value = '';
                        priceInput.setAttribute('readonly', 'readonly');
                    }
                    if (priceSelect) {
                        priceSelect.innerHTML = '<option value="">Select a fixed price option</option>';
                        priceSelect.value = '';
                    }
                    if (pricesContainer) {
                        pricesContainer.style.display = 'none';
                    }
                } else {
                    const errors = typeof res.message === 'object' 
                        ? Object.values(res.message).join('\n') 
                        : res.message;
                    Swal.fire({icon: 'error', title: 'Error', html: 'Error: ' + errors});
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit for Approval';
            });
        });
    </script>
</body>
</html>

