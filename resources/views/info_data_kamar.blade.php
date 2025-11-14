@extends('profil_pemilik')
@section('title', 'Informasi Data Kamar - SIMK')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/style_info_kamar.css') }}">
@endsection

@section('content')

<div class="main-wrapper">
    <div class="detail-content">
        <div class="content-inner">

            <div class="card-photo-sidebar card-base">
                <div class="kamar-photo-wrapper">
                    {{-- Ganti '101' dengan data dinamis --}}
                    <h2><span style="color: gold;">⭐</span> Data Kamar No {{ $kamar->nomor_kamar }} <span
                            style="color: gold;">⭐</span>
                    </h2>
                    <h4>Foto Kamar</h4>
                    <div class="kamar-photo-frame">
                        <p style="color: var(--text-muted); font-style: italic;">[Area Foto Kamar]</p>
                    </div>
                </div>
                <a href="/editkamar/{{ $kamar->nomor_kamar }}" class="btn-edit">
                    <i class="fas fa-pencil-alt me-2"></i> Edit Data
                </a>
            </div>

            <div class="card-detail-main card-base">

                <!-- Di info_data_kamar.blade.php -->
                <div class="modular-card" id="card-status">
                    <span class="badge-status">Status: {{ $kamar->status }}! 🎉</span>
                    <p class="price-text">
                        Rp {{ number_format($kamar->harga, 0, ',', '.') }} <span class="price-suffix">/perbulan</span>
                    </p>
                </div>


                <div class="modular-card" id="card-fasilitas">
                    <h3 class="modular-card-title"><i class="fas fa-list-check"></i> Fasilitas Utama</h3>
                    <p class="fasilitas-content">
                        Full AC, Kasur Queen Size, Meja Belajar Aesthetic, WiFi 50 Mbps, Kamar Mandi Dalam, Jendela
                        Menghadap Taman.
                    </p>
                </div>

                <div class="modular-card" id="card-detail-info">
                    <h3 class="modular-card-title"><i class="fas fa-info-circle"></i> Informasi Umum Kamar</h3>

                    <ul class="info-list">

                        <li class="info-item">
                            <span class="item-icon"><i class="fas fa-hashtag"></i></span>
                            <span class="item-label">Kamar:</span>
                            <span class="item-value">101</span>
                        </li>

                        <li class="info-item">
                            <span class="item-icon"><i class="fas fa-layer-group"></i></span>
                            <span class="item-label">Lantai:</span>
                            <span class="item-value">1</span>
                        </li>

                        <li class="info-item">
                            <span class="item-icon"><i class="fas fa-bed"></i></span>
                            <span class="item-label">Tipe:</span>
                            <span class="item-value">Deluxe</span>
                        </li>

                        <li class="info-item">
                            <span class="item-icon"><i class="fas fa-ruler-combined"></i></span>
                            <span class="item-label">Ukuran:</span>
                            <span class="item-value">3.5 x 4 m</span>
                        </li>

                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection