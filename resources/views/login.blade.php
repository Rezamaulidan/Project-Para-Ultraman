<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
staff_kos
    <title>Halaman Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .input-group {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280; /* Warna abu-abu ikon */
        }
        .input-group .password-toggle-icon {
            display: none;
        }
    </style>
</head>
<body class="bg-blue-500 flex justify-center items-center min-h-screen">

    <div class="w-full max-w-sm p-8 bg-white rounded-2xl shadow-2xl">

        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/illustration_login.png') }}" alt="Ilustrasi Login" class="w-48 h-auto object-contain">
        </div>

        <h2 class="text-2xl font-semibold text-gray-800 mb-8">
            Masuk
        </h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="username" class="text-sm text-gray-700 block mb-1">Username</label>
                <input type="text" id="username" name="username" placeholder="" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-base">
            </div>

            <div class="mb-2">
                <label for="password" class="text-sm text-gray-700 block mb-1">Password</label>
                <input type="password" id="password" name="password" placeholder="" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-base">
            </div>

            <div class="text-right mb-6 text-sm">
                <a href="{{ route('password.request') }}" class="text-blue-500 hover:text-blue-700">Lupa kata sandi?</a>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition duration-200 text-lg">
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 mb-4">
                Belum punya akun? jelajahi sebagai tamu
            </p>

            <a href="#"
               class="w-full inline-block py-3 font-semibold rounded-lg border-2 border-blue-500 text-blue-500 hover:bg-blue-50 transition duration-150 text-lg">
                Masuk Sebagai Tamu
            </a>
            </div>
    </div>

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
master
</body>
</html>
