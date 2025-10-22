{{-- resources/views/partials/kamar_info.blade.php --}}

{{-- 11. Tampilan Informasi Kamar (Pilihan Lantai) --}}
<div id="info-data-kamar-lantai-selection-view" class="app-view" style="display: none;">
    <h2 style="color: #007bff; margin-bottom: 30px;">Informasi Kamar</h2>
    <div style="display: flex; flex-direction: column; gap: 15px;">
        {{-- Tombol/List Lantai 1 --}}
        <div id="info-listfield-lantai-1" class="list-expander">
            Lantai 1
            <span>&#9660;</span>
        </div>
        {{-- Tombol/List Lantai 2 --}}
        <div id="info-listfield-lantai-2" class="list-expander">
            Lantai 2
            <span>&#9660;</span>
        </div>
    </div>
</div>

{{-- 12. Tampilan Daftar Kamar Lantai 1 (Informasi) --}}
<div id="info-data-kamar-lantai-1-view" class="app-view" style="display: none;">
    <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Informasi Kamar Lantai 1</h2>

    <div class="list-expander active-floor" style="margin-bottom: 15px;">
        <span>Lantai 1</span>
        <span>&#9650;</span>
    </div>

    <div id="info-room-list-lantai-1" class="room-list" style="margin-bottom: 20px;">
        {{-- Item Kamar 1 (ID untuk klik detail) --}}
        <div class="room-item info-room-clickable" data-room-id="1">
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
        <div class="room-item info-room-clickable" data-room-id="2">
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

    <div id="info-btn-lantai-2-from-1" class="list-expander switch-floor" style="margin-bottom: 20px;">
        <span>Lantai 2</span>
        <span>&#9660;</span>
    </div>
</div>

{{-- 13. Tampilan Daftar Kamar Lantai 2 (Informasi) --}}
<div id="info-data-kamar-lantai-2-view" class="app-view" style="display: none;">
    <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Informasi Kamar Lantai 2</h2>

    <div id="info-btn-lantai-1-from-2" class="list-expander switch-floor" style="margin-bottom: 15px;">
        <span>Lantai 1</span>
        <span>&#9660;</span>
    </div>

    <div class="list-expander active-floor" style="margin-bottom: 15px;">
        <span>Lantai 2</span>
        <span>&#9650;</span>
    </div>

    <div id="info-room-list-lantai-2" class="room-list" style="margin-bottom: 20px;">
        {{-- Item Kamar 11 (ID untuk klik detail) --}}
        <div class="room-item info-room-clickable" data-room-id="11">
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

{{-- 14. Tampilan Detail Informasi Kamar (Hanya Baca) --}}
<div id="detail-info-kamar-view" class="app-view" style="display: none;">
    <h2 style="color: #007bff; text-align: center; margin-bottom: 30px;">Detail Informasi Kamar No. 2</h2>

    <div class="detail-container" style="text-align: left; margin: 0 auto; max-width: 400px;">
        <div class="image-placeholder" style="text-align: center; margin-bottom: 20px;">
            
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

</div>