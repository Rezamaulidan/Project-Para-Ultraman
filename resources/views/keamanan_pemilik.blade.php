@extends('profil_pemilik')
@section('title', 'Laporan Keamanan - SIMK')

@section('content')
<style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"rel="stylesheet"><link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">body {
    background-color: #f0f2f5;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Header Navy */
.header-navy {
    background-color: #001931;
    color: white;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.report-card {
    background-color: #ffffff;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
    cursor: pointer;
}

.report-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    border-color: #001931;
}

.add-button {
    background: #001931;
    border: none;
    border-radius: 50px;
    padding: 14px 40px;
    font-size: 1rem;
    font-weight: 600;
    color: white;
    box-shadow: 0 4px 12px rgba(0, 25, 49, 0.3);
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.add-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 25, 49, 0.4);
    background-color: #033f7e;
    color: white;
}

.report-title {
    color: #001931;
    font-weight: 600;
}

.report-date {
    color: #666;
    font-size: 0.875rem;
}

.report-preview {
    color: #757575;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Tombol Kembali */
.back-button {
    color: white;
    text-decoration: none;
    transition: opacity 0.2s;
    display: flex;
    align-items: center;
}

.back-button:hover {
    opacity: 0.8;
    color: white;
}

/* Input Readonly Style di Modal */
.form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 1;
    border: 1px solid #ced4da;
}

@media (max-width: 576px) {
    .header-navy h1 {
        font-size: 1.25rem;
    }

    .report-card {
        margin-bottom: 0.75rem;
    }
}
</style>
</head>

<body>

    {{-- Main Content --}}
    <main class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">

                {{-- List Laporan --}}
                <div class="mb-4">

                    @forelse($laporans as $laporan)
                    {{-- Report Card Dynamic --}}
                    {{-- [PERBAIKAN] Menambahkan onclick untuk membuka modal --}}
                    <div class="report-card p-3 mb-3"
                        onclick="showDetailModal({{ json_encode($laporan) }}, '{{ $laporan->staf->nama_staf ?? 'Staf' }}')">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="report-title mb-0">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                {{ $laporan->judul_laporan }}
                            </h5>
                            <div class="text-end">
                                <span class="report-date d-block">
                                    {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y') }}
                                </span>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    Oleh: {{ $laporan->staf->nama_staf ?? 'Staf' }}
                                </small>
                            </div>
                        </div>

                        <p class="report-preview mb-0">
                            {{ Str::limit($laporan->keterangan, 100) }}
                        </p>
                    </div>
                    @empty
                    {{-- Empty State --}}
                    <div class="empty-state d-flex flex-column justify-content-center align-items-center"
                        style="min-height: 50vh;">

                        <i class="fa fa-folder-open text-secondary fa-4x mb-3"></i>
                        <h5 class="text-dark fw-bold mb-2">Belum Ada Laporan</h5>
                        <p class="text-muted small mb-4">Klik tombol "Tambah Laporan" untuk membuat laporan baru</p>
                    </div>
                    @endforelse

                </div>
            </div>
        </div>
    </main>

    {{-- [PERBAIKAN] Modal Detail Laporan --}}
    <div class="modal fade" id="modalDetailLaporan" tabindex="-1" aria-labelledby="modalDetailLaporanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLaporanLabel">Detail Laporan Keamanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        {{-- Kolom Kiri --}}
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Tanggal</label>
                                <input type="text" class="form-control" id="modalTanggal" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Nama Petugas</label>
                                <input type="text" class="form-control" id="modalPetugas" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Judul Laporan</label>
                                <input type="text" class="form-control" id="modalJudul" readonly>
                            </div>
                        </div>

                        {{-- Kolom Kanan --}}
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Keterangan Lengkap</label>
                                <textarea class="form-control" rows="10" id="modalKeterangan" readonly></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // [PERBAIKAN] Fungsi untuk menampilkan modal dengan data dinamis
    function showDetailModal(laporan, namaStaf) {
        // Set data ke dalam elemen modal
        document.getElementById('modalTanggal').value = new Date(laporan.tanggal).toLocaleDateString('id-ID');
        document.getElementById('modalPetugas').value = namaStaf;
        document.getElementById('modalJudul').value = laporan.judul_laporan;
        document.getElementById('modalKeterangan').value = laporan.keterangan;

        // Tampilkan modal menggunakan Bootstrap API
        var myModal = new bootstrap.Modal(document.getElementById('modalDetailLaporan'));
        myModal.show();
    }

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 2000
    });
    @endif
    </script>
</body>
@endsection