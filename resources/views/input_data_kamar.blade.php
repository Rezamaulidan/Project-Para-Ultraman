{{-- resources/views/partials/kamar_input.blade.php --}}

{{-- 2. Tampilan Input Data Kamar (Pilihan Lantai) --}}
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

<!-- Tampilan Daftar Kamar Lantai 1 -->
<div id="input-data-kamar-lantai-1-view" class="app-view" style="display: none;">
    <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Input Data Kamar Lantai 1</h2>

    <div class="list-expander active-floor" style="margin-bottom: 15px;">
        <span>Lantai 1</span>
        <span>&#9650;</span>
    </div>

    <div class="room-list" style="margin-bottom: 20px;">
        <div class="room-item">
            <div>
                <p class="room-number">No. 1</p>
                <p class="room-status status-kosong">Status Kamar: Kosong</p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 500.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 2</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 500.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 3</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 500.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 4</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 500.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 5</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 500.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 6</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 700.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 7</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 700.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 8</p>
                <p class="room-status status-kosong">Status Kamar: Kosong</p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 700.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 9</p>
                <p class="room-status status-kosong">Status Kamar: Kosong</p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 700.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 10</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 1.000.000</p>
                <p class="period">/Bulan</p>
            </div>
        </div>
    </div>

    <!-- Tampilan Daftar Kamar Lantai 2 -->
    <div id="btn-lantai-2-from-1" class="list-expander switch-floor" style="margin-bottom: 20px;">
        <span>Lantai 2</span>
        <span>&#9660;</span>
    </div>

    <button id="btn-tambah-kamar-2" class="btn-primary">
        Tambah Kamar
    </button>
</div>

<div id="input-data-kamar-lantai-2-view" class="app-view" style="display: none;">
    <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Input Data Kamar Lantai 2</h2>

    <div id="btn-lantai-1-from-2" class="list-expander switch-floor" style="margin-bottom: 15px;">
        <span>Lantai 1</span>
        <span>&#9660;</span>
    </div>

    <div class="list-expander active-floor" style="margin-bottom: 15px;">
        <span>Lantai 2</span>
        <span>&#9650;</span>
    </div>

    <div class="room-list" style="margin-bottom: 20px;">
        {{-- Item Kamar 11 (Kosong) --}}
        <div class="room-item">
            <div>
                <p class="room-number">No. 11</p>
                <p class="room-status status-kosong">Status Kamar: Kosong</p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 950.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 12</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 950.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 13</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 950.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 14</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 950.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 15</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 950.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 16</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 500.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 17</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 800.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 18</p>
                <p class="room-status status-kosong">Status Kamar: Kosong</p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 800.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 19</p>
                <p class="room-status status-kosong">Status Kamar: Kosong</p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 800.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>

        <div class="room-item">
            <div>
                <p class="room-number">No. 20</p>
                <p class="room-status status-terisi"><b>Status Kamar: Terisi</b></p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 800.000,-</p>
                <p class="period">/Bulan</p>
            </div>
        </div>
    </div>

    <button id="btn-tambah-kamar-3" class="btn-primary">
        Tambah Kamar
    </button>
</div>

<!-- Tampilan Formulir Input Data Kamar -->
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
                <span id="file-name-display">Tidak ada file yang dipilih</span>
            </div>
        </div>

        <button type="submit" id="btn-simpan" class="btn-primary" style="margin-top: 20px;">
            Simpan
        </button>
    </form>
</div>