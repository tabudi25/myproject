<?php
// Include database connection
require_once 'db.php';
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Fluffy Planet — Admin</title>

    <style>
        /* --- Color variables (kept from original) --- */
        :root {
            --bg: #fff8f1;
            --card: #ffffff;
            --muted: #6b7280;
            --accent: black;
            --soft: #eef2ff;
            --shadow: 0 6px 18px rgba(16, 24, 40, 0.06);
            --radius-lg: 18px;
            --radius-md: 10px;
            --radius-sm: 8px;
            --container-max: 1200px;
            --text: black;
            --muted-2: #9aa3ad;
        }

        /* --- Reset / base --- */
        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%
        }

        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.35;
        }

        img {
            max-width: 100%;
            display: block
        }

        /* --- utility-like helpers used by JS (kept names used in scripts) --- */
        .hidden {
            display: none !important
        }

        .view {
            display: block
        }

        .thin-scroll {
            scrollbar-width: thin;
            -ms-overflow-style: auto
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            object-fit: cover
        }

        .pointer {
            cursor: pointer
        }

        /* small scrollbar style for webkit */
        .thin-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px
        }

        .thin-scroll::-webkit-scrollbar-thumb {
            background: rgba(124, 58, 237, 0.25);
            border-radius: 9999px
        }

        /* --- Layout containers --- */
        .min-h-screen {
            min-height: 100vh
        }

        .app-root {
            min-height: 100vh;
            display: flex
        }

        .container {
            max-width: var(--container-max);
            margin: 0 auto;
            padding-left: 18px;
            padding-right: 18px
        }

        /* --- Login screen --- */
        #loginScreen {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.6));
            backdrop-filter: blur(6px);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 32px;
        }

        .brand-row {
            display: flex;
            gap: 16px;
            align-items: center;
            margin-bottom: 18px
        }

        .brand-circle {
            width: 48px;
            height: 48px;
            border-radius: 9999px;
            background: var(--soft);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-weight: 700;
            font-size: 20px;
        }

        .login-card h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600
        }

        .login-card p {
            margin: 0;
            margin-top: 4px;
            color: var(--muted);
            font-size: 13px
        }

        .form-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 8px
        }

        .input {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            background: white;
            font-size: 14px;
            color: var(--text);
        }

        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .small {
            font-size: 13px;
            color: var(--muted)
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            background: var(--accent);
            color: white;
            border: 0;
            font-weight: 600;
            cursor: pointer;
        }

        .muted-note {
            font-size: 12px;
            color: var(--muted);
            margin-top: 12px
        }

        /* --- Sidebar --- */
        aside.sidebar {
            width: 288px;
            background: var(--card);
            padding: 24px;
            border-right: 1px solid rgba(0, 0, 0, 0.04);
            box-shadow: none;
            min-height: 100vh;
            flex-shrink: 0;
        }

        .sidebar .brand {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 18px
        }

        .sidebar h2 {
            margin: 0;
            font-weight: 700
        }

        .sidebar p {
            margin: 0;
            margin-top: 4px;
            font-size: 12px;
            color: var(--muted)
        }

        nav.nav {
            display: block
        }

        nav.nav .nav-list {
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        button.navBtn {
            text-align: left;
            padding: 10px 12px;
            border-radius: 10px;
            background: transparent;
            border: 0;
            cursor: pointer;
            font-size: 14px;
            color: var(--text);
        }

        button.navBtn:hover {
            background: #f7f7fb
        }

        .notif-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 9999px;
            background: var(--accent);
            color: white;
            font-size: 11px;
            margin-left: 8px;
            padding: 2px;
        }

        .sidebar .bottom {
            margin-top: 18px;
            border-top: 1px solid rgba(0, 0, 0, 0.04);
            padding-top: 16px;
            font-size: 13px;
            color: var(--muted)
        }

        /* --- Main content --- */
        main.content {
            flex: 1;
            padding: 32px;
            min-height: 100vh;
        }

        header.topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px
        }

        header.topbar h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700
        }

        header.topbar p {
            margin: 0;
            font-size: 13px;
            color: var(--muted)
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 16px
        }

        .admin-info .text-right {
            text-align: right
        }

        .admin-info .font-semibold {
            font-weight: 600
        }

        .text-xs {
            font-size: 12px;
            color: var(--muted)
        }

        /* --- Views container spacing --- */
        #views {
            display: block;
            gap: 24px
        }

        /* --- Dashboard stats grid --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 16px
        }

        .card {
            background: var(--card);
            padding: 16px;
            border-radius: 18px;
            box-shadow: var(--shadow);
            min-height: 72px;
        }

        .card .label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 6px
        }

        .card .value {
            font-size: 22px;
            font-weight: 700
        }

        /* --- dashboard lower area --- */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 16px
        }

        .card h3 {
            margin: 0;
            font-size: 15px;
            font-weight: 600
        }

        .recent-list {
            font-size: 13px;
            color: var(--muted);
            list-style: none;
            padding: 0;
            margin: 0;
            display: block
        }

        .recent-list li {
            margin-bottom: 8px
        }

        /* --- Tables / Lists --- */
        .panel {
            background: var(--card);
            border-radius: 18px;
            padding: 16px;
            box-shadow: var(--shadow)
        }

        .controls {
            display: flex;
            align-items: center;
            gap: 8px
        }

        .controls .small-input {
            padding: 8px;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            font-size: 14px
        }

        .btn {
            padding: 8px 10px;
            border-radius: 10px;
            border: 0;
            cursor: pointer
        }

        .btn-outline {
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 10px
        }

        .btn-accent {
            background: var(--accent);
            color: white;
            border-radius: 10px;
            padding: 8px 10px;
            border: 0;
            cursor: pointer
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px
        }

        table thead th {
            font-size: 13px;
            color: var(--muted);
            text-align: left;
            padding: 8px 6px
        }

        table tbody td {
            padding: 10px 6px;
            border-top: 1px solid rgba(0, 0, 0, 0.04);
            vertical-align: middle
        }

        /* user card / pets grid */
        .pets-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px
        }

        .pet-card {
            padding: 16px;
            border-radius: 18px;
            background: var(--card);
            box-shadow: var(--shadow)
        }

        .pet-card .pet-row {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 12px
        }

        .pet-card .pet-name {
            font-weight: 600
        }

        /* modal root (modals appended here) */
        #modalRoot .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            z-index: 30
        }

        .modal {
            width: 100%;
            max-width: 680px;
            background: var(--card);
            border-radius: 18px;
            padding: 20px;
            box-shadow: var(--shadow)
        }

        .modal h3 {
            margin: 0 0 10px 0;
            font-size: 18px
        }

        .modal .form-row {
            display: flex;
            flex-direction: column;
            gap: 8px
        }

        .modal .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px
        }

        .modal .btn-row {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 8px
        }

        .modal .btn-cancel {
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.08);
            padding: 8px 10px;
            border-radius: 10px;
            cursor: pointer
        }

        .modal .btn-save {
            background: var(--accent);
            color: white;
            padding: 8px 12px;
            border-radius: 10px;
            border: 0;
            cursor: pointer
        }

        /* small helpers used inside dynamic HTML strings */
        .font-semibold {
            font-weight: 600
        }

        .text-muted {
            color: var(--muted);
            font-size: 13px
        }

        .text-xs-muted {
            font-size: 12px;
            color: var(--muted)
        }

        .flex {
            display: flex
        }

        .items-center {
            align-items: center
        }

        .justify-between {
            justify-content: space-between
        }

        .gap-2 {
            gap: 8px
        }

        .gap-3 {
            gap: 12px
        }

        .mb-2 {
            margin-bottom: 8px
        }

        .mb-3 {
            margin-bottom: 12px
        }

        .mb-4 {
            margin-bottom: 16px
        }

        /* tiny button styles inside lists created by JS (edit/verify/mark) */
        .small-btn {
            padding: 8px;
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            cursor: pointer;
            background: white
        }

        .small-btn:hover {
            background: #fafafa
        }

        .markRead {
            padding: 6px;
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            cursor: pointer;
            background: white
        }

        /* responsive adjustments */
        @media (max-width: 980px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr)
            }

            .dashboard-grid {
                grid-template-columns: 1fr
            }

            .pets-grid {
                grid-template-columns: repeat(2, 1fr)
            }

            aside.sidebar {
                display: none
            }

            main.content {
                padding: 18px
            }
        }

        @media (max-width:560px) {
            .pets-grid {
                grid-template-columns: 1fr
            }

            .stats-grid {
                grid-template-columns: 1fr
            }
        }
    </style>
