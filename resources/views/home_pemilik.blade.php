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

/* Style tambahan untuk tab chart */
.nav-tabs .nav-link.active {
    color: var(--navy-primary);
    border-color: transparent transparent var(--navy-primary) transparent;
    border-width: 3px;
    background-color: transparent;
}

.nav-tabs .nav-link {
    color: #888;
}
</style>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 style="color: #001931; font-weight: 700;">Hallo Bos <b>{{ $user->nama_pemilik ?? 'pemilik' }}!</b>
            </h2>
            <p class="text-muted">Selamat Datang di SMIK, Berikut adalah ringkasan manajemen kos Anda.</p>
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
                    <div class="nav nav-tabs border-0" id="chart-tabs" role="tablist">
                        <button class="nav-link active fw-bold" id="income-tab" data-bs-toggle="tab"
                            data-bs-target="#income-chart-pane" type="button" role="tab"
                            aria-controls="income-chart-pane" aria-selected="true">
                            <i class="fas fa-chart-bar me-2"></i> Pendapatan
                        </button>
                        <button class="nav-link fw-bold" id="expense-tab" data-bs-toggle="tab"
                            data-bs-target="#expense-chart-pane" type="button" role="tab"
                            aria-controls="expense-chart-pane" aria-selected="false">
                            <i class="fas fa-cash-register me-2"></i> Pengeluaran
                        </button>
                    </div>
                    <select class="form-select form-select-sm w-auto border-0 bg-light">
                        <option>Tahun {{ date('Y') }}</option>
                    </select>
                </div>

                <div class="card-body p-3">
                    <div class="tab-content" id="chart-tab-content">
                        {{-- SLIDE PERTAMA: PENDAPATAN --}}
                        <div class="tab-pane fade show active" id="income-chart-pane" role="tabpanel"
                            aria-labelledby="income-tab">
                            <h6 class="text-muted text-center mb-3">Statistik Pendapatan 6 Bulan Terakhir (kelipatan
                                2jt)</h6>
                            <canvas id="incomeChart" style="max-height: 320px;"
                                data-labels="{{ json_encode($labelsChart) }}"
                                data-data="{{ json_encode($dataChart) }}"></canvas>
                        </div>

                        {{-- SLIDE KEDUA: PENGELUARAN --}}
                        <div class="tab-pane fade" id="expense-chart-pane" role="tabpanel"
                            aria-labelledby="expense-tab">
                            <h6 class="text-muted text-center mb-3">Statistik Pengeluaran 6 Bulan Terakhir (kelipatan
                                500rb)
                            </h6>
                            <canvas id="expenseChart" style="max-height: 320px;"
                                data-labels="{{ json_encode($labelsChart) }}"
                                data-data="{{ json_encode($dataPengeluaranChart) }}"></canvas>
                        </div>
                    </div>
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
                            @forelse($permintaanSewa as $sewa)
                            <div class="list-group-item p-3 border-bottom">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-2 me-3">
                                            <i class="fas fa-user-tie text-primary"></i>
                                        </div>
                                        <div>
                                            {{-- Cek apakah data penyewa ada, jika tidak tampilkan username/Guest --}}
                                            <h6 class="mb-0 fw-bold">
                                                {{ $sewa->penyewa->nama_penyewa ?? $sewa->username }}</h6>
                                            <small class="text-muted">Ingin: Kamar No.
                                                {{ $sewa->no_kamar }}</small>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-navy rounded-pill px-3">Detail</button>
                                </div>
                            </div>
                            @empty
                            {{-- JIKA KOSONG --}}
                            <div class="text-center p-5">
                                <i class="fas fa-check-circle text-muted fa-3x mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada permintaan sewa yang belum dikonfirmasi.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <div id="list-belum-lunas" class="dynamic-list">
                        <div class="list-group list-group-flush">
                            @forelse($belumLunas as $hutang) {{-- <-- INI HARUS $belumLunas --}}
                            <div class="list-group-item p-3 border-bottom">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{-- ... --}}
                                        <div>
                                            {{-- Nama Penyewa: Menggunakan relasi 'penyewa' --}}
                                            <h6 class="mb-0 fw-bold">
                                                {{ $hutang->penyewa->nama_penyewa ?? $hutang->username }}</h6>
                                            {{-- Nominal --}}
                                            <small class="text-danger fw-bold">Rp
                                                {{ number_format($hutang->nominal, 0, ',', '.') }}</small><br>
                                            {{-- No Kamar --}}
                                            <small class="text-muted">Kamar No. {{ $hutang->no_kamar }}</small>
                                        </div>
                                    </div>
                                    <a href="https://wa.me/{{(isset($hutang->penyewa->no_hp) && substr($hutang->penyewa->no_hp, 0, 1) === '0') 
                                        ? '62' . substr($hutang->penyewa->no_hp, 1):($hutang->penyewa->no_hp ?? '') 
                                        }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        WA
                                    </a>
                                </div>
                            </div>
                            @empty
                            {{-- JIKA KOSONG --}}
                            <div class="text-center p-5">
                                <i class="fas fa-laugh-beam text-success fa-3x mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada penyewa yang telat bayar. Semua lunas!</p>
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
PemilikKost

                </div>
                <div class="card-footer bg-white text-center border-0 py-3">
                    <!-- <a href="#" class="text-decoration-none fw-bold small text-uppercase"
                        style="color: #001931; letter-spacing: 1px;">
                        Lihat Semua Data <i class="fas fa-arrow-right ms-1"></i>
                    </a> -->
