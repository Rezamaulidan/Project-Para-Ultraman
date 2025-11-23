<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Informasi Penyewa - SIMK</title>

    {{-- Link CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Link Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Background utama */
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Kartu Form */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }

        .card-body {
            padding: 2rem;
        }

        /* Styling Label Form */
        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        /* Styling Input & Select */
        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Styling Tombol */
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #004494);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-secondary {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            border-width: 2px;
        }

        /* === STYLING UPLOAD FOTO BARU (MIRIP PROFIL PEMILIK) === */
        .photo-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .photo-preview-wrapper {
            width: 120px;
            height: 120px;
            position: relative;
            margin-bottom: 10px;
        }

        .photo-preview-circle {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid #e9ecef;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }

        .photo-preview-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-initials {
            font-size: 3rem;
            font-weight: bold;
            color: #6c757d;
            text-transform: uppercase;
        }

        /* Ikon Kamera (Label untuk Input File) */
        .camera-icon-label {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #007bff;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            border: 3px solid white;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .camera-icon-label:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        /* Tombol Hapus Foto (Kecil di bawah) */
        .btn-delete-photo-small {
            font-size: 0.8rem;
            color: #dc3545;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            text-decoration: underline;
        }
        .btn-delete-photo-small:hover {
            color: #bd2130;
        }
    </style>
</head>

<body>

    {{-- 1. Navbar --}}
    @include('partials.navbar_menu_penyewa')

    {{-- 2. Konten Utama --}}
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-6">

                {{-- Kartu Form --}}
                <div class="card shadow-lg">
                    {{-- Header Kartu --}}
                    <div class="card-header text-center">
                        <h4 class="mb-0 fw-bold"><i class="fa-solid fa-user-pen me-2"></i>Edit Informasi Penyewa</h4>
                    </div>

                    {{-- Body Kartu --}}
                    <div class="card-body p-4 p-md-5">

                        {{-- Alert Sukses / Error --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Form Edit --}}
                        <form action="{{ route('penyewa.update_informasi') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT') {{-- Method PUT untuk update data --}}

                            {{-- [BARU] Upload Foto Profil dengan Preview --}}
                            <div class="photo-upload-container">
                                <label class="form-label d-block text-center">Foto Profil</label>

                                <div class="photo-preview-wrapper">
                                    {{-- Lingkaran Preview --}}
                                    <div class="photo-preview-circle">
                                        @if ($penyewa->foto_profil && file_exists(public_path('storage/' . $penyewa->foto_profil)))
                                            <img id="photoPreview" src="{{ asset('storage/' . $penyewa->foto_profil) }}" alt="Preview Foto">
                                        @else
                                            <img id="photoPreview" src="" alt="Preview Foto" style="display: none;">
                                            <span id="photoInitials" class="photo-initials">
                                                {{ strtoupper(substr($penyewa->nama_penyewa ?? 'U', 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Tombol Kamera (Label untuk Input File) --}}
                                    <label for="foto_profil" class="camera-icon-label" title="Ganti Foto">
                                        <i class="fa-solid fa-camera"></i>
                                    </label>
                                </div>

                                {{-- Input File (Sembunyi) --}}
                                <input type="file" class="form-control d-none" id="foto_profil" name="foto_profil"
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       onchange="previewImage(this)">

                                {{-- Tombol Hapus (Opsional - bisa ditambahkan nanti jika perlu fitur hapus foto) --}}
                                {{-- <button type="button" class="btn-delete-photo-small">Hapus Foto</button> --}}

                                <div class="form-text text-center mt-1">Format: JPG, PNG, GIF. Maks: 2MB.</div>
                            </div>


                            {{-- 1. Nama Lengkap --}}
                            <div class="mb-4">
                                <label for="nama_penyewa" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" class="form-control @error('nama_penyewa') is-invalid @enderror"
                                        id="nama_penyewa" name="nama_penyewa"
                                        value="{{ old('nama_penyewa', $penyewa->nama_penyewa) }}" required
                                        placeholder="Masukkan nama lengkap">
                                </div>
                                @error('nama_penyewa')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- 2. No Telepon (WhatsApp) --}}
                            <div class="mb-4">
                                <label for="no_hp" class="form-label">No Telepon (WhatsApp) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-brands fa-whatsapp"></i></span>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                        id="no_hp" name="no_hp" value="{{ old('no_hp', $penyewa->no_hp) }}"
                                        required placeholder="Contoh: 08123456789">
                                </div>
                                @error('no_hp')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- 3. Jenis Kelamin (Select Option) --}}
                            <div class="mb-4">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i
                                            class="fa-solid fa-venus-mars"></i></span>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="" disabled
                                            {{ old('jenis_kelamin', $penyewa->jenis_kelamin) ? '' : 'selected' }}>Pilih
                                            Jenis Kelamin</option>
                                        <option value="Laki-laki"
                                            {{ old('jenis_kelamin', $penyewa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="Perempuan"
                                            {{ old('jenis_kelamin', $penyewa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                </div>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                                <a href="{{ route('penyewa.informasi') }}" class="btn btn-outline-secondary px-4 me-md-2">
                                    <i class="fa-solid fa-arrow-left me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan
                                </button>
                            </div>

                        </form>
                    </div> {{-- End Card Body --}}
                </div> {{-- End Card --}}

            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script untuk Toggle Password & Preview Image --}}
    <script>
        // 1. Toggle Password Visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        if (togglePassword && password && eyeIcon) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });
        }

        // 2. Preview Image Sebelum Upload
        function previewImage(input) {
            const photoPreview = document.getElementById('photoPreview');
            const photoInitials = document.getElementById('photoInitials');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block'; // Tampilkan gambar
                    if (photoInitials) photoInitials.style.display = 'none'; // Sembunyikan inisial
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>

</html>
