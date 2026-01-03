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
        
        .navbar-nav .nav-link{
            color: #000 !important;
            
        }
        /* STYLE UNTUK NAVBAR AKTIF */
        .nav-link.active {
            background-color: var(--primary-color) !important;
            color: white !important;
            border-radius: 8px;
        }
        
        .nav-link:hover:not(.active) {
            background-color: rgba(67, 97, 238, 0.1);
            border-radius: 8px;
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

        .clock-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 3px;
            display: inline-block;
        }
        
        #live-clock {
            font-size: 1rem;
            font-weight: 600;
            color: rgb(48, 48, 48);
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
                    <li class="nav-item me-2">
                        <a class="nav-link  {{ request()->is('pos') ? 'active' : '' }}" href="/pos">
                            <i class="bi bi-cash-coin me-2"></i>POS
                        </a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link  {{ request()->is('products') || request()->is('products/*') ? 'active' : '' }}" href="/products">
                            <i class="bi bi-box-seam me-2"></i>Produk
                        </a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link  {{ request()->is('transactions/today') ? 'active' : '' }}" href="/transactions/today">
                            <i class="bi bi-receipt me-2"></i>Transaksi Hari Ini
                        </a>
                    </li>
                </ul>

                {{-- Live Clock --}}
                <div class="me-2">
                    <div class="clock-container">
                       <div id="live-clock">--:--:--</div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-md dropdown-toggle" type="button" 
                            data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>Kasir: {{ auth()->user()->roleLabel() }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-header">
                            <small class="text-muted">Masuk sebagai</small>
                            <div class="fw-bold">
                                {{ auth()->user()->roleLabel() }}
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <center>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                            </form>
                        </center>
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
        // FUNGSI 1: Live Clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const day = now.toLocaleDateString('id-ID', { weekday: 'long' });
            const date = now.toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric' 
            });
            document.getElementById('live-clock').textContent = 
                `${hours}:${minutes}:${seconds} | ${date}`;
        }
        
        // Update clock setiap detik
        updateClock();
        setInterval(updateClock, 1000);

        // FUNGSI 2: Format angka ke Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }
        
        // FUNGSI 3: Menghitung total transaksi
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(item => {
                total += parseFloat(item.textContent.replace(/[^0-9.-]+/g, ""));
            });
            document.getElementById('total-amount').textContent = formatRupiah(total);
            document.getElementById('change-amount').textContent = formatRupiah(0);
        }
        
        // FUNGSI 4: Update waktu di navbar (versi singkat)
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
            
            // Hitung total awal jika ada halaman yang memerlukan
            if (document.querySelectorAll('.subtotal').length > 0) {
                calculateTotal();
            }
            
            // Update waktu navbar
            updateNavbarTime();
            setInterval(updateNavbarTime, 60000);
            
            // FUNGSI 5: Highlight navbar aktif berdasarkan URL
            function highlightActiveNav() {
                const currentPath = window.location.pathname;
                document.querySelectorAll('.nav-link').forEach(link => {
                    // Hapus kelas active dari semua link
                    link.classList.remove('active');
                    
                    // Cek apakah URL saat ini sesuai dengan href link
                    if (link.getAttribute('href') === currentPath) {
                        link.classList.add('active');
                    }
                    
                    // Untuk URL dengan subpath (misal: /products/1)
                    if (currentPath.startsWith('/products') && link.getAttribute('href') === '/products') {
                        link.classList.add('active');
                    }
                });
            }
            
            // Jalankan fungsi highlight saat halaman dimuat
            highlightActiveNav();
        });
    </script>
    
    @yield('scripts')
</body>
</html>