<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Pembayaran - SIMK</title>

    {{-- Link CSS Bootstrap & Font Awesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .header-penyewa {
            background-color: #001931;
            color: white;
            padding: 1rem;
        }

        /* Animasi Tab */
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
            transition: color 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            color: #0089FF;
            font-weight: 700;
            background-color: white;
            border-bottom-color: white;
        }

        /* Card & Text */
        .card-status {
            border-left: 4px solid #0089FF;
        }

        .text-nominal {
            color: #dc3545;
            font-size: 1.25rem;
            font-weight: 600;
        }

        /* Metode Pembayaran */
        .payment-method,
        .payment-method-perpanjang {
            cursor: pointer;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .payment-method:hover,
        .payment-method-perpanjang:hover {
            border-color: #0089FF;
            background-color: #f8f9fa;
        }

        .payment-method.active,
        .payment-method-perpanjang.active {
            border-color: #0089FF;
            background-color: #e7f3ff;
        }

        /* Tombol */
        .btn-bayar {
            background-color: #0089FF;
            color: white;
            padding: 0.75rem 3rem;
            border-radius: 2rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-bayar:hover {
            background-color: #0070dd;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 137, 255, 0.3);
        }

        .rincian-row {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .rincian-row:last-child {
            border-bottom: none;
            font-weight: 600;
        }
    </style>
</head>

<body>

    @include('partials.navbar_menu_penyewa')

    <div class="header-penyewa shadow-sm">
        <div class="container-fluid"></div>
    </div>

    {{-- Tab Navigasi --}}
    <div class="container-fluid bg-light pt-2 shadow-sm">
        <ul class="nav nav-tabs nav-fill justify-content-center">
            <li class="nav-item"><a class="nav-link" href="{{ route('penyewa.informasi') }}"><i
                        class="fa-solid fa-circle-info me-2"></i>Informasi Penyewa</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('penyewa.keamanan') }}"><i
                        class="fa-solid fa-shield-halved me-2"></i>Informasi Keamanan</a></li>
            <li class="nav-item"><a class="nav-link active" href="#"><i
                        class="fa-solid fa-money-check-dollar me-2"></i>Menu Pembayaran</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('penyewa.kamar') }}"><i
                        class="fa-solid fa-box me-2"></i>Informasi Kamar</a></li>
        </ul>
    </div>

    <div class="container-fluid p-3 p-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">

                {{-- Card Status Pembayaran --}}
                <div class="card border-0 shadow-sm mb-4 card-status">
                    <div class="card-body p-4">
                        <h6 class="text-muted mb-3 text-uppercase small fw-bold">Status Sewa Saat Ini</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-muted d-block">Nama:</small>
                                <p class="mb-0 fw-bold text-dark">{{ $penyewa->nama_penyewa ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-muted d-block">Nomor Kamar:</small>
                                <p class="mb-0 fw-bold text-dark">{{ $booking->no_kamar ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Biaya Sewa / Bulan</small>
                                <p class="mb-0 text-nominal">Rp {{ number_format($booking->kamar->harga, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">

                        <ul class="nav nav-pills mb-4" id="paymentTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="bayar-tab" data-bs-toggle="pill"
                                    data-bs-target="#bayar-content" type="button" role="tab">Bayar Tagihan</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link ms-2" id="perpanjang-tab" data-bs-toggle="pill"
                                    data-bs-target="#perpanjang-content" type="button" role="tab">Perpanjang
                                    Sewa</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="paymentTabContent">

                            {{-- ================================================= --}}
                            {{-- TAB 1: BAYAR TAGIHAN (DENGAN LOGIKA WAKTU)       --}}
                            {{-- ================================================= --}}
                            <div class="tab-pane fade show active" id="bayar-content" role="tabpanel">

                                @php
                                    // 1. Hitung Tanggal Jatuh Tempo
                                    $jatuhTempo = \Carbon\Carbon::parse($booking->tanggal)->addMonths(
                                        $booking->durasi_sewa,
                                    );

                                    // 2. Hitung selisih hari dari SEKARANG ke JATUH TEMPO
                                    // diffInDays(..., false) -> false agar jika lewat tanggal jadi negatif
                                    $hariMenujuJatuhTempo = \Carbon\Carbon::now()->diffInDays($jatuhTempo, false);

                                    // 3. Tentukan apakah tombol bayar muncul
                                    // Muncul jika: Sisa hari <= 15 ATAU Statusnya sudah 'terlambat'
                                    $showPayButton =
                                        $hariMenujuJatuhTempo <= 15 || $booking->status_booking == 'terlambat';
                                @endphp

                                @if ($showPayButton)
                                    {{-- === JIKA SUDAH SAATNYA BAYAR (H-15 atau Telat) === --}}

                                    @if ($booking->status_booking == 'terlambat')
                                        <div class="alert alert-danger d-flex align-items-center mb-4">
                                            <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                                            <div>
                                                <strong>Tagihan Terlambat!</strong><br>
                                                Masa sewa Anda telah berakhir pada {{ $jatuhTempo->format('d M Y') }}.
                                                Mohon segera lakukan pembayaran.
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning d-flex align-items-center mb-4">
                                            <i class="fas fa-clock fa-2x me-3"></i>
                                            <div>
                                                <strong>Tagihan Segera Jatuh Tempo</strong><br>
                                                Jatuh tempo pada {{ $jatuhTempo->format('d M Y') }}
                                                ({{ ceil($hariMenujuJatuhTempo) }} hari lagi).
                                            </div>
                                        </div>
                                    @endif

                                    <form action="{{ route('penyewa.bayar.tagihan', $booking->id_booking) }}"
                                        method="POST" id="formBayarTagihan">
                                        @csrf
                                        <div class="mb-4">
                                            <h6 class="mb-3 fw-bold text-secondary">Rincian Pembayaran</h6>
                                            <div class="bg-light p-3 rounded border">
                                                <div class="rincian-row d-flex justify-content-between">
                                                    <span>Tagihan Bulan Ini:</span>
                                                    <span>Rp
                                                        {{ number_format($booking->kamar->harga, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="rincian-row d-flex justify-content-between mt-2">
                                                    <span class="fw-bold">Total Pembayaran:</span>
                                                    <span class="text-primary fw-bold fs-5">Rp
                                                        {{ number_format($booking->kamar->harga, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <h6 class="mb-3 fw-bold text-secondary">Pilih Metode Pembayaran</h6>

                                            {{-- Transfer Bank --}}
                                            <div class="payment-method rounded p-3 mb-3"
                                                onclick="selectPayment('transfer')">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="paymentMethod"
                                                        id="transferBank" value="transfer">
                                                    <label class="form-check-label w-100" for="transferBank">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div><strong>Transfer Bank</strong><small
                                                                    class="d-block text-muted">BCA, Mandiri, BNI,
                                                                    BRI</small></div>
                                                            <i
                                                                class="fa-solid fa-building-columns fa-2x text-secondary"></i>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                            {{-- E-Wallet --}}
                                            <div class="payment-method rounded p-3 mb-3"
                                                onclick="selectPayment('ewallet')">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="paymentMethod" id="eWallet" value="ewallet">
                                                    <label class="form-check-label w-100" for="eWallet">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div><strong>E-Wallet</strong><small
                                                                    class="d-block text-muted">GoPay, OVO, DANA</small>
                                                            </div>
                                                            <i class="fa-solid fa-wallet fa-2x text-secondary"></i>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="button" class="btn btn-bayar shadow-sm"
                                                onclick="prosesPayment()">Bayar Tagihan</button>
                                        </div>
                                    </form>
                                @else
                                    {{-- === JIKA BELUM SAATNYA BAYAR (Masih > 15 Hari) === --}}
                                    <div class="text-center py-5">
                                        <div class="mb-4">
                                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                                style="width: 100px; height: 100px;">
                                                <i class="fas fa-check-circle fa-4x text-success"></i>
                                            </div>
                                        </div>
                                        <h4 class="fw-bold text-dark">Belum Ada Tagihan</h4>
                                        <p class="text-muted">
                                            Tenang, masa sewa Anda masih aman.<br>
                                            Tagihan berikutnya akan muncul pada tanggal:
                                        </p>
                                        {{-- Tampilkan tanggal H-15 --}}
                                        <div class="badge bg-light text-dark border p-3 fs-5 mt-2 rounded-pill">
                                            <i class="far fa-calendar-alt me-2"></i>
                                            {{ $jatuhTempo->subDays(15)->format('d F Y') }}
                                        </div>
                                        <p class="small text-muted mt-3">
                                            (Jatuh Tempo: {{ $jatuhTempo->addDays(15)->format('d M Y') }})
                                        </p>
                                    </div>
                                @endif

                            </div>

                            {{-- ================================================= --}}
                            {{-- TAB 2: PERPANJANG SEWA (Selalu Muncul)           --}}
                            {{-- ================================================= --}}
                            <div class="tab-pane fade" id="perpanjang-content" role="tabpanel">

                                <form action="{{ route('penyewa.bayar.perpanjang') }}" method="POST"
                                    id="formPerpanjang">
                                    @csrf
                                    <input type="hidden" name="no_kamar" value="{{ $booking->no_kamar }}">

                                    <div class="alert alert-info small mb-4">
                                        <i class="fas fa-info-circle me-1"></i> Perpanjangan akan menambah durasi sewa
                                        mulai dari tanggal berakhirnya sewa Anda saat ini.
                                    </div>

                                    <div class="mb-4">
                                        <h6 class="mb-3 fw-bold text-secondary">Pilih Durasi Perpanjangan</h6>
                                        <select class="form-select form-select-lg border-secondary"
                                            id="durasiPembayaran" name="durasi" onchange="updateTotalPerpanjang()">
                                            <option value="1">1 Bulan</option>
                                            <option value="2" selected>2 Bulan</option>
                                            <option value="3">3 Bulan</option>
                                            <option value="6">6 Bulan</option>
                                            <option value="12">12 Bulan</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <h6 class="mb-3 fw-bold text-secondary">Rincian Pembayaran</h6>
                                        <div class="bg-light p-3 rounded border">
                                            <div class="rincian-row d-flex justify-content-between">
                                                <span>Biaya Sewa per Bulan:</span>
                                                <span id="biaya-per-bulan">Rp
                                                    {{ number_format($booking->kamar->harga, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="rincian-row d-flex justify-content-between">
                                                <span>Durasi:</span>
                                                <span id="durasi-text">2 Bulan</span>
                                            </div>
                                            <div class="rincian-row d-flex justify-content-between mt-2">
                                                <span class="fw-bold">Total Pembayaran:</span>
                                                <span class="text-primary fw-bold fs-5" id="total-perpanjang">Rp
                                                    0</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Metode Pembayaran Perpanjang (Sama) --}}
                                    <div class="mb-4">
                                        <h6 class="mb-3 fw-bold text-secondary">Pilih Metode Pembayaran</h6>

                                        <div class="payment-method-perpanjang rounded p-3 mb-3"
                                            onclick="selectPaymentPerpanjang('transfer')">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="paymentMethodPerpanjang" id="transferBankPerpanjang"
                                                    value="transfer">
                                                <label class="form-check-label w-100" for="transferBankPerpanjang">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div><strong>Transfer Bank</strong><small
                                                                class="d-block text-muted">BCA, Mandiri, BNI,
                                                                BRI</small></div>
                                                        <i
                                                            class="fa-solid fa-building-columns fa-2x text-secondary"></i>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="payment-method-perpanjang rounded p-3 mb-3"
                                            onclick="selectPaymentPerpanjang('ewallet')">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="paymentMethodPerpanjang" id="eWalletPerpanjang"
                                                    value="ewallet">
                                                <label class="form-check-label w-100" for="eWalletPerpanjang">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div><strong>E-Wallet</strong><small
                                                                class="d-block text-muted">GoPay, OVO, DANA</small>
                                                        </div>
                                                        <i class="fa-solid fa-wallet fa-2x text-secondary"></i>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="button" class="btn btn-bayar shadow-sm"
                                            onclick="prosesPaymentPerpanjang()">Bayar Perpanjangan</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function setActiveIndicator() {
            const activeTab = document.querySelector('.nav-tabs .nav-link.active');
            const navTabs = document.querySelector('.nav-tabs');
            if (activeTab && navTabs) {
                const tabRect = activeTab.getBoundingClientRect();
                const navRect = navTabs.getBoundingClientRect();
                navTabs.style.setProperty('--indicator-left', `${tabRect.left - navRect.left}px`);
                navTabs.style.setProperty('--indicator-width', `${tabRect.width}px`);
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            setActiveIndicator();
            window.addEventListener('resize', setActiveIndicator);
            document.querySelectorAll('.nav-tabs .nav-link').forEach(link => {
                link.addEventListener('mouseenter', function() {
                    const linkRect = this.getBoundingClientRect();
                    const navRect = this.closest('.nav-tabs').getBoundingClientRect();
                    const navTabs = this.closest('.nav-tabs');
                    navTabs.style.setProperty('--indicator-left',
                        `${linkRect.left - navRect.left}px`);
                    navTabs.style.setProperty('--indicator-width', `${linkRect.width}px`);
                });
            });
            document.querySelector('.nav-tabs').addEventListener('mouseleave', setActiveIndicator);
            updateTotalPerpanjang();
        });

        function selectPayment(method) {
            document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('active'));
            event.currentTarget.classList.add('active');
            event.currentTarget.querySelector('input[type="radio"]').checked = true;
        }

        function selectPaymentPerpanjang(method) {
            document.querySelectorAll('.payment-method-perpanjang').forEach(el => el.classList.remove('active'));
            event.currentTarget.classList.add('active');
            event.currentTarget.querySelector('input[type="radio"]').checked = true;
        }

        // 🛑 FUNGSI SUBMIT FORM TAGIHAN
        function prosesPayment() {
            const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked');
            if (!selectedMethod) {
                alert('Silakan pilih metode pembayaran terlebih dahulu!');
                return;
            }
            if (confirm('Lakukan pembayaran tagihan ini sekarang?')) {
                document.getElementById('formBayarTagihan').submit();
            }
        }

        // 🛑 FUNGSI SUBMIT FORM PERPANJANG
        function prosesPaymentPerpanjang() {
            const selectedMethod = document.querySelector('input[name="paymentMethodPerpanjang"]:checked');
            if (!selectedMethod) {
                alert('Silakan pilih metode pembayaran terlebih dahulu!');
                return;
            }
            const durasi = document.getElementById('durasiPembayaran').value;
            if (confirm(`Konfirmasi perpanjangan sewa selama ${durasi} bulan?`)) {
                document.getElementById('formPerpanjang').submit();
            }
        }

        function updateTotalPerpanjang() {
            const durasi = parseInt(document.getElementById('durasiPembayaran').value);
            const biayaPerBulan = {{ $booking->kamar->harga }};
            const total = durasi * biayaPerBulan;
            document.getElementById('durasi-text').textContent = `${durasi} Bulan`;
            document.getElementById('total-perpanjang').textContent = `Rp ${total.toLocaleString('id-ID')}`;
        }
    </script>
</body>

</html>