</head>

<body>

    <!-- Login Screen -->
    <div id="loginScreen" class="min-h-screen">
        <div class="login-card">
            <div class="brand-row">
                <div class="brand-circle">FP</div>
                <div>
                    <h1>Fluffy Planet Admin</h1>
                    <p>Sign in to manage pets, users, orders and reports</p>
                </div>
            </div>

            <form id="loginForm" class="form-stack">
                <input id="adminEmail" class="input" placeholder="Email" value="admin@fluffy.local" />
                <input id="adminPassword" type="password" class="input" placeholder="Password" value="password" />
                <div class="row-between" style="align-items:center;">
                    <label class="small"><input id="remember" type="checkbox" style="margin-right:8px;"> Remember
                        me</label>
                    <a href="#" style="color:var(--accent);font-size:13px;text-decoration:none">Forgot?</a>
                </div>
                <button type="submit" class="btn-primary">Sign in</button>
            </form>

            <p class="muted-note">Demo admin credentials: <strong>admin@fluffy.local / password</strong></p>
        </div>
    </div>

    <!-- Main Admin App -->
    <div id="app" class="hidden app-root">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-circle" style="width:48px;height:48px;font-size:20px;">FP</div>
                <div>
                    <h2>Fluffy Planet</h2>
                    <p>Admin Dashboard</p>
                </div>
            </div>

            <nav class="nav">
                <div class="nav-list">
                    <button data-view="dashboard" class="navBtn">Dashboard</button>
                    <button data-view="users" class="navBtn">Manage Users</button>
                    <button data-view="pets" class="navBtn">Manage Pets</button>
                    <button data-view="orders" class="navBtn">Orders & Sales</button>
                    <button data-view="customers" class="navBtn">Customer Records</button>
                    <button data-view="notifications" class="navBtn">Notifications <span id="notifCount"
                            class="notif-pill">0</span></button>
                </div>

                <div class="bottom">
                    <button id="logoutBtn" class="navBtn"
                        style="width:100%;text-align:left;padding:10px;border-radius:10px;background:transparent;border:0;cursor:pointer">Sign
                        out</button>
                </div>
            </nav>
        </aside>

        <!-- Content -->
        <main class="content">
            <!-- Top bar -->
            <header class="topbar">
                <div>
                    <h1 id="pageTitle">Dashboard</h1>
                    <p id="pageSubtitle" class="text-xs">Overview & recent activity</p>
                </div>
                <div class="admin-info">
                    <div class="text-right">
                        <div id="adminName" class="font-semibold">Admin</div>
                        <div class="text-xs">Super Admin</div>
                    </div>
                    <img src="https://i.pravatar.cc/40?img=12" class="avatar" alt="admin">
                </div>
            </header>

            <!-- Views container -->
            <section id="views">
                <!-- Dashboard View -->
                <section id="view-dashboard" class="view">
                    <div class="stats-grid">
                        <div class="card">
                            <div class="label">Total Pets</div>
                            <div id="statPets" class="value">0</div>
                        </div>
                        <div class="card">
                            <div class="label">Active Users</div>
                            <div id="statUsers" class="value">0</div>
                        </div>
                        <div class="card">
                            <div class="label">Total Orders</div>
                            <div id="statOrders" class="value">0</div>
                        </div>
                        <div class="card">
                            <div class="label">Sales (₱)</div>
                            <div id="statSales" class="value">0</div>
                        </div>
                    </div>

                    <div class="dashboard-grid">
                        <div class="card">
                            <div
                                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                                <h3>Monthly Sales (demo)</h3>
                                <div class="text-xs">Last 6 months</div>
                            </div>
                            <canvas id="chartSales" height="120"></canvas>
                        </div>

                        <div class="card">
                            <h3 style="margin-bottom:8px">Recent Activity</h3>
                            <ul id="recentActivity" class="recent-list thin-scroll"
                                style="max-height:192px;overflow-y:auto"></ul>
                        </div>
                    </div>
                </section>

                <!-- Users View -->
                <section id="view-users" class="view hidden">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <h2 style="font-size:18px;margin:0">Manage Users</h2>
                        <div style="display:flex;gap:8px;align-items:center">
                            <input id="userSearch" placeholder="Search users..." class="small-input">
                            <button id="addUserBtn" class="btn-accent">Add User</button>
                        </div>
                    </div>

                    <div class="panel">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:220px">Name</th>
                                    <th>Email</th>
                                    <th style="width:160px">Status</th>
                                    <th style="width:280px">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTable"></tbody>
                        </table>
                    </div>
                </section>

                <!-- Pets View -->
                <section id="view-pets" class="view hidden">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <h2 style="font-size:18px;margin:0">Manage Pets</h2>
                        <div style="display:flex;gap:8px;align-items:center">
                            <select id="petFilter" class="small-input">
                                <option value="all">All types</option>
                                <option value="dog">Dog</option>
                                <option value="cat">Cat</option>
                                <option value="bird">Bird</option>
                                <option value="other">Other</option>
                            </select>
                            <input id="petSearch" placeholder="Search pets..." class="small-input">
                            <button id="addPetBtn" class="btn-accent">Add Pet</button>
                        </div>
                    </div>

                    <div id="petsGrid" class="pets-grid"></div>
                </section>

                <!-- Orders View -->
                <section id="view-orders" class="view hidden">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <h2 style="font-size:18px;margin:0">Orders & Sales</h2>
                        <div style="display:flex;gap:8px;align-items:center">
                            <input id="orderSearch" placeholder="Search orders..." class="small-input">
                            <button id="exportCSV" class="btn-outline">Export CSV</button>
                        </div>
                    </div>

                    <div class="panel">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Pet</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTable"></tbody>
                        </table>
                    </div>
                </section>

                <!-- Customers View -->
                <section id="view-customers" class="view hidden">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <h2 style="font-size:18px;margin:0">Customer Records</h2>
                        <div style="display:flex;gap:8px;align-items:center">
                            <input id="customerSearch" placeholder="Search customers..." class="small-input">
                        </div>
                    </div>

                    <div class="panel">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Orders</th>
                                    <th>Lifetime Value (₱)</th>
                                </tr>
                            </thead>
                            <tbody id="customersTable"></tbody>
                        </table>
                    </div>
                </section>

                <!-- Notifications View -->
                <section id="view-notifications" class="view hidden">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <h2 style="font-size:18px;margin:0">Notifications</h2>
                        <div>
                            <button id="markAllRead" class="btn-outline">Mark all read</button>
                        </div>
                    </div>

                    <div class="panel">
                        <ul id="notificationsList" class="thin-scroll"
                            style="max-height:384px;overflow-y:auto;padding:0;margin:0;list-style:none"></ul>
                    </div>
                </section>

            </section>
        </main>
    </div>

    <!-- Modals (User / Pet) -->
    <div id="modalRoot"></div>

    <!-- Scripts (unchanged logic) -->
    <script>
        // --- Demo localStorage structure ---
        const LS_KEYS = { users: 'fp_users', pets: 'fp_pets', orders: 'fp_orders', notifications: 'fp_notifs' };

        // Helper: sample demo data that seeds storage first time
        function seedDemo() {
            if (!localStorage.getItem(LS_KEYS.users)) {
                const users = [
                    { id: 1, name: 'Admin', email: 'admin@fluffy.local', role: 'admin', status: 'active', verified: true },
                    { id: 2, name: 'Jane Doe', email: 'jane@example.com', role: 'staff', status: 'active', verified: true },
                    { id: 3, name: 'John Buyer', email: 'john@buy.com', role: 'customer', status: 'active', verified: false }
                ];
                localStorage.setItem(LS_KEYS.users, JSON.stringify(users));
            }
            if (!localStorage.getItem(LS_KEYS.pets)) {
                const pets = [
                    { id: 1, name: 'Mochi', type: 'cat', age: 2, price: 4500, status: 'available', image: 'https://placedog.net/400?random=1', addedBy: 2 },
                    { id: 2, name: 'Pico', type: 'dog', age: 1, price: 5500, status: 'available', image: 'https://placedog.net/401?random=2', addedBy: 2 }
                ];
                localStorage.setItem(LS_KEYS.pets, JSON.stringify(pets));
            }
            if (!localStorage.getItem(LS_KEYS.orders)) {
                const orders = [
                    { id: 1, orderNo: 'ORD001', customerId: 3, petId: 1, amount: 4500, date: '2025-09-28', status: 'completed' }
                ];
                localStorage.setItem(LS_KEYS.orders, JSON.stringify(orders));
            }
            if (!localStorage.getItem(LS_KEYS.notifications)) {
                const notifs = [
                    { id: 1, message: 'Staff Jane added new pet Mochi', time: Date.now() - 1000 * 60 * 60 * 24, read: false }
                ];
                localStorage.setItem(LS_KEYS.notifications, JSON.stringify(notifs));
            }
        }

        // --- Basic state & util functions ---
        function uid() { return Date.now() + Math.floor(Math.random() * 999) }
        function read(key) { return JSON.parse(localStorage.getItem(key) || '[]') }
        function write(key, val) { localStorage.setItem(key, JSON.stringify(val)); }

        // --- App initialization ---
        seedDemo();

        // Simple auth (demo)
        const loginScreen = document.getElementById('loginScreen');
        const app = document.getElementById('app');
        document.getElementById('loginForm').addEventListener('submit', e => {
            e.preventDefault();
            // demo: check default admin credentials
            const email = document.getElementById('adminEmail').value;
            const pass = document.getElementById('adminPassword').value;
            if (email === 'admin@fluffy.local' && pass === 'password') {
                loginScreen.classList.add('hidden'); app.classList.remove('hidden');
                initApp();
            } else {
                alert('Invalid demo credentials. Use admin@fluffy.local / password')
            }
        });

        // Logout
        document.getElementById('logoutBtn').addEventListener('click', () => { location.reload(); });

        // Navigation
        document.querySelectorAll('.navBtn').forEach(b => b.addEventListener('click', () => {
            const view = b.dataset.view;
            showView(view);
        }));

        function showView(name) {
            document.querySelectorAll('.view').forEach(v => v.classList.add('hidden'));
            const el = document.getElementById('view-' + name);
            if (el) el.classList.remove('hidden');
            document.getElementById('pageTitle').innerText = name.charAt(0).toUpperCase() + name.slice(1).replace('-', ' ');
        }

        // Init App after login
        function initApp() {
            refreshAll();
            // Wire actions
            document.getElementById('addUserBtn').addEventListener('click', () => openUserModal());
            document.getElementById('addPetBtn').addEventListener('click', () => openPetModal());
            document.getElementById('markAllRead').addEventListener('click', markAllNotifs);
            document.getElementById('exportCSV').addEventListener('click', exportOrdersCSV);
            // basic search filters
            document.getElementById('userSearch').addEventListener('input', renderUsers);
            document.getElementById('petSearch').addEventListener('input', renderPets);
            document.getElementById('petFilter').addEventListener('change', renderPets);
            document.getElementById('orderSearch').addEventListener('input', renderOrders);
            document.getElementById('customerSearch').addEventListener('input', renderCustomers);
            // default view
            showView('dashboard');
            // load chart lib dynamically
            loadChart();
        }

        // --- Render functions ---
        function refreshAll() { renderStats(); renderRecent(); renderUsers(); renderPets(); renderOrders(); renderCustomers(); renderNotifications(); }

        function renderStats() {
            const pets = read(LS_KEYS.pets); const users = read(LS_KEYS.users); const orders = read(LS_KEYS.orders);
            const sales = orders.reduce((s, o) => s + (o.amount || 0), 0);
            document.getElementById('statPets').innerText = pets.length;
            document.getElementById('statUsers').innerText = users.filter(u => u.status === 'active').length;
            document.getElementById('statOrders').innerText = orders.length;
            document.getElementById('statSales').innerText = sales.toLocaleString();
        }

        function renderRecent() {
            const notifs = read(LS_KEYS.notifications).slice().reverse();
            const el = document.getElementById('recentActivity'); el.innerHTML = '';
            notifs.slice(0, 6).forEach(n => {
                const li = document.createElement('li'); li.innerText = `${new Date(n.time).toLocaleString()}: ${n.message}`; el.appendChild(li);
            });
        }

        function renderUsers() {
            const q = document.getElementById('userSearch').value.toLowerCase();
            const users = read(LS_KEYS.users).filter(u => [u.name, u.email].join(' ').toLowerCase().includes(q));
            const tbody = document.getElementById('usersTable'); tbody.innerHTML = '';
            users.forEach(u => {
                const tr = document.createElement('tr'); tr.className = 'py-3';
                tr.innerHTML = `<td class='py-3'><div class='font-semibold'>${u.name}</div><div class='text-xs-muted'>${u.role}</div></td><td>${u.email}</td><td>${u.status}${u.verified ? " • verified" : ""}</td><td><div style="display:flex;gap:8px"><button class='editUser small-btn' data-id='${u.id}'>Edit</button><button class='verifyUser small-btn' data-id='${u.id}'>Verify</button><button class='toggleUser small-btn' data-id='${u.id}'>Toggle Active</button></div></td>`;
                tbody.appendChild(tr);
            });
            // attach handlers
            tbody.querySelectorAll('.editUser').forEach(b => b.addEventListener('click', () => openUserModal(b.dataset.id)));
            tbody.querySelectorAll('.verifyUser').forEach(b => b.addEventListener('click', () => verifyUser(b.dataset.id)));
            tbody.querySelectorAll('.toggleUser').forEach(b => b.addEventListener('click', () => toggleUserStatus(b.dataset.id)));
        }

        function renderPets() {
            const q = document.getElementById('petSearch').value.toLowerCase();
            const filter = document.getElementById('petFilter').value;
            const pets = read(LS_KEYS.pets).filter(p => (filter === 'all' || p.type === filter) && [p.name, p.type].join(' ').toLowerCase().includes(q));
            const grid = document.getElementById('petsGrid'); grid.innerHTML = '';
            pets.forEach(p => {
                const card = document.createElement('div'); card.className = 'pet-card';
                card.innerHTML = `
          <div class='pet-row'>
            <img src='${p.image}' style='width:64px;height:64px;border-radius:10px;object-fit:cover' />
            <div>
              <div class='pet-name'>${p.name}</div>
              <div class='text-xs-muted'>${p.type} • ${p.age} yrs</div>
            </div>
          </div>
          <div style='display:flex;align-items:center;justify-content:space-between'>
            <div class='font-semibold'>₱${p.price.toLocaleString()}</div>
            <div style='display:flex;gap:8px'>
              <button class='editPet small-btn' data-id='${p.id}'>Edit</button>
             
            </div>
          </div>
        `;
                grid.appendChild(card);
            });
            document.querySelectorAll('.editPet').forEach(b => b.addEventListener('click', () => openPetModal(b.dataset.id)));
            document.querySelectorAll('.togglePet').forEach(b => b.addEventListener('click', () => togglePetStatus(b.dataset.id)));
        }

        function renderOrders() {
            const q = document.getElementById('orderSearch').value.toLowerCase();
            const orders = read(LS_KEYS.orders).filter(o => [o.orderNo].join(' ').toLowerCase().includes(q));
            const tbody = document.getElementById('ordersTable'); tbody.innerHTML = '';
            const users = read(LS_KEYS.users); const pets = read(LS_KEYS.pets);
            orders.forEach(o => {
                const customer = users.find(u => u.id === o.customerId) || { name: 'Unknown' };
                const pet = pets.find(p => p.id === o.petId) || { name: 'Unknown' };
                const tr = document.createElement('tr'); tr.innerHTML = `<td>${o.orderNo}</td><td>${customer.name}</td><td>${pet.name}</td><td>${o.date}</td><td>₱${o.amount.toLocaleString()}</td><td>${o.status}</td>`;
                tbody.appendChild(tr);
            });
        }

        function renderCustomers() {
            const q = document.getElementById('customerSearch').value.toLowerCase();
            const users = read(LS_KEYS.users).filter(u => u.role === 'customer' && [u.name, u.email].join(' ').toLowerCase().includes(q));
            const tbody = document.getElementById('customersTable'); tbody.innerHTML = '';
            const orders = read(LS_KEYS.orders);
            users.forEach(u => {
                const userOrders = orders.filter(o => o.customerId === u.id);
                const total = userOrders.reduce((s, o) => s + (o.amount || 0), 0);
                const tr = document.createElement('tr'); tr.innerHTML = `<td>${u.name}</td><td>${u.email}</td><td>${userOrders.length}</td><td>₱${total.toLocaleString()}</td>`;
                tbody.appendChild(tr);
            });
        }

        function renderNotifications() {
            const notifs = read(LS_KEYS.notifications).slice().reverse();
            const list = document.getElementById('notificationsList'); list.innerHTML = '';
            let un = 0;
            notifs.forEach(n => {
                if (!n.read) un++;
                const li = document.createElement('li'); li.className = 'p-3 border rounded-lg';
                li.style = 'padding:12px;border-radius:12px;border:1px solid rgba(0,0,0,0.04);display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;background:white';
                li.innerHTML = `<div><div style="font-weight:600">${n.message}</div><div class='text-xs-muted'>${new Date(n.time).toLocaleString()}</div></div><div><button class='markRead' data-id='${n.id}'>${n.read ? 'Read' : 'Mark'}</button></div>`;
                list.appendChild(li);
            });
            document.getElementById('notifCount').innerText = un;
            document.querySelectorAll('.markRead').forEach(b => b.addEventListener('click', () => markNotifRead(b.dataset.id)));
        }

        // --- User actions ---
        function openUserModal(id) {
            const users = read(LS_KEYS.users);
            const user = id ? users.find(u => u.id == id) : { id: null, name: '', email: '', role: 'customer', status: 'active', verified: false };
            const modal = document.createElement('div'); modal.className = 'modal-backdrop';
            modal.innerHTML = `
        <div class='modal'>
          <h3>${user.id ? 'Edit' : 'Add'} User</h3>
          <div class='form-row'>
            <input id='m_name' class='input' placeholder='Name' value='${user.name}' />
            <input id='m_email' class='input' placeholder='Email' value='${user.email}' />
            <select id='m_role' class='input'>
              <option value='customer' ${user.role === 'customer' ? 'selected' : ''}>Customer</option>
              <option value='staff' ${user.role === 'staff' ? 'selected' : ''}>Staff</option>
              <option value='admin' ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
            </select>
            <div class='btn-row'>
              <button id='cancelUser' class='btn-cancel'>Cancel</button>
              <button id='saveUser' class='btn-save'>Save</button>
            </div>
          </div>
        </div>
      `;
            document.getElementById('modalRoot').appendChild(modal);
            modal.querySelector('#cancelUser').addEventListener('click', () => modal.remove());
            modal.querySelector('#saveUser').addEventListener('click', () => {
                const name = modal.querySelector('#m_name').value.trim();
                const email = modal.querySelector('#m_email').value.trim();
                const role = modal.querySelector('#m_role').value;
                if (!name || !email) { alert('Name and email required'); return; }
                if (user.id) {
                    const us = read(LS_KEYS.users).map(u => u.id === user.id ? { ...u, name, email, role } : u);
                    write(LS_KEYS.users, us);
                } else {
                    const us = read(LS_KEYS.users);
                    us.push({ id: uid(), name, email, role, status: 'active', verified: false });
                    write(LS_KEYS.users, us);
                }
                modal.remove(); refreshAll();
            });
        }

        function verifyUser(id) {
            const users = read(LS_KEYS.users).map(u => u.id == id ? { ...u, verified: true } : u);
            write(LS_KEYS.users, users); refreshAll();
            addNotification('Customer verified ID ' + id);
        }
        function toggleUserStatus(id) {
            const users = read(LS_KEYS.users).map(u => u.id == id ? { ...u, status: u.status === 'active' ? 'inactive' : 'active' } : u);
            write(LS_KEYS.users, users); refreshAll();
        }

        // --- Pets actions ---
        function openPetModal(id) {
            const pets = read(LS_KEYS.pets);
            const pet = id ? pets.find(p => p.id == id) : { id: null, name: '', type: 'dog', age: 1, price: 0, status: 'available', image: '' };
            const modal = document.createElement('div'); modal.className = 'modal-backdrop';
            modal.innerHTML = `
        <div class='modal'>
          <h3>${pet.id ? 'Edit' : 'Add'} Pet</h3>
          <div class='form-row'>
            <input id='p_name' class='input' placeholder='Pet name' value='${pet.name}' />
            <div class='grid-2'>
              <select id='p_type' class='input'><option value='dog'>Dog</option><option value='cat'>Cat</option><option value='bird'>Bird</option><option value='other'>Other</option></select>
              <input id='p_age' class='input' placeholder='Age' value='${pet.age}' />
            </div>
            <input id='p_price' class='input' placeholder='Price' value='${pet.price}' />
            <input id='p_image' class='input' placeholder='Image URL' value='${pet.image}' />
            <div class='btn-row'>
              <button id='cancelPet' class='btn-cancel'>Cancel</button>
              <button id='savePet' class='btn-save'>Save</button>
            </div>
          </div>
        </div>
      `;
            document.getElementById('modalRoot').appendChild(modal);
            // set type selected
            modal.querySelector('#p_type').value = pet.type;
            modal.querySelector('#cancelPet').addEventListener('click', () => modal.remove());
            modal.querySelector('#savePet').addEventListener('click', () => {
                const name = modal.querySelector('#p_name').value.trim();
                const type = modal.querySelector('#p_type').value;
                const age = Number(modal.querySelector('#p_age').value) || 0;
                const price = Number(modal.querySelector('#p_price').value) || 0;
                const image = modal.querySelector('#p_image').value.trim() || 'https://placekitten.com/400/400';
                if (!name) { alert('Name required'); return; }
                if (pet.id) {
                    const ps = read(LS_KEYS.pets).map(p => p.id == pet.id ? { ...p, name, type, age, price, image } : p);
                    write(LS_KEYS.pets, ps);
                } else {
                    const ps = read(LS_KEYS.pets);
                    const newPet = { id: uid(), name, type, age, price, image, status: 'available', addedBy: 2 };
                    ps.push(newPet); write(LS_KEYS.pets, ps);
                    addNotification(`Staff added new pet ${name}`);
                }
                modal.remove(); refreshAll();
            });
        }

        function togglePetStatus(id) {
            const pets = read(LS_KEYS.pets).map(p => p.id == id ? { ...p, status: p.status === 'available' ? 'inactive' : 'available' } : p);
            write(LS_KEYS.pets, pets); refreshAll();
        }

        // --- Notifications ---
        function addNotification(message) {
            const notifs = read(LS_KEYS.notifications);
            notifs.push({ id: uid(), message, time: Date.now(), read: false });
            write(LS_KEYS.notifications, notifs); renderNotifications(); renderRecent();
        }
        function markNotifRead(id) {
            const notifs = read(LS_KEYS.notifications).map(n => n.id == id ? { ...n, read: true } : n);
            write(LS_KEYS.notifications, notifs); renderNotifications();
        }
        function markAllNotifs() {
            const notifs = read(LS_KEYS.notifications).map(n => ({ ...n, read: true })); write(LS_KEYS.notifications, notifs); renderNotifications();
        }

        // --- Export CSV ---
        function exportOrdersCSV() {
            const orders = read(LS_KEYS.orders); const pets = read(LS_KEYS.pets); const users = read(LS_KEYS.users);
            const rows = [['OrderNo', 'Customer', 'Pet', 'Date', 'Amount', 'Status']];
            orders.forEach(o => {
                const c = users.find(u => u.id === o.customerId) || { name: 'Unknown' }; const p = pets.find(x => x.id === o.petId) || { name: 'Unknown' };
                rows.push([o.orderNo, c.name, p.name, o.date, o.amount, o.status]);
            });
            const csv = rows.map(r => r.map(cell => '"' + String(cell).replace(/"/g, '""') + '"').join(',')).join('\n');
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = 'fluffy_orders.csv'; a.click(); URL.revokeObjectURL(url);
        }

        // --- Utilities ---
        window.addEventListener('storage', () => refreshAll());

        // --- Chart ---
        function loadChart() {
            if (window.Chart) return drawChart();
            const s = document.createElement('script'); s.src = 'https://cdn.jsdelivr.net/npm/chart.js'; s.onload = drawChart; document.body.appendChild(s);
        }
        function drawChart() {
            const ctx = document.getElementById('chartSales').getContext('2d');
            const orders = read(LS_KEYS.orders);
            // fake monthly aggregation for demo
            const months = 6; const labels = []; const data = [];
            for (let i = months - 1; i >= 0; i--) { const d = new Date(); d.setMonth(d.getMonth() - i); labels.push(d.toLocaleString('default', { month: 'short' })); }
            for (let i = 0; i < labels.length; i++) data.push(Math.floor(Math.random() * 10000));
            new Chart(ctx, { type: 'line', data: { labels, datasets: [{ label: 'Sales', data, fill: true, tension: 0.4 }] }, options: { responsive: true, plugins: { legend: { display: false } } } });
        }

        // --- Initial state when page loads while hidden (to show correct notif count) ---
        document.addEventListener('DOMContentLoaded', () => {
            const notifs = read(LS_KEYS.notifications) || []; const un = notifs.filter(n => !n.read).length; document.getElementById('notifCount').innerText = un;
        });

        // --- After login show default stats ---
    </script>
</body>

</html>