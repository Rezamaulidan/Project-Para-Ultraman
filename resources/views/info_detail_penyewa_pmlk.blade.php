@extends('profil_pemilik')
@section('title', 'Informasi Data Penyewa - SIMK')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

{{-- Helper function untuk format tanggal dan uang (asumsi tersedia atau di-load) --}}
@php
// Tentukan URL gambar default berdasarkan jenis kelamin
$gender = $penyewa->jenis_kelamin; // Asumsikan ini adalah variabel yang menyimpan jenis kelamin
$defaultImageUrl = '';

if ($gender === 'Laki-laki') {
// URL ikon laki-laki yang Anda berikan
$defaultImageUrl = 'https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436188.jpg';
} else {
// URL ikon perempuan (menggunakan asset() untuk path lokal)
$defaultImageUrl = asset('/images/3d-cartoon-avatar-woman.png');
}
$tglLahir = $penyewa->tgl_lahir ? \Carbon\Carbon::parse($penyewa->tgl_lahir)->isoFormat('DD MMMM YYYY') : 'N/A';
// Nomor HP harus diawali 62 untuk link WA
$noHpWa = str_replace([' ', '-'], '', $penyewa->no_hp);
if (!str_starts_with($noHpWa, '62') && str_starts_with($noHpWa, '0')) {
$noHpWa = '62' . substr($noHpWa, 1);
}
// Cek apakah ada booking aktif
$bookingAktif = $penyewa->booking;
$noKamar = $bookingAktif && $bookingAktif->kamar ? $bookingAktif->kamar->no_kamar : 'Belum Ada';
$tglMasuk = $bookingAktif ? \Carbon\Carbon::parse($bookingAktif->tanggal)->isoFormat('DD MMMM YYYY') : 'N/A';
@endphp

<style>
:root {
    --kos-navy: #001931;
    --kos-soft-navy: #002a52;
    --kos-white: #ffffff;
}

/* Styling Container agar rapi */
.detail-container {
    padding: 40px 20px;
    background-color: #f4f6f9;
    /* Background halaman abu sangat muda */
    min-height: 80vh;
}

/* Card Utama */
.card-penyewa {
    border: none;
    border-radius: 25px;
    /* Sudut melengkung biar "lucu"/soft */
    background-color: var(--kos-white);
    box-shadow: 0 10px 30px rgba(0, 25, 49, 0.1);
    /* Bayangan soft warna navy */
    overflow: hidden;
}

/* Bagian Header Biru di dalam Card */
.card-header-custom {
    background-color: var(--kos-navy);
    color: white;
    padding: 20px 30px;
    border-bottom: 5px solid #e9ecef;
    position: relative;
}

/* Hiasan lengkungan kecil di header */
.card-header-custom::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 100%;
    height: 20px;
    background-color: var(--kos-white);
    border-radius: 20px 20px 0 0;
}

/* Foto Profil */
.profile-section {
    text-align: center;
    padding: 30px;
    /* Menambahkan perataan tengah vertikal */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100%;
    /* Pastikan section mengambil tinggi penuh kolom */
}

/* Kustomisasi untuk Ikon Default */
.default-profile-icon {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    border: 5px solid var(--kos-navy);
    padding: 3px;
    background: #e6f0fa;
    /* Warna latar belakang ikon */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8rem;
    /* Ukuran ikon */
    color: var(--kos-navy);
    /* Warna ikon */
}

/* Kelas untuk Foto Nyata (jika ada) */
.img-thumbnail-custom {
    width: 180px;
    height: 180px;
    object-fit: cover;
    border-radius: 50%;
    /* Lingkaran */
    border: 5px solid var(--kos-navy);
    padding: 3px;
    background: white;
}

.user-badge {
    background-color: var(--kos-navy);
    color: white;
    padding: 5px 15px;
    border-radius: 50px;
    font-size: 0.85rem;
    margin-top: 10px;
    display: inline-block;
}

/* Styling Label dan Value Data */
.data-row {
    margin-bottom: 20px;
    border-bottom: 1px dashed #e0e0e0;
    padding-bottom: 10px;
}

.data-label {
    font-weight: 600;
    color: #555;
    font-size: 0.9rem;
    display: block;
    margin-bottom: 5px;
}

.data-value {
    font-weight: 700;
    color: var(--kos-navy);
    font-size: 1.1rem;
}

.icon-box {
    width: 40px;
    height: 40px;
    background-color: #e6f0fa;
    /* Biru sangat muda */
    color: var(--kos-navy);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    margin-right: 15px;
    font-size: 1.2rem;
}

