<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Manajemen Kos</title>

    {{-- Link untuk memuat CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome untuk icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Custom style untuk background gradient */
        body,
        html {
            height: 100%;
        }

        .gradient-background {
            background: linear-gradient(135deg, #007bff, #0056b3);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-card {
            max-width: 450px;
            width: 100%;
        }

        /* Improved focus state untuk input */
        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        /* Hover state untuk button */
        .btn-primary {
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Link dengan kontras lebih baik */
        .link-primary-contrast {
            color: #0056b3;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .link-primary-contrast:hover {
            color: #003d82;
            text-decoration: underline;
        }

        /* Password toggle button */
        .password-toggle {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0 1rem;
            z-index: 10;
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
        }

        .password-toggle:hover {
            color: #007bff;
        }

        .password-toggle:focus {
            outline: none;
            color: #007bff;
        }

        .form-floating-password {
            position: relative;
        }

        .form-floating-password .form-control {
            padding-right: 3.5rem;
        }

        /* Hide default password reveal button in Edge/IE */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        /* Loading spinner */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading .btn-text {
            visibility: hidden;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 1rem;
            height: 1rem;
            top: 50%;
            left: 50%;
            margin-left: -0.5rem;
            margin-top: -0.5rem;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.6s linear infinite;
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        /* Alert animation */
        .alert {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card animation */
        .login-card {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="gradient-background">
        <div class="card shadow-lg border-0 rounded-4 login-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center">

                    <img src="{{ asset('img/login.svg') }}" class="img-fluid"
                        alt="Ilustrasi halaman login dengan gambar kunci dan gembok" role="img"
                        aria-label="Ilustrasi login" style="max-height: 140px;">

                    <h1 class="h3 mb-4 fw-bold">Login</h1>

                    {{-- Blok ini akan menampilkan pesan sukses setelah reset password --}}
                    @if (session('status'))
                        <div class="alert alert-success" role="alert" aria-live="polite">
                            <i class="fa fa-check-circle me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Formulir Login --}}
                    <form action="{{ route('login') }}" method="POST" id="loginForm" aria-label="Form login">
                        @csrf

                        {{-- Username --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" placeholder="Username" required
                                value="{{ old('username') }}" aria-label="Username" aria-describedby="username-error"
                                autofocus>
                            <label for="username">Username</label>

                            @error('username')
                                <div class="invalid-feedback text-start" id="username-error" role="alert">
                                    <i class="fa fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-floating mb-3 form-floating-password">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required aria-label="Password"
                                aria-describedby="password-toggle">
                            <label for="password">Password</label>

                            <button type="button" class="password-toggle" id="password-toggle"
                                onclick="togglePassword()" aria-label="Tampilkan atau sembunyikan password">
                                <i class="fa fa-eye" id="toggleIcon" aria-hidden="true"></i>
                            </button>
                        </div>

                        {{-- Lupa Kata Sandi --}}
                        <div class="text-end mb-4">
                            <a href="{{ route('password.request') }}" class="link-primary-contrast small">
                                Lupa Kata Sandi?
                            </a>
                        </div>

                        {{-- Tombol Login --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill" id="loginButton"
                                aria-label="Masuk ke akun">
                                <span class="btn-text">Login</span>
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4 small">
                        <span class="text-muted">Belum punya akun?</span>
                        <a href="{{ route('register.pilihan') }}" class="link-primary-contrast fw-bold ms-1">
                            Daftar di sini
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Link untuk memuat JavaScript Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            const toggleButton = document.getElementById('password-toggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
                toggleButton.setAttribute('aria-label', 'Sembunyikan password');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
                toggleButton.setAttribute('aria-label', 'Tampilkan password');
            }
        }

        // Loading state saat submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = document.getElementById('loginButton');
            button.classList.add('btn-loading');
            button.disabled = true;
        });

        // Smooth focus animation
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
                this.parentElement.style.transition = 'transform 0.2s ease';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>

</body>

</html>
