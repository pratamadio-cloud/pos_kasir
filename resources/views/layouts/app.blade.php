<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Kasir - @yield('title', 'Dashboard')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-color: #f8f9fa;
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .pos-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        
        .pos-item:last-child {
            border-bottom: none;
        }
        
        .cart-total {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .receipt {
            width: 100%;
            max-width: 300px;
            font-family: 'Courier New', monospace;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            .receipt {
                max-width: 100% !important;
                font-size: 14px !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
   <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="bi bi-cart-check me-2"></i>POS KASIR
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item me-4">  <!-- Jarak kanan 4 unit -->
                    <a class="nav-link" href="/pos">
                        <i class="bi bi-person me-2"></i>Kasir
                    </a>
                </li>
                <li class="nav-item me-4">  <!-- Jarak kanan 4 unit -->
                    <a class="nav-link" href="/products">
                        <i class="bi bi-box-seam me-2"></i>Produk
                    </a>
                </li>
                <li class="nav-item">  <!-- Item terakhir tidak perlu margin kanan -->
                    <a class="nav-link" href="/transactions">
                        <i class="bi bi-clock-history me-2"></i>Riwayat
                    </a>
                </li>
            </ul>
            
            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                        data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i>Kasir: Admin
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">
                        <small class="text-muted">Logged in as</small>
                        <div class="fw-bold">Kasir Admin</div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="/admin-login">
                            <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="/logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        @yield('content')
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Fungsi untuk format angka ke Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }
        
        // Fungsi untuk menghitung total
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(item => {
                total += parseFloat(item.textContent.replace(/[^0-9.-]+/g, ""));
            });
            document.getElementById('total-amount').textContent = formatRupiah(total);
            document.getElementById('change-amount').textContent = formatRupiah(0);
        }
        
        // Update time in navbar
        function updateNavbarTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit'
            });
            const timeElements = document.querySelectorAll('.navbar-time');
            timeElements.forEach(element => {
                element.textContent = timeString;
            });
        }
        
        // Event listener untuk DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi tooltip Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Hitung total awal
            calculateTotal();
            
            // Update time
            updateNavbarTime();
            setInterval(updateNavbarTime, 60000);
        });
    </script>
    
    @yield('scripts')
</body>
</html>