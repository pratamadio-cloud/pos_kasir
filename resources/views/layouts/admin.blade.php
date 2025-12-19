<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title', 'POS Kasir')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --sidebar-width: 250px;
            --sidebar-collapsed: 70px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, #2b2d42 0%, #1a1c2b 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }
        
        .sidebar-header {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar-brand .logo-icon {
            color: #4cc9f0;
            font-size: 1.8rem;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
        }
        
        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Sidebar Menu */
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .nav-item {
            margin-bottom: 5px;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #4cc9f0;
        }
        
        .nav-link.active {
            color: white;
            background: rgba(76, 201, 240, 0.2);
            border-left-color: #4cc9f0;
        }
        
        .nav-link i {
            font-size: 1.2rem;
            min-width: 24px;
            text-align: center;
        }
        
        .nav-text {
            transition: opacity 0.3s;
        }
        
        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: margin-left 0.3s;
            min-height: 100vh;
        }
        
        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed);
        }
        
        /* Top Navbar */
        .top-navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title h3 {
            margin: 0;
            color: #2b2d42;
        }
        
        .page-title p {
            margin: 5px 0 0 0;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            color: #2b2d42;
            margin: 0;
        }
        
        .user-role {
            color: #6c757d;
            font-size: 0.85rem;
            margin: 0;
        }
        
        /* Cards */
        .dashboard-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        
        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        
        /* Stats Cards */
        .stats-card-1 .card-icon { background-color: rgba(67, 97, 238, 0.1); color: #4361ee; }
        .stats-card-2 .card-icon { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
        .stats-card-3 .card-icon { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
        .stats-card-4 .card-icon { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
        
        /* Table */
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }
        
        /* Buttons */
        .btn-admin {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }
        
        .btn-admin:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        /* Footer */
        .admin-footer {
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
            margin-top: 40px;
            color: #6c757d;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed);
            }
            
            .sidebar:not(.collapsed) {
                width: var(--sidebar-width);
            }
            
            .main-content {
                margin-left: var(--sidebar-collapsed);
            }
            
            .sidebar:not(.collapsed) ~ .main-content {
                margin-left: var(--sidebar-width);
            }
            
            .nav-text {
                display: none;
            }
            
            .sidebar:not(.collapsed) .nav-text {
                display: inline;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <a href="/admin/dashboard" class="sidebar-brand">
                <i class="bi bi-speedometer2 logo-icon"></i>
                <span class="nav-text">Admin POS</span>
            </a>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
        </div>
        
        <!-- Sidebar Menu -->
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link @if(Request::is('admin/dashboard')) active @endif" 
                       href="/admin/dashboard">
                        <i class="bi bi-speedometer2"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                
                <!-- PERBAIKAN: Menu Produk untuk Admin -->
                <li class="nav-item">
                    <a class="nav-link @if(Request::is('admin/products*')) active @endif" 
                       href="{{ route('admin.products.index') }}">
                        <i class="bi bi-box-seam"></i>
                        <span class="nav-text">Manajemen Produk</span>
                    </a>
                </li>
                
                <!-- Menu Transaksi -->
                <li class="nav-item">
                    <a class="nav-link @if(Request::is('admin/transactions*')) active @endif" 
                       href="/admin/transactions">
                        <i class="bi bi-receipt"></i>
                        <span class="nav-text">Transaksi</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link @if(Request::is('admin/reports*')) active @endif" 
                       href="/admin/reports">
                        <i class="bi bi-graph-up"></i>
                        <span class="nav-text">Laporan</span>
                    </a>
                </li>
                
                <!-- Divider -->
                <li class="nav-item mt-4">
                    <div class="nav-link text-white small">
                        <span class="nav-text">AKSI CEPAT</span>
                    </div>
                </li>
                
                <!-- PERBAIKAN: Link ke halaman produk kasir (bukan admin) -->
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">
                        <i class="bi bi-cart"></i>
                        <span class="nav-text">Produk (Kasir)</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="/pos">
                        <i class="bi bi-cash-register"></i>
                        <span class="nav-text">Ke Kasir</span>
                    </a>
                </li> --}}
                
                   <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="nav-link text-danger">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="nav-text">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
      <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="page-title">
                <h3>@yield('title', 'Dashboard Admin')</h3>
                <p>@yield('subtitle', 'Ringkasan statistik dan aktivitas sistem')</p>
            </div>
            
            <div class="user-menu">
                <div class="user-info">
                    <p class="user-name">Administrator</p>
                    <p class="user-role">
                        {{ auth()->user()->roleLabel() }}
                    </p>
                </div>
                <div class="user-avatar">
                    <i class="bi bi-person-circle" style="font-size: 2rem; color: #4361ee;"></i>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        @yield('content')
        
        <!-- Footer -->
        <footer class="admin-footer">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">
                        &copy; 2025 POS Kasir System v2.0
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">
                        <i class="bi bi-server me-1"></i> Status: 
                        <span class="text-success">Online</span> 
                        | Terakhir update: <span id="lastUpdate">{{ date('H:i:s') }}</span>
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mainContent = document.getElementById('mainContent');
        
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            
            // Update icon
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('bi-list');
                icon.classList.add('bi-arrow-right');
            } else {
                icon.classList.remove('bi-arrow-right');
                icon.classList.add('bi-list');
            }
        });
        
        // Update last update time
        function updateLastUpdateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('lastUpdate').textContent = timeString;
        }
        
        // Update every minute
        setInterval(updateLastUpdateTime, 60000);
        
        // Auto collapse sidebar on mobile
        function handleResize() {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                sidebarToggle.querySelector('i').classList.remove('bi-list');
                sidebarToggle.querySelector('i').classList.add('bi-arrow-right');
            } else {
                sidebar.classList.remove('collapsed');
                sidebarToggle.querySelector('i').classList.remove('bi-arrow-right');
                sidebarToggle.querySelector('i').classList.add('bi-list');
            }
        }
        
        // Initial check
        handleResize();
        
        // Listen for resize
        window.addEventListener('resize', handleResize);
        
        // Set active menu based on current URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                // Check both href and route
                const href = link.getAttribute('href');
                if (href === currentPath || 
                    (currentPath.includes('/admin/products') && href.includes('/admin/products')) ||
                    (currentPath.includes('/products') && href.includes('/products') && !href.includes('/admin'))) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>