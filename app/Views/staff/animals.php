<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Animals - Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <span>Manage Animals</span>
                </a>
                <a href="/staff/add-animal" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add New Animal</span>
                </a>
                <a href="/staff/delivery-confirmations" class="sidebar-item">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="/staff/reservations" class="sidebar-item">
                    <i class="fas fa-calendar-check"></i>
                    <span>Reservations</span>
                </a>
                <a href="/staff/orders" class="sidebar-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="/staff/sales-report" class="sidebar-item">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Sales Report</span>
                </a>
                <a href="/staff/payments" class="sidebar-item">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="content-card">
                    <h3 class="mb-4">
                        <i class="fas fa-paw"></i> Manage Available Animals
                    </h3>

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

    <!-- Edit Animal Modal -->
    <div class="modal fade" id="editAnimalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Animal Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editAnimalForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Age (years)</label>
                                <input type="number" class="form-control" id="edit_age" name="age" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select" id="edit_gender" name="gender" required>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
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
                        document.getElementById('edit_age').value = animal.age;
                        document.getElementById('edit_gender').value = animal.gender;
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
                    alert('Animal updated successfully!');
                } else {
                    alert(res.message || 'Failed to update animal');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Network error. Please try again.');
            });
        });
    </script>
</body>
</html>

