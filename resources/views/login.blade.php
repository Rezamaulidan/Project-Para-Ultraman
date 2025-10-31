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

        .login-card {
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
        <div class="card shadow-lg border-0 rounded-4 login-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center">

                    <img src="{{ asset('img/login.svg') }}" class="img-fluid" alt="Login Illustration" style="max-height: 140px;">

                    <h3 class="mb-4 fw-bold">Login</h3>

                    {{-- Formulir Login --}}
                    <form id="loginForm" method="POST">
                        @csrf

                        {{-- Username --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            <label for="username">Username</label>
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

    {{-- JAVASCRIPT UNTUK IDENTIFIKASI USER --}}
    <script>
        const loginForm = document.getElementById('loginForm');

        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const usernameInput = document.getElementById('username');
            const username = usernameInput.value.toLowerCase();
            const usernameError = document.getElementById('usernameError');

            // Lakukan pengecekan username
            if (username === 'louis1234') {
                // Arahkan ke dashboard penyewa
                window.location.href = '/dashboard-booking';

            } else if (username === 'bril1234') {
                // Arahkan ke dashboard pemilik
                window.location.href = '/homepemilik';

            } else {
                // Tampilkan pesan error
                usernameInput.classList.add('is-invalid');
                usernameError.classList.remove('d-none');
                usernameError.textContent = 'Username yang Anda masukkan salah.';
            }
        });
    </script>

</body>
</html>
