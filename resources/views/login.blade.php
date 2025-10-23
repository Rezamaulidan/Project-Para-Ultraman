<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Manajemen Kos</title>

    {{-- Link untuk memuat CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Custom style untuk background gradient */
        body,
        html {
            height: 100%;
            overflow: hidden;
        }

        .gradient-background {
            background: linear-gradient(135deg, #007bff, #0056b3);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .register-card {
            max-width: 450px;
            width: 100%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>

    <div class="gradient-background">
        <div class="card shadow-lg border-0 rounded-4 register-card">
            <div class="card-body">
                <div class="text-center">

                    <img src="{{ asset('img/login.svg') }}" class="img-fluid" alt="Login Illustration" style="max-height: 140px;">

                    <h3 class="mb-4 fw-bold">Login</h3>

                    {{-- Formulir Login --}}
                    {{-- Kita ganti action-nya dan tambahkan id pada form --}}
                    <form id="loginForm" method="POST">
                        @csrf

                        {{-- Username --}}
                        <div class="form-floating mb-3">
                            {{-- Tambahkan id pada input username --}}
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            <label for="username">Username</label>
                            {{-- Kita tambahkan elemen untuk pesan error --}}
                            <div id="usernameError" class="invalid-feedback text-start d-none">Username tidak valid.</div>
                        </div>

                        {{-- Password --}}
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                        </div>

                        {{-- Lupa Kata Sandi --}}
                        <div class="text-end mb-4">
                            <a href="#" class="text-decoration-none small">Lupa Kata Sandi?</a>
                        </div>

                        {{-- Tombol Login --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Link untuk memuat JavaScript Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- ====================================================== --}}
    {{-- BAGIAN BARU: JAVASCRIPT UNTUK IDENTIFIKASI USER --}}
    {{-- ====================================================== --}}
    <script>
        // 1. Ambil elemen form dari HTML
        const loginForm = document.getElementById('loginForm');

        // 2. Tambahkan 'event listener' yang akan berjalan saat form disubmit
        loginForm.addEventListener('submit', function(event) {

            // Mencegah form dikirim secara default (agar tidak reload)
            event.preventDefault();

            // 3. Ambil nilai dari input username
            const usernameInput = document.getElementById('username');
            const username = usernameInput.value.toLowerCase(); // Ubah jadi huruf kecil semua

            const usernameError = document.getElementById('usernameError');

            // 4. Lakukan pengecekan username
            if (username === 'louis1234') {

                // Jika username 'penyewa', arahkan ke halaman home penyewa
                // Ganti URL '/dashboard_booking' jika URL Anda berbeda
                window.location.href = '/dashboard-booking';

            } else if (username === 'bril1234') {

                // Jika username 'pemilik', arahkan ke halaman home pemilik
                // Ganti URL '/home_pemilik' jika URL Anda berbeda
                window.location.href = '/homepemilik';

            } else {

                usernameInput.classList.add('is-invalid');
                usernameError.classList.remove('d-none');
                usernameError.textContent = 'Username yang Anda masukkan salah.';
            }
        });
    </script>

</body>
</html>
