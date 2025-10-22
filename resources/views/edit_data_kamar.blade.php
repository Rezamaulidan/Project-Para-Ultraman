<div id="edit-data-kamar-lantai-selection-view" class="app-view" style="display: none;">
    <h2 style="color: #007bff; margin-bottom: 30px;">Edit Data Kamar</h2>
    <div style="display: flex; flex-direction: column; gap: 15px;">

        <div id="edit-listfield-lantai-1" class="list-expander">
            Lantai 1
            <span>&#9660;</span>
        </div>

        <div id="edit-listfield-lantai-2" class="list-expander">
            Lantai 2
            <span>&#9660;</span>
        </div>

    </div>
</div>

<!-- Tampilan Daftar Kamar Lantai 1 (Edit) -->
<div id="edit-data-kamar-lantai-1-view" class="app-view" style="display: none;">
    <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Edit Data Kamar Lantai 1</h2>

    <div class="list-expander active-floor" style="margin-bottom: 15px;">
        <span>Lantai 1</span>
        <span>&#9650;</span>
    </div>

    <div id="edit-room-list-lantai-1" class="room-list" style="margin-bottom: 20px;">
        {{-- Item Kamar 1 (ID untuk klik detail) --}}
        <div class="room-item room-clickable" data-room-id="1">
            <div>
                <p class="room-number">No. 1</p>
                <p class="room-status status-kosong">Status Kamar: Kosong</p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 1.000.000</p>
                <p class="period">/Bulan</p>
            </div>
        </div>
        {{-- Item Kamar 2 (Untuk contoh klik) --}}
        <div class="room-item room-clickable" data-room-id="2">
            <div>
                <p class="room-number">No. 2</p>
                <p class="room-status status-terisi">Status Kamar: Terisi</p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 1.000.000</p>
                <p class="period">/Bulan</p>
            </div>
        </div>
        {{-- ... ulangi untuk kamar 3 s/d 10 ... --}}
    </div>

    <div id="edit-btn-lantai-2-from-1" class="list-expander switch-floor" style="margin-bottom: 20px;">
        <span>Lantai 2</span>
        <span>&#9660;</span>
    </div>
</div>

{{-- 8. Tampilan Daftar Kamar Lantai 2 (Edit) --}}
<div id="edit-data-kamar-lantai-2-view" class="app-view" style="display: none;">
    <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Edit Data Kamar Lantai 2</h2>

    <div id="edit-btn-lantai-1-from-2" class="list-expander switch-floor" style="margin-bottom: 15px;">
        <span>Lantai 1</span>
        <span>&#9660;</span>
    </div>

    <div class="list-expander active-floor" style="margin-bottom: 15px;">
        <span>Lantai 2</span>
        <span>&#9650;</span>
    </div>

    <div id="edit-room-list-lantai-2" class="room-list" style="margin-bottom: 20px;">
        {{-- Item Kamar 11 (ID untuk klik detail) --}}
        <div class="room-item room-clickable" data-room-id="11">
            <div>
                <p class="room-number">No. 11</p>
                <p class="room-status status-kosong">Status Kamar: Kosong</p>
            </div>
            <div class="room-item-price">
                <p class="price">Rp 1.500.000</p>
                <p class="period">/Bulan</p>
            </div>
        </div>
        {{-- ... ulangi untuk kamar 12 s/d 20 ... --}}
    </div>
</div>

{{-- 9. Tampilan Detail Kamar (Contoh Kamar No. 2 - edit kamar 2_terisi) --}}
<div id="detail-kamar-view" class="app-view" style="display: none;">
    <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Detail Kamar No. 2</h2>

    <div class="detail-container" style="text-align: left; margin: 0 auto; max-width: 400px;">
        <div class="image-placeholder" style="text-align: center; margin-bottom: 20px;">
            {{-- Ganti dengan tag <img> asli --}}
            <img src="images/gambar-kamar.jpg" style="max-width: 100%; height: auto;">
        </div>

        <p><strong>Nomor Kamar:</strong> 2</p>
        <p><strong>Lantai:</strong> 1</p>
        <p><strong>Status Kamar:</strong> Terisi</p>
        <p><strong>Harga:</strong> Rp 1.000.000 /Bulan</p>

        <h4 style="margin-top: 20px;">Fasilitas:</h4>
        <ul style="list-style: none; padding-left: 0;">
            <li>Lemari</li>
            <li>Kamar mandi dalam</li>
            <li>Kasur dan bantal</li>
            <li>Wifi</li>
            <li>Listrik</li>
            <li>Air</li>
        </ul>

        <p><strong>Ukuran Kamar:</strong> 4 x 3 meter</p>
    </div>

    <button id="btn-edit-detail" class="btn-primary" style="margin-top: 30px;">
        Edit
    </button>
</div>

{{-- 10. Tampilan Formulir Edit Data Kamar (edit data kamar 2_terisi) --}}
<div id="edit-data-kamar-form-view" class="app-view" style="display: none;">
    <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Edit Data Kamar No. 2</h2>

    <form id="form-edit-kamar">
        {{-- Isi formulir sama seperti form input, namun dengan data terisi --}}
        <div class="form-group">
            <label for="edit-nomor-kamar">Nomor Kamar:</label>
            <input type="text" id="edit-nomor-kamar" name="edit-nomor-kamar" value="2" disabled>
        </div>
        <div class="form-group">
            <label for="edit-lantai">Lantai:</label>
            <select id="edit-lantai" name="edit-lantai">
                <option value="1" selected>Lantai 1</option>
                <option value="2">Lantai 2</option>
            </select>
        </div>
        <div class="form-group">
            <label for="edit-status-kamar">Status Kamar:</label>
            <select id="edit-status-kamar" name="edit-status-kamar">
                <option value="Kosong">Kosong</option>
                <option value="Terisi" selected>Terisi</option>
            </select>
        </div>
        <div class="form-group">
            <label for="edit-harga-per-bulan">Harga per Bulan (Rp):</label>
            <input type="number" id="edit-harga-per-bulan" name="edit-harga-per-bulan" value="1000000">
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

        <button type="submit" id="btn-simpan-edit" class="btn-primary" style="margin-top: 20px;">
            Simpan perubahan
        </button>
    </form>
</div>