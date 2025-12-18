<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengelola - Rumah Gas dan Galon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #dc3545; /* Merah untuk gas */
            --secondary-color: #198754; /* Hijau untuk galon */
            --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        
        body {
            background: var(--gradient-primary);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.05) 0%, transparent 50%);
            z-index: -1;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 
                0 15px 35px rgba(0, 0, 0, 0.25),
                0 5px 15px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo {
            background: var(--gradient-primary);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 42px;
            font-weight: bold;
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .logo::before {
            content: '';
            position: absolute;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            top: 10px;
            left: 10px;
        }
        
        .logo i {
            position: relative;
            z-index: 1;
        }
        
        .brand-name {
            font-size: 14px;
            color: var(--primary-color);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .form-control {
            border-radius: 12px;
            padding: 15px 20px;
            border: 2px solid #e8e8e8;
            transition: all 0.3s;
            font-size: 15px;
            background: #f9f9f9;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
            background: white;
        }
        
        .input-group-text {
            background: #f9f9f9;
            border: 2px solid #e8e8e8;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #6c757d;
        }
        
        .password-toggle {
            cursor: pointer;
            background: #f9f9f9;
            border: 2px solid #e8e8e8;
            border-left: none;
            border-radius: 0 12px 12px 0;
            color: #6c757d;
            transition: all 0.3s;
        }
        
        .password-toggle:hover {
            background: #e9ecef;
            color: var(--primary-color);
        }
        
        .btn-login {
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 15px;
            width: 100%;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
            color: white;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .btn-login:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
        
        .form-check-label {
            color: #495057;
            font-size: 14px;
        }
        
        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .forgot-link:hover {
            color: #bb2d3b;
            text-decoration: underline;
        }
        
        .register-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .register-link:hover {
            color: #bb2d3b;
            text-decoration: underline;
        }
        
        .back-link {
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: var(--primary-color);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .floating-element {
            position: absolute;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            animation: float 8s ease-in-out infinite;
            z-index: -1;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .floating-element:nth-child(1) {
            top: 15%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            top: 65%;
            right: 12%;
            animation-delay: 2s;
            width: 40px;
            height: 40px;
        }
        
        .floating-element:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
            width: 50px;
            height: 50px;
        }
        
        @keyframes float {
            0%, 100% { 
                transform: translateY(0) rotate(0deg) scale(1); 
            }
            50% { 
                transform: translateY(-25px) rotate(180deg) scale(1.1); 
            }
        }
        
        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }
            
            .logo {
                width: 80px;
                height: 80px;
                font-size: 34px;
            }
        }
        
        .login-footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <!-- Floating background elements -->
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    
    <div class="login-container">
        <div class="login-card">
            <!-- Logo and Brand -->
            <div class="logo-container">
                <div class="logo">
                    <i class="bi bi-fire"></i>
                </div>
                <div class="brand-name">Rumah Gas dan Galon</div>
                <h3 class="fw-bold mt-2 mb-2" style="color: #333;">Login Pengelola</h3>
                <p class="text-muted mb-0">Akses dashboard pengelolaan toko</p>
            </div>
            
            <!-- Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('status'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf
                
                <!-- Email Input -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-medium">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="email@rumahgasgalon.com" required value="{{ old('email') }}"
                               autocomplete="email" autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Password Input -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-medium">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Masukkan password" required autocomplete="current-password">
                        <span class="input-group-text password-toggle" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="btn btn-login" id="loginButton">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login ke Dashboard
                </button>
                
                <!-- Footer Links -->
                <div class="login-footer">
                    <div class="text-center mb-2">
                        <p class="mb-2">Belum punya akun pengelola? 
                            <a href="{{ route('register') }}" class="register-link fw-medium">Daftar disini</a>
                        </p>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('welcome') }}" class="back-link">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke halaman utama
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Copyright -->
        <div class="text-center text-white mt-4">
            <p class="mb-0 small opacity-75">
                &copy; {{ date('Y') }} Rumah Gas dan Galon. Hak cipta dilindungi.
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
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

        // Form validation and submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            const form = e.target;
            
            // Validate email
            const emailInput = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else {
                emailInput.classList.remove('is-invalid');
            }
            
            // Validate password
            const passwordInput = document.getElementById('password');
            if (passwordInput.value.length === 0) {
                passwordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                passwordInput.classList.remove('is-invalid');
            }
            
            if (isValid) {
                // Show loading state
                const loginButton = document.getElementById('loginButton');
                const originalText = loginButton.innerHTML;
                loginButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
                loginButton.disabled = true;
                
                // Submit form
                form.submit();
            }
        });

        // Remove validation on input
        ['email', 'password'].forEach(field => {
            document.getElementById(field).addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });

        // Auto-focus email field
        document.getElementById('email').focus();
        
        // Enter key submits form
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.target.matches('textarea')) {
                const form = document.getElementById('loginForm');
                if (form.checkValidity()) {
                    form.requestSubmit();
                }
            }
        });
    </script>
</body>
</html>