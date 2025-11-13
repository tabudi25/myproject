<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pets - Staff Dashboard</title>
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

        .page-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-top: 4px solid var(--primary-color);
            margin-bottom: 20px;
        }
        
        .content-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-top: 4px solid var(--primary-color);
            margin-bottom: 20px;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid var(--primary-color);
            font-weight: 600;
            color: var(--black);
            white-space: nowrap;
            padding: 12px;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 12px;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

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

        .table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }

        .badge-available { background: #28a745; }
        .badge-sold { background: #dc3545; }
        .badge-reserved { background: #ffc107; color: #000; }
        .badge-inactive { background: #6c757d; }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-status {
            padding: 4px 12px;
            font-size: 0.875rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-status:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }
        
        .btn-active {
            background-color: #28a745;
            color: white;
        }
        
        .btn-inactive {
            background-color: #6c757d;
            color: white;
        }

        /* Price Selection Buttons */
        .price-option-btn {
            display: inline-block;
            padding: 12px 20px;
            margin: 5px;
            background: white;
            border: 2px solid #17a2b8;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            min-width: 150px;
        }

        .price-option-btn:hover {
            background: #17a2b8;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .price-option-btn.selected {
            background: #17a2b8;
            color: white;
            border-color: #138496;
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

        #add_price[readonly] {
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

            .page-card {
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
                <a href="/staff/animals" class="sidebar-item active">
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">
                            <i class="fas fa-paw"></i> List of Pets
                        </h3>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnimalModal">
                            <i class="fas fa-plus me-2"></i>Add New Pet
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="animalsTableBody">
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Animal Modal -->
    <div class="modal fade" id="addAnimalModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Pet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addAnimalForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pet Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" name="category_id" id="add_category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Category Prices Display -->
                        <div class="mb-3" id="categoryPricesContainer" style="display: none;">
                            <label class="form-label">Fixed Prices for this Category (Set by Admin)</label>
                            <select class="form-select" id="priceSelect">
                                <option value="">Select a fixed price option</option>
                            </select>
                            <small class="text-muted">Select a price option to auto-fill gender and price fields below</small>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="birthdate" required max="<?= date('Y-m-d') ?>">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" name="gender" id="add_gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price (₱) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="price" id="add_price" min="0" required readonly>
                                <small class="text-muted">Select a fixed price option above to auto-fill</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Describe the pet's characteristics, temperament, etc."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="image" accept="image/*" required id="addImageInput">
                            <small class="text-muted">Upload an image of the pet</small>
                            <div class="mt-2">
                                <img id="addImagePreview" class="img-thumbnail" style="display: none; max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Confirm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Animal Modal -->
    <div class="modal fade" id="editAnimalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Animal Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editAnimalForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="edit_category_id" name="category_id" required disabled style="background-color: #e9ecef; cursor: not-allowed;">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="edit_category_id_hidden" name="category_id">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Age (years)</label>
                                <input type="number" class="form-control" id="edit_age" name="age" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select" id="edit_gender" name="gender" required disabled style="background-color: #e9ecef; cursor: not-allowed;">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <input type="hidden" id="edit_gender_hidden" name="gender">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Price (₱)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_price" name="price" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Image (optional)</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Animal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load animals on page load
        loadAnimals();

        // Reset price input and dropdown when modal is closed
        const addAnimalModal = document.getElementById('addAnimalModal');
        if (addAnimalModal) {
            addAnimalModal.addEventListener('hidden.bs.modal', function() {
                const priceInput = document.getElementById('add_price');
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
            });
        }

        // Image preview for add animal form
        document.getElementById('addImageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('addImagePreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Load category prices when category is selected
        const addCategorySelect = document.getElementById('add_category_id');
        const priceSelect = document.getElementById('priceSelect');
        const priceInput = document.getElementById('add_price');
        
        if (addCategorySelect && priceSelect && priceInput) {
            // Handle price selection from dropdown
            priceSelect.addEventListener('change', function() {
                const selectedIndex = this.value;
                const genderSelect = document.getElementById('add_gender');
                
                if (selectedIndex && window.categoryPricesData && window.categoryPricesData[selectedIndex]) {
                    const selectedPrice = window.categoryPricesData[selectedIndex];
                    
                    // Extract gender from price_type (e.g., "adult_price_male" -> "male")
                    const priceTypeParts = selectedPrice.price_type.split('_');
                    const gender = priceTypeParts[priceTypeParts.length - 1]; // Last part is usually gender
                    
                    // Set gender if it's valid
                    if (genderSelect && (gender === 'male' || gender === 'female')) {
                        genderSelect.value = gender;
                    }
                    
                    // Set price
                    if (priceInput) {
                        priceInput.value = parseFloat(selectedPrice.price).toFixed(2);
                        priceInput.setAttribute('readonly', 'readonly');
                    }
                } else {
                    // Reset fields if no option selected
                    if (genderSelect) genderSelect.value = '';
                    if (priceInput) {
                        priceInput.value = '';
                        priceInput.setAttribute('readonly', 'readonly');
                    }
                }
            });
            
            addCategorySelect.addEventListener('change', function() {
                const categoryId = parseInt(this.value);
                const pricesContainer = document.getElementById('categoryPricesContainer');
                
                // Reset price input, dropdown, and gender when category changes
                if (priceInput) {
                    priceInput.value = '';
                    priceInput.setAttribute('readonly', 'readonly');
                }
                if (priceSelect) {
                    priceSelect.innerHTML = '<option value="">Select a fixed price option</option>';
                    priceSelect.value = '';
                }
                const genderSelect = document.getElementById('add_gender');
                if (genderSelect) genderSelect.value = '';
                window.categoryPricesData = null;
                
                if (!categoryId || isNaN(categoryId)) {
                    if (pricesContainer) pricesContainer.style.display = 'none';
                    return;
                }
                
                // Show container and loading state
                if (pricesContainer) pricesContainer.style.display = 'block';
                priceSelect.disabled = true;
                priceSelect.innerHTML = '<option value="">Loading prices...</option>';
                
                // Fetch all prices and filter for selected category
                fetch('/staff/api/category-prices')
                    .then(r => {
                        if (!r.ok) {
                            throw new Error('Network response was not ok: ' + r.status);
                        }
                        return r.json();
                    })
                    .then(response => {
                        console.log('Category prices response:', response);
                        
                        if (response.success && response.data && Array.isArray(response.data)) {
                            // Filter prices for the selected category (use loose comparison to handle string/int)
                            const categoryPrices = response.data.filter(p => {
                                return p.category_id == categoryId;
                            });
                            
                            console.log('Filtered prices for category', categoryId, ':', categoryPrices);
                            
                            if (categoryPrices.length > 0) {
                                // Store prices data for later use
                                window.categoryPricesData = categoryPrices;
                                
                                // Populate dropdown with price options
                                let optionsHtml = '<option value="">Select a fixed price option</option>';
                                categoryPrices.forEach((price, index) => {
                                    // Format price type: adult_price_male -> Adult Price Male
                                    const priceType = price.price_type
                                        .split('_')
                                        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                                        .join(' ');
                                    const priceValue = parseFloat(price.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    optionsHtml += `<option value="${index}">${priceType}: ₱${priceValue}</option>`;
                                });
                                priceSelect.innerHTML = optionsHtml;
                                priceSelect.disabled = false;
                            } else {
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
        }

        // Add Animal Form submission
        document.getElementById('addAnimalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate required fields before processing
            const name = document.querySelector('[name="name"]').value.trim();
            const categoryId = document.querySelector('[name="category_id"]').value;
            const birthdate = document.querySelector('[name="birthdate"]').value;
            const gender = document.querySelector('[name="gender"]').value;
            const price = document.querySelector('[name="price"]').value;
            const image = document.querySelector('[name="image"]').files[0];
            
            if (!name) {
                Swal.fire({icon: 'warning', title: 'Warning', text: 'Please enter the pet\'s name.'});
                return;
            }
            if (!categoryId) {
                Swal.fire({icon: 'warning', title: 'Warning', text: 'Please select a category.'});
                return;
            }
            if (!birthdate) {
                Swal.fire({icon: 'warning', title: 'Warning', text: 'Please select the pet\'s birthdate.'});
                return;
            }
            if (!gender) {
                Swal.fire({icon: 'warning', title: 'Warning', text: 'Please select the pet\'s gender.'});
                return;
            }
            if (!price || price === '' || isNaN(parseFloat(price)) || parseFloat(price) <= 0) {
                Swal.fire({icon: 'warning', title: 'Warning', text: 'Please select a price from the dropdown. The price field must have a valid value.'});
                return;
            }
            if (!image) {
                Swal.fire({icon: 'warning', title: 'Warning', text: 'Please select an image for the pet.'});
                return;
            }
            
            const formData = new FormData(this);
            
            // Ensure price is properly set (readonly fields should submit, but let's be explicit)
            formData.set('price', price);
            
            // Validate birthdate
            const b = new Date(birthdate);
            const now = new Date();
            if (b > now) {
                Swal.fire({icon: 'warning', title: 'Warning', text: 'Birthdate cannot be in the future.'});
                return;
            }
            const years = now.getFullYear() - b.getFullYear();
            const months = now.getMonth() - b.getMonth();
            const days = now.getDate() - b.getDate();
            let ageMonths = years * 12 + months - (days < 0 ? 1 : 0);
            if (ageMonths < 0) ageMonths = 0;

            // Keep birthdate in formData and also add age
            formData.set('age', String(ageMonths));
            
            // Debug: Log form data (remove in production)
            console.log('Submitting form data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            
            fetch('/staff/api/animals/add', {
                method: 'POST',
                body: formData
            })
            .then(async r => {
                const responseText = await r.text();
                console.log('Response status:', r.status);
                console.log('Response text:', responseText);
                
                if (!r.ok) {
                    throw new Error('HTTP error! status: ' + r.status + ', response: ' + responseText);
                }
                
                try {
                    return JSON.parse(responseText);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    throw new Error('Invalid JSON response: ' + responseText);
                }
            })
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Pet added successfully!'
                    });
                    this.reset();
                    document.getElementById('addImagePreview').style.display = 'none';
                    
                    // Reset price input and dropdown
                    const priceInput = document.getElementById('add_price');
                    const priceSelect = document.getElementById('priceSelect');
                    const genderSelect = document.getElementById('add_gender');
                    
                    if (priceInput) {
                        priceInput.value = '';
                        priceInput.setAttribute('readonly', 'readonly');
                    }
                    if (priceSelect) {
                        priceSelect.innerHTML = '<option value="">Select a fixed price option</option>';
                        priceSelect.value = '';
                    }
                    if (genderSelect) genderSelect.value = '';
                    window.categoryPricesData = null;
                    
                    bootstrap.Modal.getInstance(document.getElementById('addAnimalModal')).hide();
                    // Reload animals to show the new pet
                    if (typeof loadAnimals === 'function') {
                        loadAnimals();
                    }
                } else {
                    const errors = typeof res.message === 'object' 
                        ? Object.values(res.message).join('\n') 
                        : res.message || 'Unknown error occurred';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: 'Error: ' + errors
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(err => {
                console.error('Form submission error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Network error. Please try again. Error: ' + (err.message || 'Unknown error')
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        function loadAnimals() {
            fetch('/staff/api/animals')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderAnimals(data.data);
                    } else {
                        document.getElementById('animalsTableBody').innerHTML = 
                            '<tr><td colspan="8" class="text-center text-danger">Failed to load animals</td></tr>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('animalsTableBody').innerHTML = 
                        '<tr><td colspan="8" class="text-center text-danger">Network error</td></tr>';
                });
        }

        function renderAnimals(animals) {
            const tbody = document.getElementById('animalsTableBody');
            
            if (animals.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center">No animals found</td></tr>';
                return;
            }

            tbody.innerHTML = animals.map(animal => {
                // Map status to display: available = Active, sold/reserved = Inactive
                const isActive = animal.status === 'available';
                const displayStatus = isActive ? 'Active' : 'Inactive';
                const statusClass = isActive ? 'available' : 'inactive';
                
                return `
                <tr>
                    <td>
                        <img src="/uploads/${animal.image || 'default.png'}" alt="${animal.name}">
                    </td>
                    <td>${animal.name}</td>
                    <td>${animal.category_name || 'N/A'}</td>
                    <td>${animal.age} years</td>
                    <td>${animal.gender}</td>
                    <td>₱${parseFloat(animal.price).toLocaleString()}</td>
                    <td>
                        <span class="badge badge-${statusClass}">${displayStatus}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            ${isActive ? 
                                `<button class="btn-status btn-inactive" onclick="updateAnimalStatus(${animal.id}, 'inactive')" title="Mark as Inactive">
                                    <i class="fas fa-times-circle"></i> Inactive
                                </button>` :
                                `<button class="btn-status btn-active" onclick="updateAnimalStatus(${animal.id}, 'active')" title="Mark as Active">
                                    <i class="fas fa-check-circle"></i> Active
                                </button>`
                            }
                        </div>
                    </td>
                </tr>
            `;
            }).join('');
        }
        
        function updateAnimalStatus(animalId, status) {
            // Map 'active' to 'available' and 'inactive' to 'sold' for database
            const dbStatus = status === 'active' ? 'available' : 'sold';
            
            Swal.fire({
                title: 'Update Status?',
                text: `Are you sure you want to mark this pet as ${status === 'active' ? 'Active' : 'Inactive'}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: status === 'active' ? '#28a745' : '#6c757d',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/staff/api/animals/${animalId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ status: dbStatus })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message || 'Pet status updated successfully',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            loadAnimals(); // Reload the table
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to update pet status'
                            });
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Network error. Please try again.'
                        });
                    });
                }
            });
        }

        function editAnimal(id) {
            fetch('/staff/api/animals')
                .then(r => r.json())
                .then(data => {
                    const animal = data.data.find(a => a.id == id);
                    if (animal) {
                        document.getElementById('edit_id').value = animal.id;
                        document.getElementById('edit_name').value = animal.name;
                        document.getElementById('edit_category_id').value = animal.category_id;
                        document.getElementById('edit_category_id_hidden').value = animal.category_id;
                        document.getElementById('edit_age').value = animal.age;
                        document.getElementById('edit_gender').value = animal.gender;
                        document.getElementById('edit_gender_hidden').value = animal.gender;
                        document.getElementById('edit_price').value = animal.price;
                        document.getElementById('edit_description').value = animal.description || '';
                        
                        new bootstrap.Modal(document.getElementById('editAnimalModal')).show();
                    }
                });
        }

        document.getElementById('editAnimalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('edit_id').value;
            
            formData.append('_method', 'PUT');
            
            fetch('/staff/api/animals/' + id, {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('editAnimalModal')).hide();
                    this.reset();
                    loadAnimals();
                    Swal.fire({icon: 'success', title: 'Success!', text: 'Animal updated successfully!'});
                } else {
                    Swal.fire({icon: 'error', title: 'Error', text: res.message || 'Failed to update animal'});
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({icon: 'error', title: 'Error', text: 'Network error. Please try again.'});
            });
        });
    </script>
</body>
</html>

