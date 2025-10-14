<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Admin</title>
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
        .category-image { 
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
                    <a href="/fluffy-admin/animals">
                        <i class="fas fa-paw"></i>
                        <span class="menu-text">Animals</span>
                    </a>
                </li>
                <li>
                    <a href="/fluffy-admin/categories" class="active">
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
                <h4 class="mb-0"><i class="fas fa-tags text-primary me-2"></i>Manage Categories</h4>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fas fa-plus me-2"></i>Add Category
                    </button>
                </div>
                <div class="page-card">
                    <div class="table-responsive">
                        <table class="table align-middle" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Animals</th>
                                    <th style="width:130px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="6" class="text-center py-4">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="addCategoryForm" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editCategoryForm" enctype="multipart/form-data">
        <input type="hidden" name="edit_id" id="edit_id">
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control" name="name" id="edit_name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image (leave empty to keep current)</label>
                <input type="file" class="form-control" name="image" accept="image/*">
                <small class="text-muted" id="edit_current_image"></small>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-control" name="status" id="edit_status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
loadCategories();

function loadCategories(){
  fetch('/fluffy-admin/api/categories')
    .then(r=>r.json())
    .then(({success, data})=>{
      const tbody = document.querySelector('#categoriesTable tbody');
      if(!success){ tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load</td></tr>'; return; }
      if(!data.length){ tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No categories</td></tr>'; return; }
      tbody.innerHTML = data.map(c=>`
        <tr>
          <td>${c.name}</td>
          <td>${c.description||''}</td>
          <td>${c.image?`<img class=\"category-image\" src=\"/uploads/${c.image}\" onerror=\"this.src='/web/default-pet.jpg'\">`:''}</td>
          <td><span class="badge bg-${c.status==='active'?'success':'secondary'}">${c.status}</span></td>
          <td>${c.animal_count||0}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary me-1" onclick="editCategory(${c.id})"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${c.id})"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
      `).join('');
    });
}

document.getElementById('addCategoryForm').addEventListener('submit', function(e){
  e.preventDefault();
  const fd = new FormData(this);
  fetch('/fluffy-admin/api/categories', { method:'POST', body:fd })
    .then(r=>r.json())
    .then(res=>{ if(res.success){ bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide(); this.reset(); loadCategories(); } else { alert(res.message||'Failed'); } });
});

function editCategory(id){
  fetch('/fluffy-admin/api/categories')
    .then(r=>r.json())
    .then(({success, data})=>{
      const cat = data.find(c => c.id == id);
      if(!cat) return alert('Category not found');
      
      document.getElementById('edit_id').value = cat.id;
      document.getElementById('edit_name').value = cat.name;
      document.getElementById('edit_description').value = cat.description || '';
      document.getElementById('edit_status').value = cat.status;
      document.getElementById('edit_current_image').textContent = cat.image ? 'Current: ' + cat.image : 'No image';
      
      new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
    });
}

document.getElementById('editCategoryForm').addEventListener('submit', function(e){
  e.preventDefault();
  const formData = new FormData(this);
  const id = document.getElementById('edit_id').value;
  fetch('/fluffy-admin/api/categories/'+id, { method:'PUT', body:formData })
    .then(r=>r.json())
    .then(res=>{
      if(res.success){
        bootstrap.Modal.getInstance(document.getElementById('editCategoryModal')).hide();
        loadCategories();
      } else { alert(res.message || 'Failed'); }
    })
    .catch(()=>alert('Network error'));
});

function deleteCategory(id){
  if(!confirm('Delete this category?')) return;
  fetch('/fluffy-admin/api/categories/'+id, { method:'DELETE' })
    .then(r=>r.json()).then(res=>{ if(res.success){ loadCategories(); } else { alert(res.message||'Failed'); } });
}
</script>
</body>
</html>

