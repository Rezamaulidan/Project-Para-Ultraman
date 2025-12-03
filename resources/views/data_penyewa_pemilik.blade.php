@extends('profil_pemilik')
@section('title', 'Informasi Data Penyewa - SIMK')

@section('content')
<style>
:root {
    --kos-navy: #001931;
    --kos-blue-light: #e6f0fa;
    --kos-white: #ffffff;
}

/* Container utama */
.list-container {
    padding: 40px 20px;
    background-color: #f4f6f9;
    min-height: 85vh;
}

/* Styling Judul & Search Bar */
.page-header {
    margin-bottom: 30px;
}

.search-box {
    position: relative;
}

.search-box input {
    padding-left: 45px;
    border-radius: 50px;
    border: 2px solid transparent;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    height: 50px;
    transition: all 0.3s;
}

.search-box input:focus {
    border-color: var(--kos-navy);
    box-shadow: 0 5px 20px rgba(0, 25, 49, 0.15);
}

.search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
}

/* Styling Tabel Custom */
.table-custom {
    border-collapse: separate;
    border-spacing: 0 15px;
    /* Memberi jarak antar baris agar seperti kartu terpisah */
    width: 100%;
}

.table-custom thead th {
    border: none;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 1px;
    padding: 0 20px 10px 20px;
}

/* Baris Data (Row) yang melayang */
.row-card {
    background-color: var(--kos-white);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
    cursor: pointer;
}

/* Efek Hover: Row sedikit naik dan shadow menebal */
.row-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 25, 49, 0.1);
    z-index: 10;
}

/* Rounding sudut kiri dan kanan row */
.row-card td:first-child {
    border-top-left-radius: 15px;
    border-bottom-left-radius: 15px;
}

.row-card td:last-child {
    border-top-right-radius: 15px;
    border-bottom-right-radius: 15px;
}

.row-card td {
    border: none;
    padding: 20px;
    vertical-align: middle;
}

/* Avatar Kecil */
.avatar-small {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--kos-navy);
    padding: 2px;
}

/* Teks Styling */
.name-text {
    font-weight: 700;
    color: var(--kos-navy);
    display: block;
    font-size: 1.05rem;
}

.username-text {
    font-size: 0.85rem;
    color: #888;
}

/* Badge Kamar */
.room-badge {
    background-color: var(--kos-blue-light);
    color: var(--kos-navy);
    padding: 8px 15px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Tombol Panah */
.btn-detail-arrow {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: transparent;
    border: 2px solid #eee;
    color: var(--kos-navy);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.row-card:hover .btn-detail-arrow {
    background-color: var(--kos-navy);
    border-color: var(--kos-navy);
    color: white;
}
</style>

<div class="list-container">
    <div class="container">

        <div class="row page-header align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold mb-1" style="color: #001931;">Daftar Penyewa</h2>
                <p class="text-muted">Kelola data penghuni kos Anda di sini.</p>
            </div>
            <div class="col-md-6">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <form id="search-form" action="{{ url()->current() }}" method="GET">
                        <input id="search-input" name="q" type="text" class="form-control"
                            placeholder="Cari nama penyewa atau nomor kamar..." value="{{ request('q') }}">
                    </form>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th width="40%">Nama Penyewa</th>
                        <th width="20%">No. Kamar</th>
                        <th width="25%">Kontak (HP)</th>
                        <th width="15%" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- LOGIKA DINAMIS DIMULAI DI SINI --}}
                    @forelse ($penyewas as $penyewa)
                    <tr class="row-card" onclick="window.location.href='/info-detail-penyewa/{{ $penyewa->username }}'">
                        <td>
                            <div class="d-flex align-items-center">
                                @php
                                // Pastikan base storage URL tersedia, fallback ke asset('storage')
                                $storageBase = isset($storageUrl) && $storageUrl
                                ? $storageUrl
                                : asset('storage/');

                                // LOGIKA BARU UNTUK FOTO DEFAULT BERDASARKAN JENIS KELAMIN
                                $gender = $penyewa->jenis_kelamin ?? 'Laki-laki';
                                $defaultImageUrl = '';

                                if (strcasecmp($gender, 'Laki-laki') === 0) {
                                $defaultImageUrl =
                                'https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436188.jpg';
                                } else {
                                $defaultImageUrl = asset('images/3d-cartoon-avatar-woman.png');
                                }

                                // Ambil URL foto profil, jika null/kosong gunakan defaultImageUrl
                                $fotoUrl = $penyewa->foto_profil
                                ? rtrim($storageBase, '/') . '/' . ltrim($penyewa->foto_profil, '/')
                                : $defaultImageUrl;
                                @endphp
                                <img src="{{ $fotoUrl }}" class="avatar-small me-3" alt="{{ $penyewa->nama_penyewa }}">
                                <div>
                                    <span class="name-text">{{ $penyewa->nama_penyewa }}</span>
                                    <span class="username-text">@ {{ $penyewa->username }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($penyewa->booking && $penyewa->booking->kamar)
                            <span class="room-badge">
                                <i class="fas fa-door-closed me-2"></i>
                                {{ $penyewa->booking->kamar->no_kamar ?? 'N/A' }}
                            </span>
                            @else
                            <span class="room-badge bg-warning text-dark">N/A (Cek Booking)</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-secondary">{{ $penyewa->no_hp }}</span>
                        </td>
                        <td class="text-end">
                            {{-- Ganti tag button dengan link untuk navigasi detail --}}
                            <a href="/info-detail-penyewa/{{ $penyewa->username }}" class="btn-detail-arrow ms-auto">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr class="row-card">
                        <td colspan="4" class="text-center py-5 text-muted">
                            Tidak ada data penyewa yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                    {{-- LOGIKA DINAMIS BERAKHIR DI SINI --}}

                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
// Ambil input pencarian
const searchInput = document.getElementById('search-input');
const searchForm = document.getElementById('search-form');

// Tambahkan event listener untuk mendeteksi ketika input berubah
searchInput.addEventListener('keyup', function(event) {
    // Cek jika tombol yang ditekan adalah Enter (opsional: bisa dihilangkan jika ingin langsung mencari)
    // if (event.key === 'Enter') {
    //     searchForm.submit();
    // }
    // Atau, bisa menggunakan delay untuk otomatis submit (misal setelah 500ms)
});

// Auto submit form saat value berubah (untuk mempermudah testing)
searchInput.addEventListener('input', function() {
    // Agar tidak submit terlalu cepat, gunakan debounce
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        searchForm.submit();
    }, 500);
});

// Menonaktifkan submit form saat menekan enter pada input
searchInput.closest('form').addEventListener('submit', function(e) {
    e.preventDefault();
    searchForm.submit();
});
</script>
@endsection