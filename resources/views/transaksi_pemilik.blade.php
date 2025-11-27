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
        <input type="text" class="search-box" placeholder="🔍 Cari berdasarkan nama penyewa atau kode transaksi...">

        <div class="filter-group">
            <select class="filter-select">
                <option value="">Status: Semua</option>
                <option value="lunas">Lunas</option>
                <option value="belum">Belum Lunas</option>
                <option value="tempo">Jatuh Tempo</option>
            </select>
            <select class="filter-select">
                <option value="">Bulan: Semua</option>
                <option value="nov2025">Nov 2025</option>
            </select>
            <button class="export-button">📥 Ekspor ke CSV/Excel</button>
        </div>
    </div>

    <div class="transaction-table-wrapper">
        <table class="transaction-table">
            <thead>
                <tr class="navy-header">
                    <th>No. Transaksi</th>
                    <th>Penyewa</th>
                    <th>Kamar</th>
                    <th>Periode Bayar</th>
                    <th>Jumlah Bayar</th>
                    <th>Tgl. Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="No. Transaksi">TRKS-005</td>
                    <td data-label="Penyewa">Budi Santoso</td>
                    <td data-label="Kamar">C-03</td>
                    <td data-label="Periode Bayar">Nov 2025</td>
                    <td data-label="Jumlah Bayar" class="amount">Rp 1.500.000</td>
                    <td data-label="Tgl. Bayar">04 Nov 2025</td>
                    <td data-label="Status">
                        <span class="status-tag status-lunas">✅ Lunas</span>
                    </td>
                    <td data-label="Aksi">
                        <button class="action-btn navy-bg">👁️</button>
                        <button class="action-btn navy-bg">⬇️</button>
                    </td>
                </tr>

                <tr>
                    <td data-label="No. Transaksi">TRKS-004</td>
                    <td data-label="Penyewa">Dian Paramita</td>
                    <td data-label="Kamar">A-10</td>
                    <td data-label="Periode Bayar">Nov 2025</td>
                    <td data-label="Jumlah Bayar" class="amount">Rp 1.200.000</td>
                    <td data-label="Tgl. Bayar">10 Nov 2025</td>
                    <td data-label="Status">
                        <span class="status-tag status-tempo">⚠️ Jatuh Tempo</span>
                    </td>
                    <td data-label="Aksi">
                        <button class="action-btn navy-bg">🔔</button>
                        <button class="action-btn navy-bg">✏️</button>
                    </td>
                </tr>

                <tr>
                    <td data-label="No. Transaksi">TRKS-002</td>
                    <td data-label="Penyewa" class="light-text">*Belum Ada*</td>
                    <td data-label="Kamar">D-01</td>
                    <td data-label="Periode Bayar">Nov 2025</td>
                    <td data-label="Jumlah Bayar" class="amount">Rp 1.300.000</td>
                    <td data-label="Tgl. Bayar">-</td>
                    <td data-label="Status">
                        <span class="status-tag status-belum">❌ Belum Lunas</span>
                    </td>
                    <td data-label="Aksi">
                        <button class="action-btn navy-bg">➕</button>
                        <button class="action-btn navy-bg">🔔</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span class="pagination-info">Menampilkan 1-10 dari 55 Transaksi</span>
        <div class="pagination-links">
            <button class="page-link disabled">&lt; Sebelumnya</button>
            <button class="page-link active">1</button>
            <button class="page-link">2</button>
            <button class="page-link">3</button>
            <button class="page-link">Selanjutnya &gt;</button>
        </div>
    </div>

</div>
@endsection