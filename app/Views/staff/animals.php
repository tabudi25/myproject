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
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .sidebar {
            background: white;
            min-height: calc(100vh - 76px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            padding: 20px 0;
        }

        .sidebar-item {
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover, .sidebar-item.active {
            background: linear-gradient(90deg, rgba(255,107,53,0.1) 0%, rgba(255,107,53,0.05) 100%);
            border-left-color: var(--primary-color);
            color: var(--primary-color);
        }

        .sidebar-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            padding: 30px;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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
                    <span>Manage Pets</span>
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
                            <i class="fas fa-plus me-2"></i>Add New Animal
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
                    <h5 class="modal-title">Add New Animal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addAnimalForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> Animals added by staff require admin approval before they become visible to customers.
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Animal Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Age (months) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="age" min="1" required>
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
                                <input type="number" step="0.01" class="form-control" name="price" min="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Describe the animal's characteristics, temperament, etc."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="image" accept="image/*" required id="addImageInput">
                            <small class="text-muted">Upload an image of the animal</small>
                            <div class="mt-2">
                                <img id="addImagePreview" class="img-thumbnail" style="display: none; max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Submit for Approval
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

        // Add Animal Form submission
        document.getElementById('addAnimalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            
            fetch('/staff/api/animals/add', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Animal submitted for admin approval successfully!'
                    });
                    this.reset();
                    document.getElementById('addImagePreview').style.display = 'none';
                    bootstrap.Modal.getInstance(document.getElementById('addAnimalModal')).hide();
                    // Optionally reload animals to show the new pending animal
                    // loadAnimals();
                } else {
                    const errors = typeof res.message === 'object' 
                        ? Object.values(res.message).join('\n') 
                        : res.message;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: 'Error: ' + errors
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Network error. Please try again.'
                });
            })
            .finally(() => {
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
                tbody.innerHTML = '<tr><td colspan="8" class="text-center">No available animals found</td></tr>';
                return;
            }

            tbody.innerHTML = animals.map(animal => `
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
                        <span class="badge badge-${animal.status}">${animal.status}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editAnimal(${animal.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
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

