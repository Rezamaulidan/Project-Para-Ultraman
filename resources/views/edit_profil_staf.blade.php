<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Staf - SIMK</title>

    {{-- Link CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: linear-gradient(135deg, #001931 0%, #003366 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }

        /* Styling Foto Upload */
        .profile-upload-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
        }

        .profile-preview {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
        }

        .upload-icon {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background-color: #007bff;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            transition: all 0.2s;
        }

        .upload-icon:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        .form-label {
            font-weight: 600;
            color: #001931;
        }

        .btn-navy {
            background-color: #001931;
            color: white;
            border: none;
        }

        .btn-navy:hover {
            background-color: #003366;
            color: white;
        }
    </style>
</head>

<body class="py-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0 fw-bold">Edit Profil Staf</h4>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        {{-- 🛑 FORM UPDATE (PENTING: enctype="multipart/form-data") --}}
                        <form action="{{ route('staff.manajemen.update', ['id' => $staf->id_staf]) }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf
                            @method('PUT')

                            {{-- BAGIAN FOTO PROFIL --}}
                            <div class="profile-upload-container">
                                {{-- Gambar Preview --}}
                                @if ($staf->foto_staf)
                                    <img src="{{ asset('storage/' . $staf->foto_staf) }}" id="preview-img"
                                        class="profile-preview">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($staf->nama_staf) }}&background=001931&color=fff&size=256&bold=true"
                                        id="preview-img" class="profile-preview">
                                @endif

                                {{-- Input File Tersembunyi --}}
                                <input type="file" name="foto_staf" id="foto_input" class="d-none" accept="image/*"
                                    onchange="previewImage(this)">

                                {{-- Tombol Kamera --}}
                                <label for="foto_input" class="upload-icon" title="Ganti Foto">
                                    <i class="fas fa-camera"></i>
                                </label>
                            </div>
                            <div class="text-center mb-4">
                                <small class="text-muted">Klik ikon kamera untuk mengubah foto.</small>
                            </div>

                            {{-- INPUT DATA --}}
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                    <input type="text" name="nama_lengkap"
                                        class="form-control @error('nama_lengkap') is-invalid @enderror"
                                        value="{{ old('nama_lengkap', $staf->nama_staf) }}" required>
                                </div>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fab fa-whatsapp"></i></span>
                                    <input type="text" name="no_hp"
                                        class="form-control @error('no_hp') is-invalid @enderror"
                                        value="{{ old('no_hp', $staf->no_hp) }}" required>
                                </div>
                                @error('no_hp')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $staf->email) }}" required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- INFO READONLY --}}
                            <div class="mb-4 p-3 bg-light rounded border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted fw-bold text-uppercase">Username Login</small>
                                    <span class="fw-bold text-dark">{{ Auth::user()->username }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted fw-bold text-uppercase">Jadwal / Shift</small>
                                    <span class="badge bg-primary">{{ ucfirst($staf->jadwal) }}</span>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-navy py-2 fw-bold rounded-3 shadow-sm">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('staff.lihat_profil', ['id' => $staf->id_staf]) }}"
                                    class="btn btn-outline-secondary py-2 rounded-3">
                                    Batal
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script Preview Gambar --}}
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>

</html>
