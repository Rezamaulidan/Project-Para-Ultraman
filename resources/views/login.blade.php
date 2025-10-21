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
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        {{-- Username --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="Username" value="{{ old('username') }}" required>
                            <label for="username">Username</label>
                            @error('username')
                                <div class="invalid-feedback text-start">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                            @error('password')
                                <div class="invalid-feedback text-start">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Lupa Kata Sandi --}}
                        <div class="text-end mb-4">
                            <a href="{{ route('password.request') }}" class="text-decoration-none small">Lupa Kata Sandi?</a>
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
</body>
</html>
