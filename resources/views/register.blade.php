<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sistem Informasi Manajemen Kos</title>

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

                    <img src="{{ asset('img/register.svg') }}" class="img-fluid " alt="Register Illustration" style="max-height: 100px;">

                    <h5 class="mb-3 fw-bold">Daftar sebagai Penyewa</h3>

                    {{-- Formulir Registrasi --}}
                    <form action="{{ route('register.store') }}" method="POST">
                        @csrf

                        {{-- Nama Penyewa --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nama Anda" value="{{ old('name') }}" required>
                            <label for="name">Nama Penyewa</label>
                            @error('name')
                                <div class="invalid-feedback text-start">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="form-floating mb-3">
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option selected disabled value="">Pilih jenis kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback text-start">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- No HP --}}
                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" placeholder="08123456789" value="{{ old('no_hp') }}" required>
                            <label for="no_hp">No. HP</label>
                             @error('no_hp')
                                <div class="invalid-feedback text-start">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="Username" value="{{ old('username') }}" required>
                            <label for="username">Username</label>
                             @error('username')
                                <div class="invalid-feedback text-start">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="nama@contoh.com" value="{{ old('email') }}" required>
                            <label for="email">Alamat Email</label>
                            @error('email')
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

                        {{-- Tombol Daftar --}}
                        <div class="d-grid mt-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">Daftar</button>
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
