<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Kamar - SIMK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/input_kamar.css') }}">
</head>

<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Input Data Kamar Kos 🏡</h1>
            <p>Kelola dan perbarui detail properti kamar secara efisien.</p>
        </div>

        <form id="form-input-kamar-v2" action="{{ route('pemilik.inputkamar.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-section">
                <h2 class="section-title">Informasi Dasar</h2>
                <div class="form-grid">

                    <div class="form-group">
                        <input type="text" id="no_kamar" name="no_kamar" placeholder=" " required>
                        <label for="no_kamar"><i class="fas fa-hashtag"></i> Nomor Kamar</label>
                    </div>

                    <div class="form-group">
                        <div class="select-wrapper">
                            <select id="lantai" name="lantai" required>
                                <option value="" disabled selected></option>
                                <option value="1">Lantai 1</option>
                                <option value="2">Lantai 2</option>
                                <option value="3">Lantai 3</option>
                            </select>
                            <label for="lantai"><i class="fas fa-layer-group"></i> Lantai</label>
                            <span class="select-icon"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="select-wrapper">
                            <select id="status" name="status" required>
                                <option value="" disabled selected></option>
                                <option value="tersedia">Kosong</option>
                                <option value="terisi">Terisi</option>
                            </select>
                            <label for="status"><i class="fas fa-calendar-check"></i> Status Kamar</label>
                            <span class="select-icon"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="number" id="harga" name="harga" placeholder=" " min="0" required>
                        <label for="harga"><i class="fas fa-wallet"></i> Harga Sewa per Bulan (Rp)</label>
                    </div>

                </div>
            </div>

            <div class="form-section">
                <h2 class="section-title">Detail Properti</h2>
                <div class="form-grid">

                    <div class="form-group">
                        <div class="select-wrapper">
                            <select id="tipe_kamar" name="tipe_kamar" required>
                                <option value="" disabled selected></option>
                                <option value="kosongan">Kosongan (Tanpa Perabot)</option>
                                <option value="basic">Basic (Standar)</option>
                                <option value="ekslusif">Deluxe (Premium)</option>
                            </select>
                            <label for="tipe_kamar"><i class="fas fa-bed"></i> Tipe Kamar</label>
                            <span class="select-icon"><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" id="ukuran" name="ukuran" placeholder=" " required>
                        <label for="ukuran"><i class="fas fa-ruler-combined"></i> Ukuran Kamar (m²)</label>
                    </div>

                    <div class="form-group" id="fasilitas-group">
                        <input type="text" id="fasilitas" name="fasilitas" placeholder=" " required>
                        <label for="fasilitas"><i class="fas fa-list-check"></i> Fasilitas Tambahan</label>
                    </div>

                    <div class="file-upload-wrapper">
                        <label for="foto_kamar" style="font-weight: 600; color: var(--navy-dark);"><i
                                class="fas fa-camera"></i> Upload Foto Kamar</label>
                        <input type="file" id="foto_kamar" name="foto_kamar" style="display: none;" accept="image/*">
                        <label for="foto_kamar" class="file-upload-label-button">
                            Pilih File Foto
                        </label>
                        <span id="file-name-display-v2">Tidak ada file yang dipilih</span>
                    </div>
                </div>
            </div>

            <div class="submit-group">
                <button type="submit" class="btn-simpan-v2" id="submit-button">
                    <i class="fas fa-save"></i> Simpan Data Kamar Sekarang
                </button>
            </div>
        </form>
    </div>

    <script>
        const form = document.getElementById('form-input-kamar-v2');
        const modal = document.getElementById('success-modal');
        const modalOkButton = document.getElementById('modal-ok-button');
        const fileInput = document.getElementById('foto_kamar');
        const redirectURL = '/datakamarpemilik';

        // A. Script untuk menampilkan nama file yang dipilih
        fileInput.addEventListener('change', function() {
            const fileNameDisplay = document.getElementById('file-name-display-v2');
            if (this.files && this.files.length > 0) {
                fileNameDisplay.textContent = 'File terpilih: ' + this.files[0].name;
            } else {
                fileNameDisplay.textContent = 'Tidak ada file yang dipilih';
            }
        });

        // B. Script untuk Floating Label di Select
        document.querySelectorAll('.form-group select').forEach(select => {
            // Cek status saat load
            if (select.value) {
                select.classList.add('valid');
            } else {
                select.classList.remove('valid');
            }

            // Cek status saat berubah
            select.addEventListener('change', function() {
                if (this.value) {
                    this.classList.add('valid');
                } else {
                    this.classList.remove('valid');
                }
            });
        });
    </script>
</body>

</html>
