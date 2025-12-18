<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POS Kasir</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, rgb(255, 255, 255) 0%, rgb(255, 255, 255) 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .login-body {
            padding: 30px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
        }
        
        .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
        }
        
        .btn-login:hover {
            opacity: 0.9;
            color: white;
        }
        
        .login-icon {
            font-size: 4rem;
            color: white;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-cart-check login-icon"></i>
            <h3>POS KASIR</h3>
            <p class="mb-0">Sistem Point of Sale Modern</p>
        </div>
        
        <div class="login-body">

            {{-- code untuk pesan password salah --}}

            @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


            <form action="{{ route('login.post') }}" method="POST">
             @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" name="email" class="form-control" placeholder="example@gmail.com">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" class="form-control" placeholder="insert password">
                        <button class="btn btn-outline-secondary" type="button" 
                                id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <!-- <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">
                        Ingat saya
                    </label>
                </div> -->

                <!-- Login Button -->
                <button type="submit" class="btn btn-login mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login ke Sistem
                </button>

                <!-- Copyright -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        © 2025 POS Kasir <br>
                        <!-- <a href="#" class="text-decoration-none">Bantuan</a> • 
                        <a href="#" class="text-decoration-none">Lupa Password</a> -->
                    </small>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
        
    </script>
</body>
</html>