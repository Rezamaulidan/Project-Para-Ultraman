<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kamar Kos - Gabungan</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }

    #main-content {
        width: 100%;
        padding: 20px;
        box-sizing: border-box;
    }

    .app-view {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
        text-align: center;
        max-width: 400px;
    }

    #input-data-kamar-lantai-1-view,
    #input-data-kamar-lantai-2-view,
    #input-data-kamar-form-view {
        max-width: 800px;
        padding: 40px;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 15px 25px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary,
    .list-expander {
        background-color: #f0f0f0;
        color: #333;
        border: 1px solid #ccc;
        padding: 15px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        box-sizing: border-box;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s;
    }

    .list-expander:hover,
    .btn-secondary:hover {
        background-color: #e0e0e0;
    }

    .room-item {
        background-color: #fff;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-align: left;
    }

    .room-item .status-kosong {
        color: green;
        font-weight: bold;
    }

    .room-item .status-terisi {
        color: red;
    }

    .room-item-price {
        text-align: right;
    }

    .room-item-price .price {
        font-weight: bold;
        font-size: 1.1em;
    }

    .room-item-price .period {
        font-size: 0.8em;
        color: #666;
    }

    /* Styling Formulir */
    .form-group {
        text-align: left;
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 16px;
    }

    .file-upload-container {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .file-upload-label {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .file-upload-label:hover {
        background-color: #e0e0e0;
    }
    </style>
</head>

<body>

    <div id="main-content">

        <div id="manajemen-kamar-view" class="app-view">
            <h2 style="color: #333; margin-bottom: 40px;">Manajemen Kamar</h2>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <button id="btn-input-data-kamar" class="btn-secondary">
                    Input Data Kamar
                </button>
                <button class="btn-secondary">
                    Edit Data Kamar
                </button>
                <button class="btn-secondary">
                    Informasi Kamar
                </button>
            </div>
        </div>
        <div id="input-data-kamar-lantai-selection-view" class="app-view" style="display: none;">
            <h2 style="color: #007bff; margin-bottom: 30px;">Input Data Kamar</h2>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div id="listfield-lantai-1" class="list-expander">
                    Lantai 1
                    <span>&#9660;</span>
                </div>
                <div id="listfield-lantai-2" class="list-expander">
                    Lantai 2
                    <span>&#9660;</span>
                </div>
                <button id="btn-tambah-kamar-1" class="btn-primary" style="margin-top: 20px;">
                    Tambah Kamar
                </button>
            </div>
        </div>
        <div id="input-data-kamar-lantai-1-view" class="app-view" style="display: none;">
            <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Input Data Kamar Lantai 1</h2>

            <div class="list-expander" style="background-color: #ddd; font-weight: bold; margin-bottom: 15px;">
                <span>Lantai 1</span>
                <span>&#9650;</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                <div class="room-item">
                    <div>
                        <p style="margin: 0; font-weight: bold;">No. 1</p>
                        <p style="margin: 0; font-size: 0.9em;" class="status-kosong">Status Kamar: Kosong</p>
                    </div>
                    <div class="room-item-price">
                        <p style="margin: 0;" class="price">Rp 1.000.000</p>
                        <p style="margin: 0;" class="period">/Bulan</p>
                    </div>
                </div>
                <div class="room-item">
                    <div>
                        <p style="margin: 0; font-weight: bold;">No. 2</p>
                        <p style="margin: 0; font-size: 0.9em;" class="status-terisi">Status Kamar: Terisi</p>
                    </div>
                    <div class="room-item-price">
                        <p style="margin: 0;" class="price">Rp 1.000.000</p>
                        <p style="margin: 0;" class="period">/Bulan</p>
                    </div>
                </div>
                <div class="room-item">
                    <div>
                        <p style="margin: 0; font-weight: bold;">No. 10</p>
                        <p style="margin: 0; font-size: 0.9em;" class="status-terisi">Status Kamar: Terisi</p>
                    </div>
                    <div class="room-item-price">
                        <p style="margin: 0;" class="price">Rp 1.000.000</p>
                        <p style="margin: 0;" class="period">/Bulan</p>
                    </div>
                </div>
            </div>

            <div id="btn-lantai-2-from-1" class="list-expander" style="margin-bottom: 20px;">
                <span>Lantai 2</span>
                <span>&#9660;</span>
            </div>

            <button id="btn-tambah-kamar-2" class="btn-primary">
                Tambah Kamar
            </button>
        </div>
        <div id="input-data-kamar-lantai-2-view" class="app-view" style="display: none;">
            <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Input Data Kamar Lantai 2</h2>

            <div id="btn-lantai-1-from-2" class="list-expander" style="margin-bottom: 15px;">
                <span>Lantai 1</span>
                <span>&#9660;</span>
            </div>

            <div class="list-expander" style="background-color: #ddd; font-weight: bold; margin-bottom: 15px;">
                <span>Lantai 2</span>
                <span>&#9650;</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                <div class="room-item">
                    <div>
                        <p style="margin: 0; font-weight: bold;">No. 11</p>
                        <p style="margin: 0; font-size: 0.9em;" class="status-kosong">Status Kamar: Kosong</p>
                    </div>
                    <div class="room-item-price">
                        <p style="margin: 0;" class="price">Rp 1.500.000</p>
                        <p style="margin: 0;" class="period">/Bulan</p>
                    </div>
                </div>
                <div class="room-item">
                    <div>
                        <p style="margin: 0; font-weight: bold;">No. 20</p>
                        <p style="margin: 0; font-size: 0.9em;" class="status-terisi">Status Kamar: Terisi</p>
                    </div>
                    <div class="room-item-price">
                        <p style="margin: 0;" class="price">Rp 1.500.000</p>
                        <p style="margin: 0;" class="period">/Bulan</p>
                    </div>
                </div>
            </div>

            <button id="btn-tambah-kamar-3" class="btn-primary">
                Tambah Kamar
            </button>
        </div>
        <div id="input-data-kamar-form-view" class="app-view" style="display: none;">
            <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Input Data Kamar</h2>

            <form id="form-input-kamar">

                <div class="form-group">
                    <label for="nomor-kamar">Nomor Kamar:</label>
                    <input type="text" id="nomor-kamar" name="nomor-kamar">
                </div>

                <div class="form-group">
                    <label for="lantai">Lantai:</label>
                    <select id="lantai" name="lantai">
                        <option value="">Pilih Lantai</option>
                        <option value="1">Lantai 1</option>
                        <option value="2">Lantai 2</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status-kamar">Status Kamar:</label>
                    <select id="status-kamar" name="status-kamar">
                        <option value="">Pilih Status</option>
                        <option value="Kosong">Kosong</option>
                        <option value="Terisi">Terisi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="harga-per-bulan">Harga per Bulan (Rp):</label>
                    <input type="number" id="harga-per-bulan" name="harga-per-bulan">
                </div>

                <div class="form-group">
                    <label for="fasilitas">Fasilitas:</label>
                    <input type="text" id="fasilitas" name="fasilitas" placeholder="Contoh: AC, Kamar Mandi Dalam">
                </div>

                <div class="form-group">
                    <label for="ukuran-kamar">Ukuran Kamar (m²):</label>
                    <input type="text" id="ukuran-kamar" name="ukuran-kamar" placeholder="Contoh: 3x4">
                </div>

                <div class="form-group">
                    <label>Upload Foto Kamar:</label>
                    <div class="file-upload-container">
                        <input type="file" id="foto-kamar" name="foto-kamar" style="display: none;">
                        <label for="foto-kamar" class="file-upload-label">
                            Pilih File
                        </label>
                        <span id="file-name-display" style="color: #666; font-size: 0.9em;">Tidak ada file yang
                            dipilih</span>
                    </div>
                </div>

                <button type="submit" id="btn-simpan" class="btn-primary" style="margin-top: 20px;">
                    Simpan
                </button>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const views = {
            manajemen: document.getElementById('manajemen-kamar-view'),
            lantaiSelection: document.getElementById('input-data-kamar-lantai-selection-view'),
            lantai1: document.getElementById('input-data-kamar-lantai-1-view'),
            lantai2: document.getElementById('input-data-kamar-lantai-2-view'),
            form: document.getElementById('input-data-kamar-form-view')
        };

        function showView(viewName) {
            Object.values(views).forEach(view => {
                if (view) view.style.display = 'none';
            });
            if (views[viewName]) {
                views[viewName].style.display = 'block';
                window.scrollTo(0, 0);
            }
        }

        document.getElementById('btn-input-data-kamar').addEventListener('click', () => {
            showView('lantaiSelection');
        });

        document.getElementById('listfield-lantai-1').addEventListener('click', () => {
            showView('lantai1');
        });

        document.getElementById('listfield-lantai-2').addEventListener('click', () => {
            showView('lantai2');
        });

        document.getElementById('btn-lantai-2-from-1').addEventListener('click', () => {
            showView('lantai2');
        });
        document.getElementById('btn-lantai-1-from-2').addEventListener('click', () => {
            showView('lantai1');
        });

        const tambahKamarButtons = ['btn-tambah-kamar-1', 'btn-tambah-kamar-2', 'btn-tambah-kamar-3'];
        tambahKamarButtons.forEach(id => {
            const btn = document.getElementById(id);
            if (btn) {
                btn.addEventListener('click', () => {
                    showView('form');
                });
            }
        });

        document.getElementById('form-input-kamar').addEventListener('submit', function(e) {
            e.preventDefault();

            alert('Data sudah tersimpan');

            showView('manajemen');

            this.reset();
            document.getElementById('file-name-display').textContent = 'Tidak ada file yang dipilih';
        });

        // Logika untuk menampilkan nama file yang dipilih
        const fileInput = document.getElementById('foto-kamar');
        const fileNameDisplay = document.getElementById('file-name-display');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileNameDisplay.textContent = this.files[0].name;
                } else {
                    fileNameDisplay.textContent = 'Tidak ada file yang dipilih';
                }
            });
        }
        showView('manajemen');
    });
    </script>
</body>

</html>