master
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- Pastikan Anda juga menyertakan Bootstrap JS yang mendukung fitur Tabs/Nav --}}
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

// FUNGSI INISIALISASI CHART

// 1. Chart Pendapatan (Kelipatan 2 Juta Rp)
function initIncomeChart() {
    const chartElement = document.getElementById('incomeChart');
    if (!chartElement) return;

    const labels = JSON.parse(chartElement.dataset.labels);
    const dataValues = JSON.parse(chartElement.dataset.data);
    const ctx = chartElement.getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Juta)',
                data: dataValues,
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
                    max: 20, // Batas atas (20 Juta). Sesuaikan jika data Anda lebih tinggi.
                    ticks: {
                        stepSize: 2, // Kelipatan 2 Juta Rupiah
                        callback: function(value) {
                            // Hanya tampilkan label untuk kelipatan 2
                            return value % 2 === 0 ? value : null;
                        }
                    },
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
}

// 2. Chart Pengeluaran (Kelipatan 500 Ribu Rp)
function initExpenseChart() {
    const chartElement = document.getElementById('expenseChart');
    if (!chartElement) return;

    const labels = JSON.parse(chartElement.dataset.labels);
    const dataValues = JSON.parse(chartElement.dataset.data);
    const ctx = chartElement.getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pengeluaran (Juta)',
                data: dataValues,
                backgroundColor: '#dc3545', // Merah untuk Pengeluaran
                hoverBackgroundColor: '#c82333',
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
                    max: 5, // Batas atas (5 Juta). Sesuaikan jika pengeluaran Anda lebih tinggi.
                    ticks: {
                        stepSize: 0.5, // Kelipatan 500 Ribu Rupiah
                        callback: function(value) {
                            // Pastikan label ditampilkan dengan format yang benar (0.5, 1.0, 1.5, dst.)
                            return value.toFixed(1);
                        }
                    },
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
}


// Panggil fungsi inisialisasi ketika DOM dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Chart Pendapatan (yang pertama ditampilkan)
    initIncomeChart();

    // Logika untuk menginisialisasi Chart Pengeluaran hanya ketika tabnya diklik
    let isExpenseChartInitialized = false;

    const expenseTab = document.getElementById('expense-tab');
    if (expenseTab) {
        // event listener untuk Bootstrap tab shown (memastikan tab aktif)
        expenseTab.addEventListener('shown.bs.tab', function(event) {
            if (!isExpenseChartInitialized) {
                initExpenseChart();
                isExpenseChartInitialized = true;
            }
        });
    }
});
</script>
@endsection