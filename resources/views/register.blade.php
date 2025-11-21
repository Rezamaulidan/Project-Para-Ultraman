<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sistem Informasi Manajemen Kos</title>

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
            background: linear-gradient(135deg, #001931, #033f7e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .register-card {
            max-width: 450px;
            width: 100%;
        }

        /* Improved focus state untuk input */
        .form-control:focus,
        .form-select:focus {
            border-color: #001931;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        /* Hover state untuk button */
        .btn-primary {
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px #001931;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Link dengan kontras lebih baik */
        .link-kembali {
            color: #0056b3;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
        }

        .link-kembali:hover {
            color: #003d82;
            text-decoration: underline !important;
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

        /* Card animation */
        .register-card {
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

        /* Input group animation on focus */
        .form-floating {
            transition: transform 0.2s ease;
        }
    </style>
</head>

<body>

    <div class="gradient-background">
        <div class="card shadow-lg border-0 rounded-4 register-card">
            <div class="card-body">
                <div class="text-center">

                    <img src="{{ asset('img/register.svg') }}" class="img-fluid"
                        alt="Ilustrasi halaman pendaftaran dengan gambar formulir dan pena" role="img"
                        aria-label="Ilustrasi registrasi" style="max-height: 100px;">

                    <h1 class="h5 mb-3 fw-bold">Daftar sebagai Penyewa</h1>

                    {{-- Formulir Registrasi --}}
                    <form action="{{ route('register.store') }}" method="POST" id="registerForm"
                        aria-label="Form pendaftaran penyewa">
                        @csrf

                        {{-- Nama Penyewa --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('nama_penyewa') is-invalid @enderror"
                                id="nama_penyewa" name="nama_penyewa" placeholder="Nama Anda"
                                value="{{ old('nama_penyewa') }}" required aria-label="Nama penyewa"
                                aria-describedby="nama-error" autofocus>
                            <label for="nama_penyewa">Nama Penyewa</label>
                            @error('nama_penyewa')
                                <div class="invalid-feedback text-start" id="nama-error" role="alert">
                                    <i class="fa fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="form-floating mb-3">
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin"
                                name="jenis_kelamin" required aria-label="Jenis kelamin"
                                aria-describedby="jenis-kelamin-error">
                                <option selected disabled value="">Pilih jenis kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback text-start" id="jenis-kelamin-error" role="alert">
                                    <i class="fa fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- No HP --}}
                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control @error('no_hp') is-invalid @enderror"
                                id="no_hp" name="no_hp" placeholder="08123456789" value="{{ old('no_hp') }}"
                                required aria-label="Nomor handphone" aria-describedby="no-hp-error"
                                pattern="[0-9]{10,13}">
                            <label for="no_hp">No. HP</label>
                            @error('no_hp')
                                <div class="invalid-feedback text-start" id="no-hp-error" role="alert">
                                    <i class="fa fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" placeholder="Username" value="{{ old('username') }}"
                                required aria-label="Username" aria-describedby="username-error" minlength="3">
                            <label for="username">Username</label>
                            @error('username')
                                <div class="invalid-feedback text-start" id="username-error" role="alert">
                                    <i class="fa fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" placeholder="nama@contoh.com" value="{{ old('email') }}"
                                required aria-label="Alamat email" aria-describedby="email-error">
                            <label for="email">Alamat Email</label>
                            @error('email')
                                <div class="invalid-feedback text-start" id="email-error" role="alert">
                                    <i class="fa fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-floating mb-3 form-floating-password">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Password" required aria-label="Password"
                                aria-describedby="password-toggle password-error" minlength="5">
                            <label for="password">Password</label>

                            <button type="button" class="password-toggle" id="password-toggle"
                                onclick="togglePassword()" aria-label="Tampilkan atau sembunyikan password">
                                <i class="fa fa-eye" id="toggleIcon" aria-hidden="true"></i>
                            </button>

                            @error('password')
                                <div class="invalid-feedback text-start" id="password-error" role="alert">
                                    <i class="fa fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Tombol Daftar --}}
                        <div class="d-grid mt-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill" id="registerButton"
                                aria-label="Daftar sebagai penyewa">
                                <span class="btn-text">Daftar</span>
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-2">
                        <a href="{{ route('home') }}" class="small link-kembali fw-normal">
                            <i class="fa fa-arrow-left me-1"></i>
                            Kembali ke Home
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
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const button = document.getElementById('registerButton');
            button.classList.add('btn-loading');
            button.disabled = true;
        });

        // Smooth focus animation
        document.querySelectorAll('.form-control, .form-select').forEach(input => {
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
