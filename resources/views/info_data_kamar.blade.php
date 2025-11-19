@extends('profil_pemilik')
@section('title', 'Kamar No ' . $kamar->no_kamar . ' - SIMK')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style_info_kamar.css') }}">
@endsection

@section('content')
    <div class="main-wrapper">
        <div class="detail-content">
            <div class="content-inner">

                <!-- Sidebar Foto Kamar -->
                <div class="card-photo-sidebar card-base">
                    <div class="kamar-photo-wrapper">
                        <h2 class="text-center mb-3">
                            <i class="fas fa-star gold-star"></i>
                            Data Kamar No {{ $kamar->no_kamar }}
                            <i class="fas fa-star gold-star"></i>
                        </h2>
                        <h4 class="text-center mb-3">Foto Kamar</h4>
                        <div class="kamar-photo-frame">
                            @if ($kamar->foto_kamar && file_exists(public_path($kamar->foto_kamar)))
                                <img src="{{ asset($kamar->foto_kamar) }}" alt="Foto Kamar {{ $kamar->no_kamar }}"
                                    style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
                            @else
                                <p style="color: var(--text-muted); font-style: italic; text-align:center; padding:40px 0;">
                                    [Belum ada foto]
                                </p>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('pemilik.editkamar', ['no_kamar' => $kamar->no_kamar]) }}" class="btn-edit d-block text-center mt-3">
                        <i class="fas fa-pencil-alt me-2"></i> Edit Data
                    </a>
                </div>

                <!-- Detail Utama -->
                <div class="card-detail-main card-base">

                    <!-- Status & Harga -->
                    <div class="modular-card" id="card-status">
                        <div class="text-center">
                            <span class="badge-status d-block mb-2">
                                Status: {{ ucfirst($kamar->status) }}
                                @if (strtolower($kamar->status) == 'tersedia')
                                    <i class="fas fa-check-circle text-success ms-1"></i>
                                @endif
                            </span>
                            <p class="price-text fs-3 fw-bold text-primary mb-0">
                                Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                            </p>
                            <p class="text-muted small">/perbulan</p>
                        </div>
                    </div>

                    <!-- Fasilitas -->
                    <div class="modular-card" id="card-fasilitas">
                        <h3 class="modular-card-title">
                            <i class="fas fa-list-check"></i> Fasilitas Utama
                        </h3>
                        <p class="fasilitas-content mb-0">
                            {{ $kamar->fasilitas ?: 'Fasilitas belum diisi.' }}
                        </p>
                    </div>

                    <!-- Informasi Umum (TABEL RAPI) -->
                    <div class="modular-card" id="card-detail-info">
                        <h3 class="modular-card-title">
                            <i class="fas fa-info-circle"></i> Informasi Umum Kamar
                        </h3>

                        <table class="info-table">
                            <tr>
                                <td class="icon-col"><i class="fas fa-hashtag"></i></td>
                                <td class="label-col">Kamar</td>
                                <td class="value-col">{{ $kamar->no_kamar }}</td>
                            </tr>
                            <tr>
                                <td class="icon-col"><i class="fas fa-layer-group"></i></td>
                                <td class="label-col">Lantai</td>
                                <td class="value-col">{{ $kamar->lantai }}</td>
                            </tr>
                            <tr>
                                <td class="icon-col"><i class="fas fa-bed"></i></td>
                                <td class="label-col">Tipe</td>
                                <td class="value-col">{{ ucfirst($kamar->tipe_kamar) }}</td>
                            </tr>
                            <tr>
                                <td class="icon-col"><i class="fas fa-ruler-combined"></i></td>
                                <td class="label-col">Ukuran</td>
                                <td class="value-col">{{ $kamar->ukuran }}</td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
