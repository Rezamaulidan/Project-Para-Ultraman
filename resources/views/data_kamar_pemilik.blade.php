@extends('profil_pemilik')
@section('title', 'Data Kamar - SIMK')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/style_kamar_pemilik.css') }}">
@endsection

@section('content')
<div id="main-content">

    <!-- Success Modal -->
    @if (session('success'))
    <div id="success-modal">
        <div class="modal-content">
            <div class="modal-body">
                <i class="fas fa-paw success-icon"></i>
                <h2>{{ session('success') }}</h2>
                <p>Yeeeaaayyy! Data kamar baru kamu sudah aman dan siap menampung penghuni~</p>
            </div>
            <div class="text-center pb-3">
                <button id="modal-ok-button" class="btn-simpan-v2">Lanjut</button>
            </div>
        </div>
    </div>
    @endif

    <div class="cards-container my-4">
        <!-- Header -->
        <div class="header-section">
            <h2>Informasi Kamar</h2>
            <a href="{{ route('pemilik.inputkamar') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Input Kamar Baru
            </a>
        </div>

        @if ($kamars->count() > 0)
        <!-- Cards Wrapper -->
        <div class="cards-wrapper">
            @foreach ($kamars as $kamar)
            <div class="card-item">
                <a href="{{ route('pemilik.infokamar', $kamar->no_kamar) }}"
                    style="text-decoration: none; color: inherit;">
                    <div class="card shadow-sm border-0">
                        <!-- Foto Kamar -->
                        <img src="{{ $kamar->foto_kamar ? asset($kamar->foto_kamar) : asset('img/kamar-atas.png') }}"
                            class="card-img-top" alt="Kamar {{ $kamar->no_kamar }}"
                            onerror="this.src='{{ asset('img/kamar-atas.png') }}'">

                        <div class="card-body card-kamar-body">
                            <!-- Nomor & Status -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. {{ $kamar->no_kamar }}</h5>
                                <span
                                    class="badge rounded-pill fw-medium
                                                {{ strtolower($kamar->status) == 'tersedia' ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }}">
                                    {{ ucfirst($kamar->status) }}
                                </span>
                            </div>

                            <!-- Lantai -->
                            <p class="card-text text-muted mb-1">
                                <i class="bi bi-layers me-1"></i>Lantai: {{ $kamar->lantai }}
                            </p>

                            <!-- Harga -->
                            <p class="card-text fw-bold fs-5 mb-0">
                                Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                            </p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="alert alert-info border-0 shadow-sm py-5" role="alert">
                <i class="bi bi-inbox d-block mb-3"></i>
                <h4 class="mb-3">Belum Ada Data Kamar</h4>
                <p class="text-muted.mb-4">
                    Mulai tambahkan kamar untuk mengelola properti kos Anda dengan lebih baik.
                </p>
                <a href="{{ route('pemilik.inputkamar') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Kamar Sekarang
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
@if(session('success'))
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('success-modal');
    const modalOkButton = document.getElementById('modal-ok-button');

    // Tampilkan modal
    setTimeout(() => modal.classList.add('show'), 100);

    // Tutup saat klik tombol
    modalOkButton.addEventListener('click', closeModal);

    // Tutup saat klik luar
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    function closeModal() {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }
});
@endif
</script>
@endsection