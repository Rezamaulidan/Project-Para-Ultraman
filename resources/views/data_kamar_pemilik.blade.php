@extends('profil_pemilik')
@section('title', 'Informasi Data Kamar - SIMK')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style_kamar_pemilik.css') }}">
@endsection

@section('content')
    <div id="main-content">
        <div id="success-modal" style="display: none;" class="modal">
            <div class="modal-content">
                <div class="modal-body">
                    <i class="fas fa-paw success-icon"></i>

                    <h2>{{ session('success') ?? 'Data Berhasil Disimpan!' }} 🎉</h2>

                    <p>Yeeeaaayyy! Data kamar baru kamu sudah aman dan siap menampung penghuni~</p>
                </div>
                <button id="modal-ok-button" class="btn-simpan-v2">Lanjut</button>
            </div>
        </div>

        <div class="container my-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Informasi Kamar</h2>
                <button onclick=window.location.href='/inputkamar' class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Input Kamar Baru
                </button>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

                {{-- Card Kamar 1 (Contoh) --}}
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 1">
                        <div class="card-body card-kamar-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 1</h5>
                                <span
                                    class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 1</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>
                    </div>
                </div>

                {{-- Card Kamar 2 (Contoh) --}}
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 2">
                        <div class="card-body card-kamar-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 2</h5>
                                <span
                                    class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 1</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>
                    </div>
                </div>

                {{-- Card Kamar 3 (Contoh) --}}
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 3">
                        <div class="card-body card-kamar-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 3</h5>
                                <span
                                    class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 1</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>
                    </div>
                </div>

                {{-- Card Kamar 4 (Contoh) --}}
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 4">
                        <div class="card-body card-kamar-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 4</h5>
                                <span
                                    class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 2</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>
                    </div>
                </div>

                {{-- Card Kamar 11 (Contoh) --}}
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 11">
                        <div class="card-body card-kamar-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 11</h5>
                                <span
                                    class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 2</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>
                    </div>
                </div>

                {{-- Card Kamar 12 (Contoh) --}}
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 12">
                        <div class="card-body card-kamar-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 12</h5>
                                <span
                                    class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 2</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>
                    </div>
                </div>

                {{-- Card Kamar 13 (Contoh) --}}
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 13">
                        <div class="card-body card-kamar-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 13</h5>
                                <span
                                    class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 2</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>
                    </div>
                </div>

                {{-- Card Kamar 14 (Contoh) --}}
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 14">
                        <div class="card-body card-kamar-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 14</h5>
                                <span
                                    class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 2</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>
                    </div>
                </div>

            </div> {{-- End row --}}

        </div> {{-- End container --}}

    </div>
@endsection

@section('scripts')

    {{-- Ini script BARU untuk memicu modal --}}
    <script>
        @if (session('success'))

            // Ambil elemen modal dari HTML
            const modal = document.getElementById('success-modal');
            const modalOkButton = document.getElementById('modal-ok-button');

            // Tampilkan modal
            modal.style.display = 'flex';
            setTimeout(function() {
                modal.classList.add('show');
            }, 10);

            // Tambahkan listener untuk tombol "Lanjut"
            modalOkButton.addEventListener('click', function() {
                modal.classList.remove('show');

                // Beri jeda sedikit agar animasi penutupan terlihat
                setTimeout(function() {
                    modal.style.display = 'none';
                }, 300);
            });

        @endif
    </script>
@endsection
