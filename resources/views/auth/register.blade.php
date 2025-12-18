<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengelola - Rumah Gas dan Galon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #dc3545;
            --secondary-color: #198754;
            --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        
        body {
            background: var(--gradient-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }
        
        .register-container {
            width: 100%;
            max-width: 480px;
            padding: 20px;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.25),
                0 10px 20px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo {
            background: var(--gradient-primary);
            width: 90px;
            height: 90px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 38px;
            font-weight: bold;
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .logo::before {
            content: '';
            position: absolute;
            width: 50px;
            height: 50px;
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
            font-size: 13px;
            color: var(--primary-color);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .register-title {
            color: #333;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .form-control {
            border-radius: 12px;
            padding: 14px 18px;
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
        
        .btn-register {
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
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
            color: white;
        }
        
        .btn-register:active {
            transform: translateY(0);
        }
        
        .form-text {
            font-size: 13px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .login-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .login-link:hover {
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
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
        
        .terms-link {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .terms-link:hover {
            text-decoration: underline;
        }
        
        .strength-meter {
            height: 6px;
            border-radius: 3px;
            margin-top: 8px;
            transition: all 0.3s;
        }
        
        .strength-0 { background-color: #dc3545; width: 20%; }
        .strength-1 { background-color: #fd7e14; width: 40%; }
        .strength-2 { background-color: #ffc107; width: 60%; }
        .strength-3 { background-color: #20c997; width: 80%; }
        .strength-4 { background-color: #198754; width: 100%; }
        
        @media (max-width: 576px) {
            .register-card {
                padding: 30px 20px;
            }
            
            .logo {
                width: 75px;
                height: 75px;
                font-size: 32px;
            }
        }
        
        .register-footer {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .bubble {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: floatBubble 15s infinite linear;
            z-index: -1;
        }
        
        @keyframes floatBubble {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
            }
        }
    </style>
</head>
<body>
    <!-- Background bubbles -->
    <div class="bubble" style="width: 80px; height: 80px; left: 10%; animation-delay: 0s;"></div>
    <div class="bubble" style="width: 60px; height: 60px; left: 20%; animation-delay: 2s; animation-duration: 18s;"></div>
    <div class="bubble" style="width: 100px; height: 100px; right: 15%; animation-delay: 4s; animation-duration: 20s;"></div>
    <div class="bubble" style="width: 40px; height: 40px; right: 25%; animation-delay: 6s; animation-duration: 12s;"></div>
    
    <div class="register-container">
        <div class="register-card">
            <!-- Logo and Brand -->
            <div class="logo-container">
                <div class="logo">
                    <i class="bi bi-fire"></i>
                </div>
                <div class="brand-name">Rumah Gas dan Galon</div>
                <h3 class="register-title mt-2 mb-2">Registrasi Pengelola</h3>
                <p class="text-muted mb-0">Buat akun untuk mengelola toko</p>
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
            
            <!-- Register Form (SEDERHANA - tanpa usertype) -->
            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf
                
                <!-- Name Input -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-medium">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" class="form-control" id="name" name="name" 
                               placeholder="Nama lengkap pengelola" required value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Minimal 3 karakter</div>
                </div>
                
                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="email@rumahgasgalon.com" required value="{{ old('email') }}">
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
                               placeholder="Minimal 3 karakter" required>
                        <span class="input-group-text password-toggle" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                    <div class="strength-meter" id="passwordStrength"></div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Password minimal 3 karakter</div>
                </div>
                
                <!-- Confirm Password Input -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control" id="password_confirmation" 
                               name="password_confirmation" placeholder="Ulangi password" required>
                        <span class="input-group-text password-toggle" id="toggleConfirmPassword">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                    <div class="form-text">Harus sama dengan password di atas</div>
                </div>
                
                <!-- Terms Checkbox -->
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">
                        Saya menyetujui <a href="#" class="terms-link">Syarat & Ketentuan</a>
                    </label>
                    @error('terms')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Register Button -->
                <button type="submit" class="btn btn-register" id="registerButton">
                    <i class="bi bi-person-plus me-2"></i>Daftar Akun Pengelola
                </button>
                
                <!-- Footer Links -->
                <div class="register-footer">
                    <div class="text-center mb-2">
                        <p class="mb-2">Sudah punya akun pengelola? 
                            <a href="{{ route('login') }}" class="login-link fw-medium">Login disini</a>
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
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        
        function setupPasswordToggle(toggleElement, inputElement) {
            toggleElement.addEventListener('click', function() {
                const icon = this.querySelector('i');
                
                if (inputElement.type === 'password') {
                    inputElement.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    inputElement.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        }
        
        setupPasswordToggle(togglePassword, passwordInput);
        setupPasswordToggle(toggleConfirmPassword, confirmPasswordInput);
        
        // Password strength checker
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Character variety check
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            // Cap at 4
            strength = Math.min(strength, 4);
            
            // Update strength bar
            strengthBar.className = 'strength-meter';
            if (password.length === 0) {
                strengthBar.style.width = '0%';
            } else {
                strengthBar.classList.add(`strength-${strength}`);
            }
        });
        
        // Password confirmation validation
        confirmPasswordInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword && confirmPassword !== '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        // Form validation (TANPA validasi usertype)
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            const form = e.target;
            
            // Validate name
            const nameInput = document.getElementById('name');
            if (nameInput.value.length < 3) {
                nameInput.classList.add('is-invalid');
                isValid = false;
            } else {
                nameInput.classList.remove('is-invalid');
            }
            
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
            const password = document.getElementById('password');
            if (password.value.length < 3) {
                password.classList.add('is-invalid');
                isValid = false;
            } else {
                password.classList.remove('is-invalid');
            }
            
            // Validate confirm password
            const confirmPassword = document.getElementById('password_confirmation');
            if (password.value !== confirmPassword.value) {
                confirmPassword.classList.add('is-invalid');
                isValid = false;
            } else {
                confirmPassword.classList.remove('is-invalid');
            }
            
            // Validate terms
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                terms.classList.add('is-invalid');
                isValid = false;
            } else {
                terms.classList.remove('is-invalid');
            }
            
            if (isValid) {
                // Show loading state
                const registerButton = document.getElementById('registerButton');
                registerButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mendaftarkan...';
                registerButton.disabled = true;
                
                // Submit form
                form.submit();
            } else {
                // Scroll to first error
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
            }
        });
        
        // Remove validation on input (TANPA usertype)
        ['name', 'email', 'password', 'password_confirmation'].forEach(field => {
            document.getElementById(field).addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
        
        document.getElementById('terms').addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
        
        // Auto-focus name field
        document.getElementById('name').focus();
    </script>
</body>
</html>