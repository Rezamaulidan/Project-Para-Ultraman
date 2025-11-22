<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Keamanan - SIMK</title>

    {{-- Link untuk memuat file CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Link Font Awesome (agar ikon berfungsi) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* (Semua CSS Anda dari file sebelumnya) */
        body {
            background-color: #f0f2f5;
        }

        .header-penyewa {
            background-color: #001931;
            color: white;
            padding: 1rem;
        }

        /* Animasi Sliding Indicator untuk Tab */
        .nav-tabs {
            position: relative;
            border-bottom: 0;
        }

        .nav-tabs::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: var(--indicator-left, 0);
            width: var(--indicator-width, 0);
            height: 3px;
            background-color: #0089FF;
            transition: all 0.3s ease;
            border-radius: 3px 3px 0 0;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            transition: color 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            color: #000;
            font-weight: 600;
            background-color: white;
            border-bottom-color: white;
        }

        /* Style untuk input readonly (dari file Anda sebelumnya) */
        .form-control[readonly] {
            background-color: #f8f9fa;
            opacity: 1;
            border: 1px solid #ced4da;
        }

        /* Style agar item list bisa diklik */
        .list-group-item-action {
            cursor: pointer;
        }

        /* Style for empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #9e9e9e;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>

    {{-- 1. Memanggil navbar utama penyewa --}}
    @include('partials.navbar_menu_penyewa')

    {{-- 2. Header Halaman (Biru) --}}
    <div class="header-penyewa shadow-sm">
        <div class="container-fluid d-flex align-items-center">
            <a href="" class="text-white me-3">
            </a>
        </div>
    </div>

    {{-- 3. Tab Navigasi (Abu-abu) --}}
    <div class="container-fluid bg-light pt-2">
        <ul class="nav nav-tabs nav-fill">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.informasi') }}">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    Informasi Penyewa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('penyewa.keamanan') }}">
                    <i class="fa-solid fa-shield-halved me-1"></i>
                    Informasi Keamanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.pembayaran') }}">
                    <i class="fa-solid fa-money-check-dollar me-1"></i>
                    Menu Pembayaran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.kamar') }}">
                    <i class="fa-solid fa-box me-1"></i>
                    Informasi Kamar
                </a>
            </li>
        </ul>
    </div>

    {{-- ===== 4. KONTEN UTAMA (DAFTAR LAPORAN) ===== --}}
    <div class="container-fluid p-3 p-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="mb-0">Daftar Laporan Keamanan</h5>
                <p class="small text-muted">Berikut adalah riwayat laporan keamanan yang dicatat oleh
                    staf.</p>
            </div>
            <div class="card-body p-4 pt-2">

                <div class="list-group">
                    @forelse($laporans as $laporan)
                        {{-- Laporan Item --}}
                        <a class="list-group-item list-group-item-action"
                           onclick="showDetailModal({{ json_encode($laporan) }}, '{{ $laporan->staf->nama_staf ?? 'Staf' }}')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $laporan->judul_laporan }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y') }}</small>
                            </div>
                            <p class="mb-1 small text-muted text-truncate" style="max-width: 90%;">
                                {{ Str::limit($laporan->keterangan, 80) }}
                            </p>
                        </a>
                    @empty
                        {{-- Empty State --}}
                        <div class="empty-state">
                            <i class="fa-regular fa-folder-open"></i>
                            <p>Belum ada laporan keamanan saat ini.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div> {{-- End container-fluid --}}


    {{-- ===== 5. MODAL DETAIL LAPORAN ===== --}}
    <div class="modal fade" id="modalDetailLaporan" tabindex="-1" aria-labelledby="modalDetailLaporanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLaporanLabel">Detail Laporan Keamanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    {{-- Konten modal dinamis --}}
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


    {{-- Link untuk memuat JavaScript Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JavaScript untuk Sliding Indicator & Modal Logic --}}
    <script>
        // Fungsi untuk menampilkan modal dengan data dinamis
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

        // Fungsi untuk mengatur posisi sliding indicator
        function setActiveIndicator() {
            const activeTab = document.querySelector('.nav-tabs .nav-link.active');
            const navTabs = document.querySelector('.nav-tabs');

            if (activeTab && navTabs) {
                const tabRect = activeTab.getBoundingClientRect();
                const navRect = navTabs.getBoundingClientRect();

                // Hitung posisi relatif terhadap nav-tabs
                const left = tabRect.left - navRect.left;
                const width = tabRect.width;

                // Set properti CSS untuk indicator
                navTabs.style.setProperty('--indicator-left', `${left}px`);
                navTabs.style.setProperty('--indicator-width', `${width}px`);
            }
        }

        // Jalankan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            setActiveIndicator();

            // Update indicator saat window diresize
            window.addEventListener('resize', setActiveIndicator);

            // Tambahkan hover effect
            const navLinks = document.querySelectorAll('.nav-tabs .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    const linkRect = this.getBoundingClientRect();
                    const navRect = this.closest('.nav-tabs').getBoundingClientRect();
                    const left = linkRect.left - navRect.left;
                    const width = linkRect.width;

                    const navTabs = this.closest('.nav-tabs');
                    navTabs.style.setProperty('--indicator-left', `${left}px`);
                    navTabs.style.setProperty('--indicator-width', `${width}px`);
                });
            });

            // Kembalikan ke posisi active saat mouse leave
            document.querySelector('.nav-tabs').addEventListener('mouseleave', function() {
                setActiveIndicator();
            });
        });
    </script>
</body>

</html>
