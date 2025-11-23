<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kamar - SIMK</title>

    {{-- Aset yang Diperlukan --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- <link rel="stylesheet" href="{{ asset('css/style_info_kamar.css') }}"> -->

    <style>
    body {
        background-color: #f4f7f6;
        font-family: 'Inter', sans-serif;
    }

    .page-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .app-view {
        display: none;
    }

    .app-view.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .header-container {
        background-color: #0c3b69;
        color: white;
        padding: 24px 30px;
        border-radius: 12px;
        margin-bottom: 24px;
    }

    .header-container h2 {
        margin: 0;
        font-weight: 700;
    }

    .header-container p {
        margin: 0;
        opacity: 0.9;
        font-size: 1rem;
    }

    .form-card-container {
        background: white;
        padding: 20px 30px 30px 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 3px solid #007bff;
        display: inline-block;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        color: #495057;
        font-size: 0.95rem;
    }

    .form-group label i {
        width: 20px;
        margin-right: 8px;
        color: #007bff;
    }

    .form-control,
    .form-select {
        border-radius: 6px;
        padding: 0.75rem 1rem;
        border: 1px solid #ced4da;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
    }

    .file-upload-label {
        background-color: #0c3b69;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        display: inline-block;
        cursor: pointer;
        width: 13%;
        transition: all 0.3s;

    }

    .file-upload-label:hover {
        background-color: #002d5a;
        transform: translateY(-1px);
    }

    #file-name-display {
        color: #ffffffff;
        font-style: italic;
        font-size: 0.9em;
        margin-top: 10px;
    }

    .btn-submit-main {
        background: linear-gradient(45deg, #0c3b69, #007bff);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 10px;
        font-size: 1.1em;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 20px rgba(0, 25, 49, 0.3);
        width: 50%;
        text-align: center;
        align-items: center;
        transition: all 0.3s;
    }

    .btn-submit-main i {
        margin-right: 10px;
    }

    .btn-submit-main:hover {
        background: #082a4d;
        color: white;
    }

    .btn-submit-main:active {
        transform: scale(0.98);
    }
    </style>
</head>

<body>

    <div class="page-container">
        {{-- Header --}}
        <div class="header-container">
            <h2>Edit Data Kamar Kos 🏠</h2>
            <p>Kelola dan perbarui detail properti untuk kamar No. {{ $kamar->no_kamar }} secara efisien.</p>
        </div>

        {{-- Form Edit Kamar --}}
        <div id="edit-data-kamar-form-view" class="app-view active">
            <div class="form-card-container">
                <form id="form-edit-kamar" enctype="multipart/form-data">
                    @csrf
                    {{-- FIX: Hapus @method('PUT') dan input hidden no_kamar yang tidak perlu --}}

                    <h3 class="section-title">Informasi Dasar</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-nomor-kamar"><i class="fas fa-hashtag"></i> Nomor Kamar:</label>
                                <input type="text" id="edit-nomor-kamar" class="form-control"
                                    value="{{ $kamar->no_kamar }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-lantai"><i class="fas fa-layer-group"></i> Lantai:</label>
                                <select id="edit-lantai" name="lantai" class="form-select" required>
                                    <option value="1" {{ $kamar->lantai == 1 ? 'selected' : '' }}>Lantai 1</option>
                                    <option value="2" {{ $kamar->lantai == 2 ? 'selected' : '' }}>Lantai 2</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-status-kamar"><i class="fas fa-check-circle"></i> Status Kamar:</label>
                                <select id="edit-status-kamar" name="status" class="form-select" required>
                                    <option value="tersedia" {{ $kamar->status == 'tersedia' ? 'selected' : '' }}>
                                        Tersedia</option>
                                    <option value="terisi" {{ $kamar->status == 'terisi' ? 'selected' : '' }}>Terisi
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-harga-per-bulan"><i class="fas fa-dollar-sign"></i> Harga per Bulan
                                    (Rp):</label>
                                <input type="number" id="edit-harga-per-bulan" name="harga" class="form-control"
                                    value="{{ $kamar->harga }}" required>
                            </div>
                        </div>
                    </div>

                    <h3 class="section-title mt-3">Detail Properti</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-tipe-kamar"><i class="fas fa-bed"></i> Tipe Kamar:</label>
                                <select id="edit-tipe-kamar" name="tipe_kamar" class="form-select" required>
                                    <option value="kosongan" {{ $kamar->tipe_kamar == 'kosongan' ? 'selected' : '' }}>
                                        Kosongan</option>
                                    <option value="basic" {{ $kamar->tipe_kamar == 'basic' ? 'selected' : '' }}>Basic
                                    </option>
                                    <option value="ekslusif" {{ $kamar->tipe_kamar == 'ekslusif' ? 'selected' : '' }}>
                                        Ekslusif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-ukuran-kamar"><i class="fas fa-ruler-combined"></i> Ukuran Kamar
                                    (m²):</label>
                                <input type="text" id="edit-ukuran-kamar" name="ukuran" class="form-control"
                                    placeholder="Contoh: 3x4" value="{{ $kamar->ukuran }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit-fasilitas"><i class="fas fa-shower"></i> Fasilitas Tambahan:</label>
                                <textarea id="edit-fasilitas" name="fasilitas" class="form-control" rows="3"
                                    placeholder="Contoh: AC, WiFi, KM Dalam">{{ $kamar->fasilitas }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><i class="fas fa-image"></i> Upload Foto Kamar (Opsional):</label>
                                <div class="file-upload-container">
                                    <input type="file" id="foto-kamar" name="foto_kamar" accept="image/*"
                                        style="display: none;">
                                    <label for="foto-kamar" class="file-upload-label" style="color: white;">Pilih
                                        File</label>
                                    <span id="file-name-display">Tidak ada file yang dipilih</span>
                                </div>
                                @if ($kamar->foto_kamar)
                                <small class="form-text text-muted mt-2">Foto saat ini:
                                    {{ basename($kamar->foto_kamar) }}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" id="btn-simpan-edit" class="btn-submit-main">
                            <i class="fas fa-save"></i> Simpan Data Kamar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // FIX: Membersihkan sintaks Blade agar tidak error di JS
    const kamar = @json($kamar);
    const updateRoute = '{{ url("/updatekamar/" . $kamar->no_kamar) }}';

    function showView(viewId) {
        document.querySelectorAll('.app-view').forEach(v => v.classList.remove('active'));
        document.getElementById(viewId).classList.add('active');
    }

    document.getElementById('foto-kamar').addEventListener('change', function() {
        const fileName = this.files[0]?.name || 'Tidak ada file yang dipilih';
        document.getElementById('file-name-display').textContent = fileName;
    });

    // --- FIX UTAMA DISINI ---
    document.getElementById('form-edit-kamar').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // 1. Tambahkan _method: PUT secara manual ke FormData
        // Ini wajib untuk upload file menggunakan metode update di Laravel
        formData.append('_method', 'PUT');

        try {
            const response = await fetch(updateRoute, {
                method: 'POST', // 2. Tetap gunakan POST di fetch, bukan PUT
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert('Data kamar berhasil diperbarui!');
                // Arahkan kembali ke halaman index/dashboard
                window.location.href = '{{ route("pemilik.datakamar") }}';
            } else {
                // Tampilkan pesan error jika validasi gagal
                console.log(result);
                let pesan = result.message || 'Gagal menyimpan data.';
                if (result.errors) {
                    pesan += '\n\nDetail Error:\n' + JSON.stringify(result.errors, null, 2).replace(
                        /[{}"]/g, '');
                }
                alert(pesan);
            }
        } catch (err) {
            console.error(err);
            alert('Terjadi kesalahan koneksi atau server: ' + err.message);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        showView('edit-data-kamar-form-view');
    });
    </script>
</body>

</html>