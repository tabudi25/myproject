<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluffy Planet - Admin Dashboard</title>
    <style>
        /* Admin Dashboard Styles */
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #2196F3;
            --danger-color: #f44336;
            --warning-color: #ff9800;
            --success-color: #4CAF50;
            --bg-color: #f5f5f5;
            --card-bg: #ffffff;
            --text-color: #333;
            --border-color: #ddd;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--card-bg);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
        }

        .logo h1 {
            color: var(--primary-color);
            font-size: 24px;
            margin-bottom: 5px;
        }

        .logo p {
            color: #666;
            font-size: 14px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-menu li {
            margin-bottom: 10px;
        }

        .nav-menu a {
            display: block;
            padding: 12px 15px;
            text-decoration: none;
            color: var(--text-color);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-menu a:hover, .nav-menu a.active {
            background-color: var(--primary-color);
            color: white;
        }

        .user-info {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .user-info p {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .logout-btn {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .header h1 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 2em;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .stat-card p {
            color: #666;
        }

        /* Animal Management Section */
        .animals-section {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .add-animal-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .add-animal-btn:hover {
            background: #45a049;
        }

        .animals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .animal-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .animal-card:hover {
            transform: translateY(-5px);
        }

        .animal-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .animal-info {
            padding: 15px;
        }

        .animal-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .animal-details {
            color: #666;
            margin-bottom: 10px;
        }

        .animal-price {
            font-size: 20px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .animal-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            flex: 1;
        }

        .btn-edit {
            background: var(--secondary-color);
            color: white;
        }

        .btn-delete {
            background: var(--danger-color);
            color: white;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: var(--card-bg);
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group textarea {
            resize: vertical;
            height: 100px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-save {
            background: var(--success-color);
            color: white;
            padding: 10px 20px;
        }

        .btn-cancel {
            background: #666;
            color: white;
            padding: 10px 20px;
        }

        /* Alert Messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: none;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Loading Spinner */
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                order: 2;
            }

            .main-content {
                order: 1;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .animals-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h1>🐾 Fluffy Planet</h1>
                <p>Admin Dashboard</p>
            </div>

            <ul class="nav-menu">
                <li><a href="#dashboard" class="nav-link active" data-section="dashboard">Dashboard</a></li>
                <li><a href="#animals" class="nav-link" data-section="animals">Manage Animals</a></li>
                <li><a href="#users" class="nav-link" data-section="users">Manage Users</a></li>
                <li><a href="#orders" class="nav-link" data-section="orders">Orders</a></li>
            </ul>

            <div class="user-info">
                <p>Welcome, <?= session()->get('user_name') ?>!</p>
                <a href="<?= base_url('logout') ?>" class="logout-btn">Logout</a>
                </div>
            </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Alert Messages -->
            <div id="alert" class="alert"></div>

            <!-- Dashboard Section -->
            <div id="dashboard-section" class="content-section">
                <div class="header">
                    <h1>Admin Dashboard</h1>
                    <p>Welcome to Fluffy Planet Admin Panel</p>
                    <?php if(session()->getFlashdata('msg')): ?>
                        <div class="alert success" style="display: block; margin-top: 10px;">
                            <?= session()->getFlashdata('msg') ?>
        </div>
                    <?php endif; ?>
    </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h3 id="total-animals">0</h3>
                        <p>Total Animals</p>
                    </div>
                    <div class="stat-card">
                        <h3 id="available-animals">0</h3>
                        <p>Available Animals</p>
                    </div>
                    <div class="stat-card">
                        <h3 id="total-orders">0</h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="stat-card">
                        <h3 id="total-users">0</h3>
                        <p>Total Users</p>
                    </div>
                </div>
            </div>

            <!-- Animals Management Section -->
            <div id="animals-section" class="content-section" style="display: none;">
                <div class="animals-section">
                    <div class="section-header">
                        <h2>Manage Animals</h2>
                        <button class="add-animal-btn" onclick="openAnimalModal()">Add New Animal</button>
                </div>

                    <div class="loading" id="animals-loading">
                        <div class="spinner"></div>
                        <p>Loading animals...</p>
                </div>

                    <div class="animals-grid" id="animals-grid">
                        <!-- Animals will be loaded here -->
                </div>
                    </div>
                </div>

            <!-- Users Management Section -->
            <div id="users-section" class="content-section" style="display: none;">
                <div class="animals-section">
                    <div class="section-header">
                        <h2>Manage Users</h2>
                        <button class="add-animal-btn" onclick="openUserModal()">Add New User</button>
                        </div>

                    <div class="loading" id="users-loading">
                        <div class="spinner"></div>
                        <p>Loading users...</p>
                        </div>

                    <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th style="padding: 15px; text-align: left; font-weight: 600;">Name</th>
                                    <th style="padding: 15px; text-align: left; font-weight: 600;">Email</th>
                                    <th style="padding: 15px; text-align: left; font-weight: 600;">Role</th>
                                    <th style="padding: 15px; text-align: left; font-weight: 600;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="users-table">
                                <!-- Users will be loaded here -->
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>

            <!-- Other sections (placeholder) -->
            <div id="orders-section" class="content-section" style="display: none;">
                <div class="header">
                    <h1>Orders Management</h1>
                    <p>Coming soon...</p>
                            </div>
                        </div>
                        </div>
                    </div>

    <!-- Animal Modal -->
    <div id="animalModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Add New Animal</h2>
                <span class="close" onclick="closeAnimalModal()">&times;</span>
                    </div>

            <form id="animalForm" enctype="multipart/form-data">
                <input type="hidden" id="animal-id" name="id">
                
                <div class="form-group">
                    <label for="animal-name">Animal Name *</label>
                    <input type="text" id="animal-name" name="name" required>
                    </div>

                <div class="form-group">
                    <label for="animal-category">Category *</label>
                    <select id="animal-category" name="category" required>
                        <option value="">Select Category</option>
                                <option value="cat">Cat</option>
                        <option value="dog">Dog</option>
                                <option value="bird">Bird</option>
                        <option value="hamster">Hamster</option>
                        <option value="rabbit">Rabbit</option>
                        <option value="fish">Fish</option>
                            </select>
                        </div>

                <div class="form-group">
                    <label for="animal-age">Age (months) *</label>
                    <input type="number" id="animal-age" name="age" min="1" required>
                    </div>

                <div class="form-group">
                    <label for="animal-gender">Gender *</label>
                    <select id="animal-gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">♂️ Male</option>
                        <option value="female">♀️ Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="animal-price">Price (₱) *</label>
                    <input type="number" id="animal-price" name="price" min="0" step="0.01" required>
                        </div>

                <div class="form-group">
                    <label for="animal-description">Description</label>
                    <textarea id="animal-description" name="description" placeholder="Enter animal description..."></textarea>
                    </div>

                <div class="form-group">
                    <label for="animal-image">Image *</label>
                    <input type="file" id="animal-image" name="image" accept="image/*">
                    <div id="current-image" style="margin-top: 10px; display: none;">
                        <p>Current image:</p>
                        <img id="current-image-preview" src="" alt="Current image" style="max-width: 200px; border-radius: 5px;">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeAnimalModal()">Cancel</button>
                    <button type="submit" class="btn btn-save">Save Animal</button>
                </div>
            </form>
                        </div>
                    </div>

    <!-- User Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="user-modal-title">Add New User</h2>
                <span class="close" onclick="closeUserModal()">&times;</span>
                    </div>

            <form id="userForm">
                <input type="hidden" id="user-id" name="id">
                
                <div class="form-group">
                    <label for="user-name">Name *</label>
                    <input type="text" id="user-name" name="name" required>
                        </div>

                <div class="form-group">
                    <label for="user-email">Email *</label>
                    <input type="email" id="user-email" name="email" required>
                    </div>

                <div class="form-group">
                    <label for="user-password">Password *</label>
                    <input type="password" id="user-password" name="password" required minlength="6">
                    <small style="color: #666; font-size: 12px;">Leave empty when editing to keep current password</small>
                    </div>

                <div class="form-group">
                    <label for="user-role">Role *</label>
                    <select id="user-role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                    <small style="color: #666; font-size: 12px;">Only Admin and Staff roles can be created here</small>
    </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeUserModal()">Cancel</button>
                    <button type="submit" class="btn btn-save">Save User</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global variables
        let animals = [];
        let users = [];
        let currentAnimalId = null;
        let currentUserId = null;

        // Initialize the admin panel
        document.addEventListener('DOMContentLoaded', function() {
            loadAnimals();
            loadStats();
            
            // Navigation
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const section = this.dataset.section;
                    showSection(section);
                    
                    // Update active nav
                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Animal form submission
            document.getElementById('animalForm').addEventListener('submit', handleAnimalSubmit);
            
            // User form submission
            document.getElementById('userForm').addEventListener('submit', handleUserSubmit);
        });

        // Show specific section
        function showSection(section) {
            document.querySelectorAll('.content-section').forEach(s => s.style.display = 'none');
            document.getElementById(section + '-section').style.display = 'block';
            
            if (section === 'animals') {
                loadAnimals();
            } else if (section === 'users') {
                loadUsers();
            }
        }

        // Load animals from API
        async function loadAnimals() {
            const loading = document.getElementById('animals-loading');
            const grid = document.getElementById('animals-grid');
            
            loading.style.display = 'block';
            grid.innerHTML = '';

            try {
                const response = await fetch('<?= base_url('admin/animals') ?>');
                if (!response.ok) throw new Error('Failed to load animals');
                
                animals = await response.json();
                renderAnimals();
            } catch (error) {
                showAlert('Error loading animals: ' + error.message, 'error');
            } finally {
                loading.style.display = 'none';
            }
        }

        // Render animals in grid
        function renderAnimals() {
            const grid = document.getElementById('animals-grid');
            
            if (animals.length === 0) {
                grid.innerHTML = '<p style="text-align: center; grid-column: 1/-1; padding: 40px;">No animals found. Add your first animal!</p>';
                return;
            }

            grid.innerHTML = animals.map(animal => `
                <div class="animal-card">
                    <img src="${animal.image ? '<?= base_url('uploads/') ?>' + animal.image : '<?= base_url('web/placeholder.jpg') ?>'}" 
                         alt="${animal.name}" class="animal-image" 
                         onerror="this.src='<?= base_url('web/placeholder.jpg') ?>'">
                    <div class="animal-info">
                        <div class="animal-name">${animal.name}</div>
                        <div class="animal-details">
                            ${animal.category.charAt(0).toUpperCase() + animal.category.slice(1)} • ${animal.age} months old • ${animal.gender === 'male' ? '♂️' : '♀️'} ${animal.gender.charAt(0).toUpperCase() + animal.gender.slice(1)}
                        </div>
                        <div class="animal-price">₱${parseFloat(animal.price).toLocaleString()}</div>
                        <div class="animal-actions">
                            <button class="btn btn-edit" onclick="editAnimal(${animal.id})">Edit</button>
                            <button class="btn btn-delete" onclick="deleteAnimal(${animal.id})">Delete</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Load dashboard stats
        async function loadStats() {
            try {
                // Load animals stats
                const animalsResponse = await fetch('<?= base_url('admin/animals') ?>');
                if (animalsResponse.ok) {
                    const animals = await animalsResponse.json();
                    document.getElementById('total-animals').textContent = animals.length;
                    document.getElementById('available-animals').textContent = 
                        animals.filter(a => a.status === 'available').length;
                }

                // Load users stats
                const usersResponse = await fetch('<?= base_url('admin/users') ?>');
                if (usersResponse.ok) {
                    const users = await usersResponse.json();
                    document.getElementById('total-users').textContent = users.length;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Open animal modal
        function openAnimalModal(animalId = null) {
            const modal = document.getElementById('animalModal');
            const form = document.getElementById('animalForm');
            const title = document.getElementById('modal-title');
            
            form.reset();
            currentAnimalId = animalId;
            
            if (animalId) {
                const animal = animals.find(a => a.id == animalId);
                if (animal) {
                    title.textContent = 'Edit Animal';
                    document.getElementById('animal-id').value = animal.id;
                    document.getElementById('animal-name').value = animal.name;
                    document.getElementById('animal-category').value = animal.category;
                    document.getElementById('animal-age').value = animal.age;
                    document.getElementById('animal-gender').value = animal.gender;
                    document.getElementById('animal-price').value = animal.price;
                    document.getElementById('animal-description').value = animal.description || '';
                    
                    // Show current image
                    if (animal.image) {
                        document.getElementById('current-image').style.display = 'block';
                        document.getElementById('current-image-preview').src = 
                            '<?= base_url('uploads/') ?>' + animal.image;
                        document.getElementById('animal-image').removeAttribute('required');
                    }
                }
            } else {
                title.textContent = 'Add New Animal';
                document.getElementById('current-image').style.display = 'none';
                document.getElementById('animal-image').setAttribute('required', 'required');
            }
            
            modal.style.display = 'block';
        }

        // Close animal modal
        function closeAnimalModal() {
            document.getElementById('animalModal').style.display = 'none';
            currentAnimalId = null;
        }

        // Edit animal
        function editAnimal(id) {
            openAnimalModal(id);
        }

        // Delete animal
        async function deleteAnimal(id) {
            Swal.fire({
                title: 'Delete Animal?',
                text: 'Are you sure you want to delete this animal?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (!result.isConfirmed) return;

            try {
                const response = await fetch(`<?= base_url('admin/animals/') ?>${id}`, {
                    method: 'DELETE'
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                    loadAnimals();
                    loadStats();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Error deleting animal: ' + error.message, 'error');
            }
            });
        }

        // Handle animal form submission
        async function handleAnimalSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const url = currentAnimalId ? 
                `<?= base_url('admin/animals/') ?>${currentAnimalId}` : 
                '<?= base_url('admin/animals') ?>';
            
            const method = currentAnimalId ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                    closeAnimalModal();
                    loadAnimals();
                    loadStats();
                } else {
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).join(', ');
                        showAlert('Validation errors: ' + errorMessages, 'error');
                    } else {
                        showAlert(result.message, 'error');
                    }
                }
            } catch (error) {
                showAlert('Error saving animal: ' + error.message, 'error');
            }
        }

        // Show alert message
        function showAlert(message, type) {
            const alert = document.getElementById('alert');
            alert.className = `alert ${type}`;
            alert.textContent = message;
            alert.style.display = 'block';
            
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }

        // ========== USER MANAGEMENT FUNCTIONS ==========

        // Load users from API
        async function loadUsers() {
            const loading = document.getElementById('users-loading');
            const table = document.getElementById('users-table');
            
            loading.style.display = 'block';
            table.innerHTML = '';

            try {
                const response = await fetch('<?= base_url('admin/users') ?>');
                if (!response.ok) throw new Error('Failed to load users');
                
                users = await response.json();
                renderUsers();
            } catch (error) {
                showAlert('Error loading users: ' + error.message, 'error');
            } finally {
                loading.style.display = 'none';
            }
        }

        // Render users in table
        function renderUsers() {
            const table = document.getElementById('users-table');
            
            if (users.length === 0) {
                table.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 40px;">No users found.</td></tr>';
                return;
            }

            table.innerHTML = users.map(user => `
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px;">
                        <div style="font-weight: 600;">${user.name}</div>
                    </td>
                    <td style="padding: 15px;">${user.email}</td>
                    <td style="padding: 15px;">
                        <span style="background: ${getRoleColor(user.role)}; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; text-transform: uppercase;">
                            ${user.role}
                        </span>
                    </td>
                    <td style="padding: 15px;">
                        <div style="display: flex; gap: 8px;">
                            <button class="btn btn-edit" onclick="editUser(${user.id})" style="padding: 6px 12px; font-size: 12px;">Edit</button>
                            <button class="btn btn-delete" onclick="deleteUser(${user.id})" style="padding: 6px 12px; font-size: 12px;" 
                                ${user.id == <?= session()->get('user_id') ?> ? 'disabled title="Cannot delete yourself"' : ''}>Delete</button>
            </div>
                    </td>
                </tr>
            `).join('');
        }

        // Get role color
        function getRoleColor(role) {
            switch(role) {
                case 'admin': return '#dc3545';
                case 'staff': return '#007bff';
                case 'customer': return '#28a745';
                default: return '#6c757d';
            }
        }

        // Open user modal
        function openUserModal(userId = null) {
            const modal = document.getElementById('userModal');
            const form = document.getElementById('userForm');
            const title = document.getElementById('user-modal-title');
            const passwordField = document.getElementById('user-password');
            
            form.reset();
            currentUserId = userId;
            
            if (userId) {
                const user = users.find(u => u.id == userId);
                if (user) {
                    title.textContent = 'Edit User';
                    document.getElementById('user-id').value = user.id;
                    document.getElementById('user-name').value = user.name;
                    document.getElementById('user-email').value = user.email;
                    document.getElementById('user-role').value = user.role;
                    passwordField.removeAttribute('required');
                    passwordField.placeholder = 'Leave empty to keep current password';
                }
                } else {
                title.textContent = 'Add New User';
                passwordField.setAttribute('required', 'required');
                passwordField.placeholder = 'Enter password';
            }
            
            modal.style.display = 'block';
        }

        // Close user modal
        function closeUserModal() {
            document.getElementById('userModal').style.display = 'none';
            currentUserId = null;
        }

        // Edit user
        function editUser(id) {
            openUserModal(id);
        }

        // Delete user
        async function deleteUser(id) {
            if (id == <?= session()->get('user_id') ?>) {
                showAlert('You cannot delete your own account.', 'error');
                return;
            }

            Swal.fire({
                title: 'Delete User?',
                text: 'Are you sure you want to delete this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (!result.isConfirmed) return;

            try {
                const response = await fetch(`<?= base_url('admin/users/') ?>${id}`, {
                    method: 'DELETE'
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                    loadUsers();
                    loadStats();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Error deleting user: ' + error.message, 'error');
            }
            });
        }

        // Handle user form submission
        async function handleUserSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const url = currentUserId ? 
                `<?= base_url('admin/users/') ?>${currentUserId}` : 
                '<?= base_url('admin/users') ?>';
            
            const method = currentUserId ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                    closeUserModal();
                    loadUsers();
                    loadStats();
                } else {
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).join(', ');
                        showAlert('Validation errors: ' + errorMessages, 'error');
                    } else {
                        showAlert(result.message, 'error');
                    }
                }
            } catch (error) {
                showAlert('Error saving user: ' + error.message, 'error');
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const animalModal = document.getElementById('animalModal');
            const userModal = document.getElementById('userModal');
            
            if (event.target === animalModal) {
                closeAnimalModal();
            } else if (event.target === userModal) {
                closeUserModal();
            }
        }
    </script>
</body>
</html>