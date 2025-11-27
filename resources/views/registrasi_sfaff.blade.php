<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Staff - SIMK</title>

    {{-- 🛑 1. TAMBAHKAN LINK BOOTSTRAP CSS (Bootstrap 5) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    {{-- 🛑 2. TAMBAHKAN LINK FONT AWESOME CSS (Untuk ikon) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- 🛑 3. TAMBAHKAN LINK SWEETALERT2 JS (Di sini agar dimuat awal) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    /* Card Styling */
    .register-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        animation: fadeInUp 0.5s ease-out;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #001931, #033f7e);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    /* Form Control Styling (Floating Labels) */
    .form-control:focus,
    .form-select:focus {
        border-color: #001931;
        box-shadow: 0 0 0 0.25rem rgba(0, 25, 49, 0.15);
    }

    .form-floating {
        transition: transform 0.2s ease;
    }

    /* Button Styling */
    .btn-primary-custom {
        background-color: #001931;
        border-color: #001931;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        background-color: #033f7e;
        border-color: #033f7e;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 25, 49, 0.3);
    }

    /* Password Toggle */
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
        display: flex;
        align-items: center;
    }

    .password-toggle:hover {
        color: #001931;
    }

    .form-floating-password .form-control {
        padding-right: 3.5rem;
    }

    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none;
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Styling untuk menempatkan form di tengah halaman */
    body {
        background-color: #f8f9fa;
        /* Warna latar belakang */
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }
    </style>
</head>

<body>

    {{-- ISI CONTENT FORM --}}
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                <div class="card register-card">
                    {{-- Header Card --}}
                    <div class="card-header-custom">
                        <h3 class="mb-1 fw-bold"><i class="fas fa-user-shield me-2"></i>Registrasi Staff Baru</h3>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        {{-- Pesan Sukses/Error Global (Alert Bootstrap lama Dihapus) --}}
                        @if (session('success'))
                        {{-- 🛑 Alert ini akan tetap ada untuk error/success lain, tapi bukan untuk 'staff_saved' --}}
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        {{-- Form --}}
                        {{-- Pastikan route-nya sesuai, misal: pemilik.store_staff --}}
                        <form action="{{ route('pemilik.store_staff') }}" method="POST" enctype="multipart/form-data"
                            id="registerForm">
                            @csrf

                            <div class="row g-3">
                                {{-- Nama Staff --}}
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('nama_staf') is-invalid @enderror"
                                            id="nama_staf" name="nama_staf" placeholder="Nama Lengkap"
                                            value="{{ old('nama_staf') }}" required>
                                        <label for="nama_staf">Nama Lengkap Staff</label>
                                        @error('nama_staf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- No HP --}}
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                            id="no_hp" name="no_hp" placeholder="08xxx" value="{{ old('no_hp') }}"
                                            required pattern="[0-9]+">
                                        <label for="no_hp">Nomor HP (WhatsApp)</label>
                                        @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" placeholder="email@contoh.com"
                                            value="{{ old('email') }}" required>
                                        <label for="email">Alamat Email</label>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Jadwal Kerja --}}
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('jadwal') is-invalid @enderror" id="jadwal"
                                            name="jadwal" required>
                                            <option value="" selected disabled>Pilih Jadwal</option>
                                            <option value="Pagi" {{ old('jadwal') == 'Pagi' ? 'selected' : '' }}>Shift
                                                Pagi</option>
                                            <option value="Malam" {{ old('jadwal') == 'Malam' ? 'selected' : '' }}>Shift
                                                Malam</option>
                                        </select>
                                        <label for="jadwal">Jadwal Kerja</label>
                                        @error('jadwal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Foto Profil (Optional) --}}
                                <div class="col-md-6">
                                    <div class="form-group h-100 d-flex flex-column justify-content-center">
                                        <label for="foto_staf" class="form-label small text-muted mb-1">Foto Profil
                                        </label>
                                        <input class="form-control @error('foto_staf') is-invalid @enderror" type="file"
                                            id="foto_staf" name="foto_staf" accept="image/*">
                                        @error('foto_staf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 my-2">
                                    <hr class="text-muted">
                                    <p class="text-muted small mb-0"><i class="fas fa-lock me-1"></i> Pengaturan Akun
                                        Login
                                    </p>
                                </div>

                                {{-- Username --}}
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" placeholder="Username"
                                            value="{{ old('username') }}" required minlength="4">
                                        <label for="username">Username</label>
                                        @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Password --}}
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-password">
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password" placeholder="Password" required minlength="6">
                                        <label for="password">Password</label>

                                        <button type="button" class="password-toggle" onclick="togglePassword()">
                                            <i class="fa fa-eye" id="toggleIcon"></i>
                                        </button>

                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Tombol Submit --}}
                                <div class="col-12 mt-4">
                                    <button type="submit"
                                        class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold">
                                        <i class="fas fa-user-plus me-2"></i> Daftarkan Staff
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- 🛑 4. TAMBAHKAN LINK BOOTSTRAP JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    <script>
    // Toggle password visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Efek visual saat input difokuskan (Optional)
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // 🛑 SCRIPT SWEETALERT2 UNTUK POP-UP SUKSES & REDIRECT
    @if(session('staff_saved'))
    Swal.fire({
        title: "🎉 Berhasil Disimpan!",
        html: `Data staff **{{ session('staff_saved') }}**<br>Sekarang Anda akan dialihkan ke halaman Data Staff.`,
        icon: "success",
        iconColor: "#FFFFFF", // Icon putih
        showConfirmButton: false,
        timer: 3500, // Durasi pop-up 3.5 detik
        timerProgressBar: true,
        background: "#001931", // Navy Primary
        color: "#FFFFFF", // Teks putih
        customClass: {
            popup: 'rounded-4 shadow-lg border border-3 border-light',
            title: 'fw-bold',
        },
        // Sebelum pop-up ditutup
        willClose: () => {
            // 🛑 Lakukan redirect ke halaman data staff
            // Ganti 'pemilik.data_staff_pemilik' sesuai nama route yang benar di web.php
            window.location.href = "{{ route('pemilik.datastaff') }}";
        }
    });
    @endif
    </script>
</body>

</html>