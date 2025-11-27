@extends('profil_pemilik') {{-- Sesuaikan dengan layout utama Anda --}}
@section('title', 'Beranda - SIMK')

@section('content')
    {{-- 1. BAGIAN CSS CUSTOM --}}
    <style>
        :root {
            --navy-primary: #001931;
            --navy-light: #002b52;
            --gold-accent: #FFD700;
        }

        body {
            background-color: #f4f6f9;
        }

        /* Styling Card Statistik */
        .stat-card {
            border: none;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 25, 49, 0.15);
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .stat-card.active-selected {
            border: 2px solid var(--navy-primary);
            background-color: #eef6ff;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            background-color: rgba(0, 25, 49, 0.1);
            color: var(--navy-primary);
        }

        .card-income {
            background: var(--navy-primary);
            color: white;
        }

        .card-income .stat-icon {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .card-income h6 {
            color: #d1d1d1;
        }

        .card-header-custom {
            background-color: white;
            border-bottom: 2px solid #f0f0f0;
            padding: 20px;
            font-weight: bold;
            color: var(--navy-primary);
        }

        .btn-navy {
            background-color: var(--navy-primary);
            color: white;
            border-radius: 8px;
        }

        .btn-navy:hover {
            background-color: var(--navy-light);
            color: white;
        }

        .dynamic-list {
            display: none;
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="container-fluid py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <h2 style="color: #001931; font-weight: 700;">Dashboard Pemilik</h2>
                <p class="text-muted">Selamat datang kembali, <b>{{ $user->nama_pemilik ?? 'Pemilik' }}</b>! Berikut
                    ringkasan kos Anda.</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card card-income h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-1">Pendapatan Bulan Ini</h6>
                            {{-- Format Rupiah --}}
                            <h3 class="mb-0 fw-bold">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                            <small class="text-success">Data Realtime</small>
                        </div>
                        <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stat-card h-100 cursor-pointer" id="card-kamar" onclick="showList('kamar-kosong', this)">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Okupansi Kamar</h6>
                            {{-- Hitung Kamar Terisi: Total - Kosong --}}
                            <h3 class="mb-0 fw-bold" style="color: #001931">{{ $totalKamar - $jumlahKamarKosong }} /
                                {{ $totalKamar }}</h3>
                            <small class="text-muted">{{ $jumlahKamarKosong }} Kamar Kosong</small>
                        </div>
                        <div class="stat-icon"><i class="fas fa-bed"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stat-card h-100 cursor-pointer" id="card-lunas" onclick="showList('belum-lunas', this)">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Belum Lunas</h6>
                            <h3 class="mb-0 fw-bold text-danger">{{ $jumlahBelumLunas }} Orang</h3>
                            <small class="text-danger">Total: Rp
                                {{ number_format($totalUangBelumLunas, 0, ',', '.') }}</small>
                        </div>
                        <div class="stat-icon text-danger bg-danger-subtle"><i
                                class="fas fa-exclamation-circle text-danger"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stat-card h-100 cursor-pointer active-selected" id="card-sewa"
                    onclick="showList('permintaan-sewa', this)">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Permintaan Sewa</h6>
                            <h3 class="mb-0 fw-bold" style="color: #001931">{{ $jumlahPermintaan }} Baru</h3>
                            <small class="text-primary fw-bold">Klik untuk Detail</small>
                        </div>
                        <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-chart-line me-2"></i> Statistik Pendapatan (6 Bulan)</span>
                        <select class="form-select form-select-sm w-auto border-0 bg-light">
                            <option>Tahun {{ date('Y') }}</option>
                        </select>
                    </div>
                    <div class="card-body d-flex align-items-center">
                        <canvas id="incomeChart" style="max-height: 320px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <span id="list-title"><i class="fas fa-user-plus me-2"></i> Permintaan Sewa</span>
                        <span class="badge bg-primary rounded-pill" id="list-count">{{ $jumlahPermintaan }} Baru</span>
                    </div>

                    <div class="card-body p-0 overflow-auto" style="height: 350px;">

                        <div id="list-permintaan-sewa" class="dynamic-list d-block">
                            <div class="list-group list-group-flush">
                                {{-- Loop Data dari Controller --}}
                                @forelse($permintaanSewa as $sewa)
                                    <div class="list-group-item p-3 border-bottom">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-3">
                                                    <i class="fas fa-user-tie text-primary"></i>
                                                </div>
                                                <div>
                                                    {{-- Nama Penyewa --}}
                                                    <h6 class="mb-0 fw-bold">
                                                        {{ $sewa->penyewa->nama_penyewa ?? $sewa->username }}
                                                    </h6>
                                                    {{-- Info Kamar & Tanggal --}}
                                                    <small class="text-muted">
                                                        Ingin: Kamar No. {{ $sewa->no_kamar }} <br>
                                                        Tgl:
                                                        {{ \Carbon\Carbon::parse($sewa->created_at)->format('d M Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                            {{-- Tombol Detail --}}
                                            {{-- Pastikan route 'pemilik.permohonan' sudah dibuat di web.php --}}
                                            <a href="{{ route('pemilik.permohonan') }}"
                                                class="btn btn-sm btn-navy rounded-pill px-3">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    {{-- Tampilan Jika Kosong --}}
                                    <div class="text-center p-5">
                                        <i class="fas fa-check-circle text-muted fa-3x mb-3"></i>
                                        <p class="text-muted mb-0">Tidak ada permintaan sewa baru.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div id="list-belum-lunas" class="dynamic-list">
                            <div class="list-group list-group-flush">
                                @forelse($belumLunas as $hutang)
                                    <div class="list-group-item p-3 border-bottom">
                                        <div class="d-flex w-100 justify-content-between align-items-center">

                                            {{-- Info Penyewa --}}
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-3">
                                                    <i class="fas fa-clock text-warning"></i> {{-- Icon Jam --}}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">
                                                        {{ $hutang->penyewa->nama_penyewa ?? $hutang->username }}
                                                    </h6>
                                                    <small class="text-muted fw-bold">
                                                        Rp {{ number_format($hutang->nominal, 0, ',', '.') }}
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        Kamar {{ $hutang->no_kamar }} •
                                                        {{-- Tampilkan sudah berapa lama sejak di-approve --}}
                                                        {{ \Carbon\Carbon::parse($hutang->updated_at)->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>

                                            {{-- Tombol Aksi --}}
                                            <div class="d-flex flex-column gap-2">

                                                {{-- Tombol Hubungi WA (Sudah ada sebelumnya) --}}
                                                <a href="https://wa.me/{{ $hutang->penyewa->no_hp ?? '' }}" target="_blank"
                                                    class="btn btn-sm btn-success rounded-pill px-3">
                                                    <i class="fab fa-whatsapp"></i> Tagih
                                                </a>

                                                {{-- TOMBOL BATALKAN (BARU) --}}
                                                <form action="{{ route('pemilik.booking.cancel', $hutang->id_booking) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin batalkan booking ini? Status akan menjadi DITOLAK.');">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger rounded-pill px-3 w-100">
                                                        <i class="fas fa-ban me-1"></i> Batal
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center p-5">
                                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                        <p class="text-muted mb-0">Tidak ada tagihan pending.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div id="list-kamar-kosong" class="dynamic-list">
                            <div class="list-group list-group-flush">
                                @forelse($daftarKamarKosong as $kamar)
                                    <div class="list-group-item p-3 border-bottom">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-3">
                                                    <i class="fas fa-door-open text-success"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">Kamar {{ $kamar->no_kamar }} (Lantai
                                                        {{ $kamar->lantai }})</h6>
                                                    <small class="text-success">Siap Huni</small><br>
                                                    <small class="text-muted">Rp
                                                        {{ number_format($kamar->harga, 0, ',', '.') }} / bln</small>
                                                </div>
                                            </div>
                                            <a href="{{ route('pemilik.editkamar', $kamar->no_kamar) }}"
                                                class="btn btn-sm btn-outline-secondary rounded-pill px-3">Edit</a>
                                        </div>
                                    </div>
                                @empty
                                    {{-- JIKA KOSONG --}}
                                    <div class="text-center p-5">
                                        <i class="fas fa-house-user text-muted fa-3x mb-3"></i>
                                        <p class="text-muted mb-0">Tidak ada kamar kosong saat ini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-white text-center border-0 py-3">
                        <a href="#" class="text-decoration-none fw-bold small text-uppercase"
                            style="color: #001931; letter-spacing: 1px;">
                            Lihat Semua Data <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT (TETAP SAMA, LOGIKA DISPLAY) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function showList(type, element) {
            // 1. Hide all lists
            const lists = document.querySelectorAll('.dynamic-list');
            lists.forEach(list => {
                list.classList.remove('d-block');
                list.style.display = 'none';
            });

            // 2. Show selected list
            const selectedList = document.getElementById('list-' + type);
            if (selectedList) {
                selectedList.style.display = 'block';
            }

            // 3. Update Header Text based on Type
            const titleElement = document.getElementById('list-title');
            const countElement = document.getElementById('list-count');

            // Menggunakan PHP variables untuk default count di Javascript jika perlu,
            // tapi teks statis di sini cukup update Judul & Warna Badge saja.
            if (type === 'permintaan-sewa') {
                titleElement.innerHTML = '<i class="fas fa-user-plus me-2"></i> Permintaan Sewa';
                countElement.innerText = '{{ $jumlahPermintaan }} Baru';
                countElement.className = 'badge bg-primary rounded-pill';
            } else if (type === 'belum-lunas') {
                titleElement.innerHTML = '<i class="fas fa-file-invoice-dollar me-2"></i> Tagihan Belum Lunas';
                countElement.innerText = '{{ $jumlahBelumLunas }} Orang';
                countElement.className = 'badge bg-danger rounded-pill';
            } else if (type === 'kamar-kosong') {
                titleElement.innerHTML = '<i class="fas fa-bed me-2"></i> Daftar Kamar Kosong';
                countElement.innerText = '{{ $jumlahKamarKosong }} Kamar';
                countElement.className = 'badge bg-success rounded-pill';
            }

            // 4. Update Card Active State
            const allCards = document.querySelectorAll('.stat-card');
            allCards.forEach(card => {
                card.classList.remove('active-selected');
            });
            if (element) {
                element.classList.add('active-selected');
            }
        }

        // Chart.js Config (Sama seperti sebelumnya, bisa dibuat dinamis nanti jika perlu)
        const ctx = document.getElementById('incomeChart').getContext('2d');
        const incomeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Pendapatan (Juta)',
                    data: [8, 10, 9.5, 11, 12, 12.5], // Nanti ini bisa diganti data dari controller juga
                    backgroundColor: '#001931',
                    hoverBackgroundColor: '#002b52',
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [5, 5],
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    </script>
@endsection
