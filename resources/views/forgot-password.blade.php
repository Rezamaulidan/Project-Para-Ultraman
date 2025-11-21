<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Sistem Informasi Manajemen Kos</title>

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
            background: linear-gradient(135deg, #001931, #033f7e);
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

                    <img src="{{ asset('img/login.svg') }}" class="img-fluid" alt="Lupa Password Illustration" style="max-height: 140px;">

                    <h3 class="mb-3 fw-bold">Lupa Kata Sandi</h3>
                    <p class="text-muted mb-4 small">
                        Masukkan alamat email Anda yang terdaftar. Kami akan mengirimkan link untuk mereset password Anda.
                    </p>

                    {{-- ====================================================== --}}
                    {{-- BLOK UNTUK MENAMPILKAN PESAN SUKSES --}}
                    {{-- ====================================================== --}}
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{-- ====================================================== --}}


                    {{-- Formulir Lupa Password --}}
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf

                        {{-- Email --}}
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="nama@contoh.com" required value="{{ old('email') }}" autofocus>
                            <label for="email">Alamat Email</label>
                            @error('email')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Tombol Kirim --}}
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">Kirim Link Reset</button>
                        </div>
                    </form>

                    {{-- Link Kembali ke Login --}}
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none small">Kembali ke halaman Login</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Link untuk memuat JavaScript Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
