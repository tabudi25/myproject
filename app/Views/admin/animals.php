<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Animals - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff6b35;
            --secondary-color: #f7931e;
            --accent-color: #2c3e50;
            --sidebar-bg: #343a40;
            --sidebar-hover: #495057;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #495057;
            text-align: center;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            text-decoration: none;
        }

        .sidebar-menu {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .sidebar-menu li {
            border-bottom: 1px solid #495057;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--sidebar-hover);
            color: white;
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            transition: all 0.3s ease;
        }

        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-area {
            padding: 30px;
        }

        .page-card { 
            background:#fff; 
            border-radius:12px; 
            padding:20px; 
            box-shadow:0 2px 10px rgba(0,0,0,.05); 
        }
        .table img { 
            width:50px; 
            height:50px; 
            object-fit:cover; 
            border-radius:8px; 
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="/fluffy-admin" class="sidebar-brand">
                    <i class="fas fa-paw me-2"></i>
                    <span class="brand-text">Fluffy Admin</span>
                </a>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="/fluffy-admin">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/animals" class="active">
                        <i class="fas fa-paw"></i>
                        <span class="menu-text">Animals</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/categories">
                        <i class="fas fa-tags"></i>
                        <span class="menu-text">Categories</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/orders">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="menu-text">Orders</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/users">
                        <i class="fas fa-users"></i>
                        <span class="menu-text">Users</span>
                    </a>
                </li>
                <li>
                    <a href="/">
                        <i class="fas fa-globe"></i>
                        <span class="menu-text">Visit Site</span>
                    </a>
                </li>
                <li>
                    <a href="/logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="menu-text">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <div class="top-navbar">
                <h4 class="mb-0"><i class="fas fa-paw text-primary me-2"></i>Manage Animals</h4>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnimalModal">
                        <i class="fas fa-plus me-2"></i>Add Animal
                    </button>
                </div>
                <div class="page-card">
                    <div class="table-responsive">
                        <table class="table align-middle" id="animalsTable">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th style="width:130px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="8" class="text-center py-4">Loading...</td></tr>
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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select class="form-control" name="category_id" required id="categorySelect">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Age (months) *</label>
                                <input type="number" class="form-control" name="age" required min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender *</label>
                                <select class="form-control" name="gender" required>
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price (₱) *</label>
                                <input type="number" step="0.01" class="form-control" name="price" required min="0">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Image *</label>
                                <input type="file" class="form-control" name="image" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Animal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Animal Modal -->
    <div class="modal fade" id="editAnimalModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Animal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editAnimalForm" enctype="multipart/form-data">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" id="edit_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select class="form-control" name="category_id" id="edit_category_id" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Age (months) *</label>
                                <input type="number" class="form-control" name="age" id="edit_age" required min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender *</label>
                                <select class="form-control" name="gender" id="edit_gender" required>
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price (₱) *</label>
                                <input type="number" step="0.01" class="form-control" name="price" id="edit_price" required min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-control" name="status" id="edit_status" required>
                                    <option value="available">Available</option>
                                    <option value="sold">Sold</option>
                                    <option value="reserved">Reserved</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Image (leave empty to keep current)</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                <small class="text-muted" id="edit_current_image"></small>
                            </div>
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
        loadAnimals();
        loadCategories();

        function loadAnimals() {
            fetch('/fluffy-admin/api/animals')
                .then(r => r.json())
                .then(({success, data}) => {
                    const tbody = document.querySelector('#animalsTable tbody');
                    if (!success) { tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Failed to load</td></tr>'; return; }
                    if (!data.length) { tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No animals found</td></tr>'; return; }
                    tbody.innerHTML = data.map(animal => `
                        <tr>
                            <td><img src="/uploads/${animal.image}" onerror="this.src='/web/default-pet.jpg'" alt="${animal.name}"></td>
                            <td>${animal.name}</td>
                            <td>${animal.category_name || 'N/A'}</td>
                            <td>${animal.gender || ''}</td>
                            <td>${animal.age} mo</td>
                            <td>₱${parseFloat(animal.price).toLocaleString()}</td>
                            <td><span class="badge bg-${animal.status==='available'?'success':'secondary'}">${animal.status}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="openEdit(${animal.id})"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteAnimal(${animal.id})"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `).join('');
                })
                .catch(() => {
                    document.querySelector('#animalsTable tbody').innerHTML = '<tr><td colspan="8" class="text-center text-danger">Network error</td></tr>';
                });
        }

        function loadCategories() {
            fetch('/fluffy-admin/api/categories')
                .then(r => r.json())
                .then(({success, data}) => {
                    const addSelect = document.getElementById('categorySelect');
                    const editSelect = document.getElementById('edit_category_id');
                    const options = '<option value="">Select Category</option>' + (success ? data.map(cat => `<option value="${cat.id}">${cat.name}</option>`).join('') : '');
                    addSelect.innerHTML = options;
                    editSelect.innerHTML = options;
                });
        }

        // Add
        document.getElementById('addAnimalForm').addEventListener('submit', function(e){
            e.preventDefault();
            const formData = new FormData(this);
            fetch('/fluffy-admin/api/animals', { method:'POST', body:formData })
                .then(r=>r.json())
                .then(res=>{
                    if(res.success){
                        bootstrap.Modal.getInstance(document.getElementById('addAnimalModal')).hide();
                        this.reset();
                        loadAnimals();
                    } else { alert(res.message || 'Failed'); }
                })
                .catch(()=>alert('Network error'));
        });

        // Edit
        function openEdit(id){
            fetch('/fluffy-admin/api/animals')
                .then(r => r.json())
                .then(({success, data}) => {
                    const animal = data.find(a => a.id == id);
                    if (!animal) return alert('Animal not found');
                    
                    document.getElementById('edit_id').value = animal.id;
                    document.getElementById('edit_name').value = animal.name;
                    document.getElementById('edit_category_id').value = animal.category_id;
                    document.getElementById('edit_age').value = animal.age;
                    document.getElementById('edit_gender').value = animal.gender;
                    document.getElementById('edit_price').value = animal.price;
                    document.getElementById('edit_status').value = animal.status;
                    document.getElementById('edit_description').value = animal.description || '';
                    document.getElementById('edit_current_image').textContent = 'Current: ' + animal.image;
                    
                    new bootstrap.Modal(document.getElementById('editAnimalModal')).show();
                });
        }

        document.getElementById('editAnimalForm').addEventListener('submit', function(e){
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('edit_id').value;
            
            // Use POST with _method override for file uploads
            formData.append('_method', 'PUT');
            
            fetch('/fluffy-admin/api/animals/'+id, { method:'POST', body:formData })
                .then(r=>r.json())
                .then(res=>{
                    if(res.success){
                        bootstrap.Modal.getInstance(document.getElementById('editAnimalModal')).hide();
                        this.reset();
                        loadAnimals();
                    } else { 
                        alert(res.message || 'Failed to update animal'); 
                    }
                })
                .catch(err=>{
                    console.error('Error:', err);
                    alert('Network error. Please try again.');
                });
        });

        function deleteAnimal(id){
            if(!confirm('Delete this animal?')) return;
            fetch('/fluffy-admin/api/animals/'+id, { method:'DELETE' })
                .then(r=>r.json()).then(res=>{ if(res.success){ loadAnimals(); } else { alert(res.message||'Failed'); } });
        }
    </script>
</body>
</html>

