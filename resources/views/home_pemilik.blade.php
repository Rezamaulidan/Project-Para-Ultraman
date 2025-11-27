@extends('profil_pemilik') 
@section('title', 'Beranda - SIMK')

@section('content')
<style>
/* CSS GABUNGAN */
:root { --navy-primary: #001931; --navy-light: #002b52; }
.stat-card { border: none; border-radius: 15px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); transition: all 0.3s ease; position: relative; overflow: hidden; }
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,25,49,0.15); }
.cursor-pointer { cursor: pointer; }
.stat-card.active-selected { border: 2px solid var(--navy-primary); background-color: #eef6ff; }
.stat-icon { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; background-color: rgba(0,25,49,0.1); color: var(--navy-primary); }
.card-income { background: var(--navy-primary); color: white; }
.card-income .stat-icon { background-color: rgba(255,255,255,0.2); color: white; }
.card-income h6 { color: #d1d1d1; }
.card-header-custom { background-color: white; border-bottom: 2px solid #f0f0f0; padding: 20px; font-weight: bold; color: var(--navy-primary); }
.btn-navy { background-color: var(--navy-primary); color: white; border-radius: 8px; }
.btn-navy:hover { background-color: var(--navy-light); color: white; }
.dynamic-list { display: none; animation: fadeIn 0.4s ease-in-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.nav-tabs .nav-link.active { color: var(--navy-primary); border-color: transparent transparent var(--navy-primary) transparent; border-width: 3px; background-color: transparent; }
.nav-tabs .nav-link { color: #888; }
</style>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 style="color: #001931; font-weight: 700;">Hallo Bos <b>{{ $user->nama_pemilik ?? 'Pemilik' }}!</b></h2>
            <p class="text-muted">Selamat Datang di SIMK, Berikut adalah ringkasan manajemen kos Anda.</p>
        </div>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="row g-4 mb-5">
        {{-- Pendapatan --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card card-income h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase mb-1">Pendapatan Bulan Ini</h6>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                        <small class="text-success">Data Realtime</small>
                    </div>
                    <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                </div>
            </div>
        </div>

        {{-- Okupansi Kamar --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100 cursor-pointer" onclick="showList('kamar-kosong', this)">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1">Okupansi Kamar</h6>
                        <h3 class="mb-0 fw-bold" style="color: #001931">{{ $jumlahKamarTerisi ?? 0 }} / {{ $totalKamar }}</h3>
                        <small class="text-muted">{{ $jumlahKamarKosong }} Kamar Kosong</small>
                    </div>
                    <div class="stat-icon"><i class="fas fa-bed"></i></div>
                </div>
            </div>
        </div>

        {{-- Belum Lunas --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100 cursor-pointer" onclick="showList('belum-lunas', this)">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1">Belum Lunas</h6>
                        <h3 class="mb-0 fw-bold text-danger">{{ $jumlahBelumLunas }} Orang</h3>
                        <small class="text-danger">Total: Rp {{ number_format($totalUangBelumLunas, 0, ',', '.') }}</small>
                    </div>
                    <div class="stat-icon text-danger bg-danger-subtle"><i class="fas fa-exclamation-circle text-danger"></i></div>
                </div>
            </div>
        </div>

        {{-- Permintaan Sewa --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100 cursor-pointer active-selected" id="card-sewa" onclick="showList('permintaan-sewa', this)">
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
        {{-- KOLOM KIRI: CHART (DARI MASTER) --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <div class="nav nav-tabs border-0" id="chart-tabs" role="tablist">
                        <button class="nav-link active fw-bold" id="income-tab" data-bs-toggle="tab" data-bs-target="#income-chart-pane" type="button" role="tab"><i class="fas fa-chart-bar me-2"></i> Pendapatan</button>
                        <button class="nav-link fw-bold" id="expense-tab" data-bs-toggle="tab" data-bs-target="#expense-chart-pane" type="button" role="tab"><i class="fas fa-cash-register me-2"></i> Pengeluaran</button>
                    </div>
                    <select class="form-select form-select-sm w-auto border-0 bg-light"><option>Tahun {{ date('Y') }}</option></select>
                </div>
                <div class="card-body p-3">
                    <div class="tab-content" id="chart-tab-content">
                        <div class="tab-pane fade show active" id="income-chart-pane" role="tabpanel">
                            <canvas id="incomeChart" style="max-height: 320px;" data-labels="{{ json_encode($labelsChart) }}" data-data="{{ json_encode($dataChart) }}"></canvas>
                        </div>
                        <div class="tab-pane fade" id="expense-chart-pane" role="tabpanel">
                            <canvas id="expenseChart" style="max-height: 320px;" data-labels="{{ json_encode($labelsChart) }}" data-data="{{ json_encode($dataPengeluaranChart) }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: LIST DYNAMIC (DARI TAMPILAN-PENYEWA) --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <span id="list-title"><i class="fas fa-user-plus me-2"></i> Permintaan Sewa</span>
                    <span class="badge bg-primary rounded-pill" id="list-count">{{ $jumlahPermintaan }} Baru</span>
                </div>

                <div class="card-body p-0 overflow-auto" style="height: 350px;">
                    
                    {{-- LIST PERMINTAAN SEWA (PENDING) --}}
                    <div id="list-permintaan-sewa" class="dynamic-list d-block">
                        <div class="list-group list-group-flush">
                            @forelse($permintaanSewa as $sewa)
                                <div class="list-group-item p-3 border-bottom">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3"><i class="fas fa-user-tie text-primary"></i></div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $sewa->penyewa->nama_penyewa ?? $sewa->username }}</h6>
                                                <small class="text-muted">Ingin: Kamar No. {{ $sewa->no_kamar }}</small>
                                            </div>
                                        </div>
                                        <a href="{{ route('pemilik.permohonan') }}" class="btn btn-sm btn-navy rounded-pill px-3">Detail</a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-5"><i class="fas fa-check-circle text-muted fa-3x mb-3"></i><p class="text-muted mb-0">Tidak ada permintaan sewa baru.</p></div>
                            @endforelse
                        </div>
                    </div>

                    {{-- LIST BELUM LUNAS (CONFIRMED) --}}
                    <div id="list-belum-lunas" class="dynamic-list">
                        <div class="list-group list-group-flush">
                            @forelse($belumLunas as $hutang)
                                <div class="list-group-item p-3 border-bottom">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3"><i class="fas fa-clock text-warning"></i></div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $hutang->penyewa->nama_penyewa ?? $hutang->username }}</h6>
                                                <small class="text-danger fw-bold">Rp {{ number_format($hutang->nominal, 0, ',', '.') }}</small><br>
                                                <small class="text-muted">Kamar No. {{ $hutang->no_kamar }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column gap-2">
                                            <a href="https://wa.me/{{ $hutang->penyewa->no_hp ?? '' }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3"><i class="fab fa-whatsapp"></i> Tagih</a>
                                            <form action="{{ route('pemilik.booking.cancel', $hutang->id_booking) }}" method="POST" onsubmit="return confirm('Yakin batalkan booking ini?');">
                                                @csrf <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 w-100">Batal</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-5"><i class="fas fa-laugh-beam text-success fa-3x mb-3"></i><p class="text-muted mb-0">Semua tagihan lunas!</p></div>
                            @endforelse
                        </div>
                    </div>

                    {{-- LIST KAMAR KOSONG --}}
                    <div id="list-kamar-kosong" class="dynamic-list">
                        <div class="list-group list-group-flush">
                            @forelse($daftarKamarKosong as $kamar)
                                <div class="list-group-item p-3 border-bottom">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3"><i class="fas fa-door-open text-success"></i></div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">Kamar {{ $kamar->no_kamar }}</h6>
                                                <small class="text-success">Siap Huni</small>
                                            </div>
                                        </div>
                                        <a href="{{ route('pemilik.editkamar', $kamar->no_kamar) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Edit</a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-5"><i class="fas fa-house-user text-muted fa-3x mb-3"></i><p class="text-muted mb-0">Tidak ada kamar kosong.</p></div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function showList(type, element) {
    const lists = document.querySelectorAll('.dynamic-list');
    lists.forEach(l => { l.classList.remove('d-block'); l.style.display = 'none'; });
    const selected = document.getElementById('list-' + type);
    if(selected) selected.style.display = 'block';

    const title = document.getElementById('list-title');
    const count = document.getElementById('list-count');

    if(type === 'permintaan-sewa') {
        title.innerHTML = '<i class="fas fa-user-plus me-2"></i> Permintaan Sewa';
        count.innerText = '{{ $jumlahPermintaan }} Baru';
        count.className = 'badge bg-primary rounded-pill';
    } else if(type === 'belum-lunas') {
        title.innerHTML = '<i class="fas fa-file-invoice-dollar me-2"></i> Tagihan Belum Lunas';
        count.innerText = '{{ $jumlahBelumLunas }} Orang';
        count.className = 'badge bg-danger rounded-pill';
    } else if(type === 'kamar-kosong') {
        title.innerHTML = '<i class="fas fa-bed me-2"></i> Daftar Kamar Kosong';
        count.innerText = '{{ $jumlahKamarKosong }} Kamar';
        count.className = 'badge bg-success rounded-pill';
    }

    document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('active-selected'));
    if(element) element.classList.add('active-selected');
}

// Inisialisasi Chart dari Master Branch
function initIncomeChart() {
    const el = document.getElementById('incomeChart');
    if(!el) return;
    new Chart(el.getContext('2d'), {
        type: 'bar',
        data: { labels: JSON.parse(el.dataset.labels), datasets: [{ label: 'Pendapatan (Juta)', data: JSON.parse(el.dataset.data), backgroundColor: '#001931', borderRadius: 6 }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
}
function initExpenseChart() {
    const el = document.getElementById('expenseChart');
    if(!el) return;
    new Chart(el.getContext('2d'), {
        type: 'bar',
        data: { labels: JSON.parse(el.dataset.labels), datasets: [{ label: 'Pengeluaran (Juta)', data: JSON.parse(el.dataset.data), backgroundColor: '#dc3545', borderRadius: 6 }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initIncomeChart();
    let expInit = false;
    const expTab = document.getElementById('expense-tab');
    if(expTab) {
        expTab.addEventListener('shown.bs.tab', function() {
            if(!expInit) { initExpenseChart(); expInit = true; }
        });
    }
});
</script>
@endsection