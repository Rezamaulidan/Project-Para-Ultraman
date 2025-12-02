@extends('profil_pemilik')
@section('title', 'Transaksi - SIMK')

@section('content')
<style>
/* Definisi Warna */
:root {
    --navy: #001931;
    --white: #ffffff;
    --red-alert: #b30000;
    --green-lunas: #d4edda;
    /* Light Green */
    --orange-tempo: #fff3cd;
    /* Light Orange */
    --blue-belum: #d0e8f2;
    /* Light Blue */
    --text-navy: #001931;
}

/* Container Utama */
.transaction-container {
    padding: 30px;
    background-color: var(--white);
    font-family: 'Poppins', sans-serif;
    /* Ganti dengan font yang Anda gunakan */
}

/* --- RINGKASAN KEUANGAN --- */
.financial-snapshot {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 30px;
}

.card {
    flex: 1;
    background: var(--white);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.card-icon {
    font-size: 24px;
    margin-bottom: 10px;
}

.card-label {
    font-size: 14px;
    color: #6c757d;
    margin: 0;
}

.card-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-navy);
    margin-top: 5px;
}

.red-text {
    color: var(--red-alert) !important;
}

.separator-line {
    border: 0;
    height: 1px;
    background-color: #e9ecef;
    margin: 20px 0;
}

/* --- FILTER DAN PENCARIAN --- */
.filter-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    /* Untuk responsif */
    gap: 15px;
}

.search-box {
    padding: 10px 15px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    width: 300px;
    transition: border-color 0.3s;
    color: var(--text-navy);
}

.search-box:focus {
    border-color: var(--navy);
    outline: none;
}

.filter-group {
    display: flex;
    gap: 10px;
}

.filter-select {
    padding: 10px 15px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    color: var(--text-navy);
    background-color: var(--white);
    cursor: pointer;
}

.export-button {
    background-color: var(--navy);
    color: var(--white);
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: background-color 0.3s;
}

.export-button:hover {
    background-color: #000c1d;
    /* Warna Navy lebih gelap */
}

/* --- TABEL RIWAYAT TRANSAKSI --- */
.transaction-table-wrapper {
    overflow-x: auto;
    /* Penting untuk tabel yang responsif */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border-radius: 12px;
}

.transaction-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
    /* Minimal lebar tabel */
    background-color: var(--white);
    border-radius: 12px;
    overflow: hidden;
}

.navy-header th {
    background-color: var(--navy);
    color: var(--white);
    padding: 15px;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
}

.transaction-table tbody tr {
    border-bottom: 1px solid #f1f1f1;
    transition: background-color 0.3s;
}

.transaction-table tbody tr:hover {
    background-color: #f8f9fa;
}

.transaction-table td {
    padding: 15px;
    color: var(--text-navy);
    font-size: 14px;
}

.amount {
    font-weight: 600;
}

.light-text {
    color: #999;
    font-style: italic;
}

/* Status Tags */
.status-tag {
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 12px;
    display: inline-block;
}

.status-lunas {
    background-color: var(--green-lunas);
    color: #155724;
    /* Dark Green Text */
}

.status-tempo {
    background-color: var(--orange-tempo);
    color: #856404;
    /* Dark Orange Text */
}

.status-belum {
    background-color: var(--blue-belum);
    color: var(--navy);
}

/* Action Buttons */
.action-btn {
    background-color: var(--navy);
    color: var(--white);
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    cursor: pointer;
    margin-right: 5px;
    font-size: 14px;
    transition: opacity 0.2s;
}

.action-btn:hover {
    opacity: 0.8;
}

/* --- PAGINATION --- */
.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
}

.pagination-info {
    color: #6c757d;
    font-size: 14px;
}

.pagination-links {
    display: flex;
    gap: 5px;
}

.page-link {
    background: var(--white);
    color: var(--text-navy);
    border: 1px solid #ced4da;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

.page-link:hover:not(.active):not(.disabled) {
    background-color: #e9ecef;
}

.page-link.active {
    background-color: var(--navy);
    color: var(--white);
    border-color: var(--navy);
    font-weight: 600;
}

.page-link.disabled {
    cursor: not-allowed;
    opacity: 0.5;
}
</style>

<div class="transaction-container">

    <div class="filter-controls">
        <input type="text" id="search-input" class="search-box" placeholder="🔍 Cari nama penyewa (ketik langsung)...">

        <div class="filter-group">
            <select class="filter-select" style="padding: 10px;">
                <option value="">Bulan: Semua</option>
                <option value="nov2025">Nov 2025</option>
            </select>
            <a href="{{ route('transaksi.export') }}" class="export-button">📥 Ekspor ke CSV/Excel</a>
        </div>
    </div>

    <div class="transaction-table-wrapper">
        <table class="transaction-table">
            <thead>
                <tr class="navy-header">
                    <th>ID. Transaksi</th>
                    <th>Penyewa</th>
                    <th>Kamar</th>
                    <th>Periode Bayar</th>
                    <th>Jumlah Bayar</th>
                    <th>Tgl. Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="transaksi-table-body">
                @include('partials.transaksi_table_rows', ['transaksis' => $transaksis])
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span class="pagination-info" id="pagination-info">
            Menampilkan {{ $transaksis->firstItem() }} - {{ $transaksis->lastItem() }} dari {{ $transaksis->total() }}
            Transaksi
        </span>
        <div class="pagination-links" id="pagination-links">
            {{ $transaksis->links('pagination::bootstrap-4') }}
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Cek apakah script jalan
    console.log("Script Transaksi Siap!");

    var searchTimer;

    // Event listener saat mengetik
    $('#search-input').on('keyup', function() {
        var query = $(this).val();
        console.log("Mengetik: " + query); // Cek di console browser

        clearTimeout(searchTimer);

        // Tunggu 300ms baru kirim request (biar ga berat)
        searchTimer = setTimeout(function() {
            fetchData(query, 1);
        }, 300);
    });

    // Event listener pagination (supaya kalau klik halaman 2, pencarian tetap jalan)
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        var query = $('#search-input').val();
        fetchData(query, page);
    });

    function fetchData(query, page) {
        $.ajax({
            url: "{{ route('transaksi.search') }}",
            method: "GET",
            data: {
                query: query,
                page: page
            },
            success: function(data) {
                console.log("Berhasil dapat data!"); // Cek console
                $('#transaksi-table-body').html(data.html);
                $('#pagination-links').html(data.pagination_links);
                $('#pagination-info').text(data.pagination_info);
            },
            error: function(xhr) {
                console.log("Error AJAX:");
                console.log(xhr.responseText); // Cek error di console
                alert("Gagal memuat data. Cek Console (F12) untuk detail.");
            }
        });
    }
});
</script>
@endsection