/* Tombol Back */
.btn-back {
    background-color: transparent;
    color: var(--kos-navy);
    border: 2px solid var(--kos-navy);
    border-radius: 50px;
    padding: 8px 25px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-back:hover {
    background-color: var(--kos-navy);
    color: white;
}
</style>

<div class="detail-container">
    <div class="container">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ url('datapenyewapemilik') }}" class="btn btn-back me-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h3 class="m-0 fw-bold" style="color: #001931;">Detail Identitas Penyewa</h3>
        </div>
        <div class="card card-penyewa">
            <div class="card-header-custom text-center">
                <h5 class="m-0"><i class="fas fa-id-card me-2"></i> Informasi Personal & Status Sewa
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    {{-- KOLOM KIRI: Foto, Nama, & Status. Dibuat flex untuk rata tengah vertikal. --}}
                    <div class="col-md-4 d-flex flex-column align-items-center justify-content-center"
                        style="background-color: #fcfcfc; border-right: 1px solid #eee;">
                        <div class="profile-section">
                            {{-- Logic Foto Profil: Jika default, tampilkan ikon. Jika tidak, tampilkan gambar. --}}
                            @php
                            $fotoPenyewa = $penyewa->foto_profil ?? $penyewa->foto_ktp ?? 'default-profile.png';
                            $isDefault = (strpos($fotoPenyewa, 'default') !== false);
                            $fotoUrl = !$isDefault ? $storageUrl . $fotoPenyewa : '';
                            @endphp

                            @if($isDefault)
                            <div class="default-profile-icon shadow-sm">
                                <img src="{{ $defaultImageUrl }}" alt="Foto Default Penyewa"
                                    class="img-thumbnail-custom shadow-sm">
                            </div>
                            @else
                            {{-- Jika ada foto yang diunggah, tampilkan foto tersebut --}}
                            <img src="{{ $fotoUrl }}" alt="Foto Penyewa" class="img-thumbnail-custom shadow-sm">
                            @endif

                            {{-- Nama Lengkap dirapihkan rata tengah dengan foto --}}
                            <h4 class="mt-3 fw-bold text-center" style="color: #001931;">
                                {{ $penyewa->nama_penyewa ?? 'N/A' }}</h4>
                            {{-- Status Penyewa Dinamis (juga rata tengah) --}}
                            <span class="user-badge 
                            @if($statusPenyewa == 'Penyewa Aktif') badge-aktif
                            @elseif($statusPenyewa == 'Terlambat Bayar') badge-terlambat
                            @else badge-tidak-aktif
                            @endif">
                                {{ $statusPenyewa }}
                            </span>
                        </div>
                    </div>
                    {{-- KOLOM KANAN: Detail Data --}}
                    <div class="col-md-8 p-5">
                        <h5 class="fw-bold mb-4" style="color: var(--kos-soft-navy);">Data Kontak & Identitas</h5>
                        <div class="row">
                            {{-- Baris 1: Username & Jenis Kelamin --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-start data-row">
                                    <div class="icon-box"><i class="fas fa-user"></i></div>
                                    <div>
                                        <span class="data-label">Username</span>
                                        <span class="data-value">@
                                            {{ $penyewa->username ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-start data-row">
                                    <div class="icon-box"><i class="fas fa-venus-mars"></i></div>
                                    <div>
                                        <span class="data-label">Jenis
                                            Kelamin</span>
                                        <span class="data-value">{{ $penyewa->jenis_kelamin ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Baris 2: Alamat Email & NOMOR HANDPHONE (Pindah ke sini) --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-start data-row">
                                    <div class="icon-box"><i class="fas fa-envelope"></i></div>
                                    <div>
                                        <span class="data-label">Alamat
                                            Email</span>
                                        <span class="data-value">{{ $penyewa->email ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-start data-row">
                                    <div class="icon-box"><i class="fas fa-phone-alt"></i></div>
                                    <div>
                                        <span class="data-label">Nomor Handphone
                                            / WhatsApp</span>
                                        <span class="data-value">{{ $penyewa->no_hp ?? 'N/A' }}</span>
                                        @if($penyewa->no_hp)
                                        <a href="https://wa.me/{{ $noHpWa }}"
                                            class="btn btn-sm btn-success ms-2 rounded-pill" target="_blank"><i
                                                class="fab fa-whatsapp"></i>
                                            Chat</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Informasi Tambahan (Tanggal Lahir, KTP, dll. - Tambahkan sesuai kebutuhan) --}}

                        </div> {{-- end row Data Kontak & Identitas --}}


                        {{-- Informasi Kamar Aktif --}}
                        <h5 class="fw-bold mt-5 mb-4 pt-3 border-top" style="color: var(--kos-soft-navy);">Informasi
                            Sewa Kamar</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start data-row">
                                    <div class="icon-box"><i class="fas fa-door-closed"></i></div>
                                    <div>
                                        <span class="data-label">Nomor
                                            Kamar</span>
                                        <span class="data-value">{{ $noKamar }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start data-row">
                                    <div class="icon-box"><i class="fas fa-calendar-alt"></i></div>
                                    <div>
                                        <span class="data-label">Tanggal Masuk
                                            (Awal Sewa)</span>
                                        <span class="data-value">{{ $tglMasuk }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($bookingAktif)
                            <div class="col-md-12">
                                <div class="d-flex align-items-start data-row border-0">
                                    <div class="icon-box"><i class="fas fa-info-circle"></i></div>
                                    <div>
                                        <span class="data-label">Periode Bayar
                                            Terakhir / Nominal</span>
                                        <span class="data-value">
                                            {{ $bookingAktif->durasi_sewa ?? 'N/A' }}
                                            /
                                            Rp{{ number_format($bookingAktif->nominal, 0, ',', '.') ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection