@extends('profil_pemilik')
@section('title', 'Daftar Permohonan Sewa')

@section('content')
<div class="container-fluid py-4">

    {{-- Header Halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Permohonan Sewa Masuk</h4>
            <p class="text-muted small">Daftar calon penyewa yang menunggu persetujuan Anda.</p>
        </div>
        <a href="{{ route('pemilik.home') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tabel Data --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="px-4 py-3">Tanggal Request</th>
                            <th class="py-3">Info Penyewa</th>
                            <th class="py-3">Kamar & Durasi</th>
                            <th class="py-3">Total Tagihan</th>
                            <th class="py-3">Identitas (KTP)</th>
                            <th class="py-3 text-center" style="min-width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            {{-- 1. Tanggal --}}
                            <td class="px-4 text-muted">
                                {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }} <br>
                                <small>{{ \Carbon\Carbon::parse($booking->created_at)->format('H:i') }} WIB</small>
                            </td>

                            {{-- 2. Info Penyewa --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle text-primary rounded-circle p-2 me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $booking->penyewa->nama_penyewa ?? $booking->username }}</div>
                                        <div class="small text-muted">
                                            <i class="fab fa-whatsapp me-1 text-success"></i>
                                            {{ $booking->penyewa->no_hp ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. Info Kamar --}}
                            <td>
                                <span class="badge bg-info text-dark mb-1">Kamar No. {{ $booking->no_kamar }}</span>
                                <div class="small text-muted">Durasi: {{ $booking->durasi_sewa }} Bulan</div>
                                <div class="small text-muted">Mulai: {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}</div>
                            </td>

                            {{-- 4. Total Tagihan --}}
                            <td>
                                <h6 class="fw-bold text-primary mb-0">
                                    Rp {{ number_format($booking->nominal, 0, ',', '.') }}
                                </h6>
                            </td>

                            {{-- 5. KTP (Modal Trigger) --}}
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-dark rounded-pill"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalKtp{{ $booking->id_booking }}">
                                    <i class="fas fa-id-card me-1"></i> Lihat KTP
                                </button>

                                {{-- MODAL KTP --}}
                                <div class="modal fade" id="modalKtp{{ $booking->id_booking }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Identitas Penyewa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center bg-light">
                                                @if($booking->penyewa->foto_ktp)
                                                    <img src="{{ asset('storage/'.$booking->penyewa->foto_ktp) }}"
                                                         class="img-fluid rounded shadow-sm"
                                                         alt="Foto KTP">
                                                @else
                                                    <p class="text-muted">Penyewa belum upload KTP</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 6. Tombol Aksi --}}
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Tombol Tolak --}}
                                    <form action="{{ route('pemilik.booking.reject', $booking->id_booking) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK permohonan ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm rounded-3" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>

                                    {{-- Tombol Terima --}}
                                    <form action="{{ route('pemilik.booking.approve', $booking->id_booking) }}" method="POST"
                                          onsubmit="return confirm('Terima booking ini? Status akan berubah menjadi CONFIRMED.');">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm rounded-3 px-3 fw-bold" title="Terima">
                                            <i class="fas fa-check me-1"></i> Terima
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted opacity-50">
                                    <i class="fas fa-clipboard-list fa-4x mb-3"></i>
                                    <h5>Tidak ada permohonan baru</h5>
                                    <p class="small">Semua permohonan booking akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
