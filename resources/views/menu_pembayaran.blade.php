<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Pembayaran - SIMK</title>

    {{-- Link untuk memuat file CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Link Font Awesome (agar ikon berfungsi) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            color: #0089FF;
            font-weight: 700;
            background-color: white;
            border-bottom-color: white;
        }

        /* Style untuk card status pembayaran */
        .card-status {
            border-left: 4px solid #0089FF;
        }

        .text-nominal {
            color: #dc3545;
            font-size: 1.25rem;
            font-weight: 600;
        }

        /* Style untuk tab bayar tagihan */
        .nav-pills .nav-link {
            color: #6c757d;
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
        }

        .nav-pills .nav-link.active {
            background-color: #0089FF;
            color: white;
        }

        /* Style untuk metode pembayaran */
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

        .payment-method input[type="radio"],
        .payment-method-perpanjang input[type="radio"] {
            cursor: pointer;
        }

        /* Style untuk tombol bayar */
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

        /* Style untuk rincian pembayaran */
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

    {{-- 1. Memanggil navbar utama penyewa --}}
    @include('partials.navbar_menu_penyewa')

    {{-- 2. Header Halaman (Biru) --}}
    <div class="header-penyewa shadow-sm">
        <div class="container-fluid d-flex align-items-center">
        </div>
    </div>

    {{-- 3. Tab Navigasi (Abu-abu) --}}
    <div class="container-fluid bg-light pt-2 shadow-sm">
        <ul class="nav nav-tabs nav-fill justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.informasi') }}">
                    <i class="fa-solid fa-circle-info me-2"></i>Informasi Penyewa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.keamanan') }}">
                    <i class="fa-solid fa-shield-halved me-2"></i>Informasi Keamanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">
                    <i class="fa-solid fa-money-check-dollar me-2"></i>Menu Pembayaran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.kamar') }}">
                    <i class="fa-solid fa-box me-2"></i>Informasi Kamar
                </a>
            </li>
        </ul>
    </div>

    {{-- ===== 4. KONTEN UTAMA ===== --}}
    <div class="container-fluid p-3 p-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">

                {{-- Card Status Pembayaran --}}
                <div class="card border-0 shadow-sm mb-4 card-status">
                    <div class="card-body p-4">
                        <h6 class="text-muted mb-3 text-uppercase small fw-bold">Status Pembayaran</h6>
                        <div class="row">
                            {{-- [MODIFIKASI] Nama Penyewa --}}
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-muted d-block">Nama:</small>
                                <p class="mb-0 fw-bold text-dark">{{ $penyewa->nama_penyewa ?? '-' }}</p>
                            </div>

                            {{-- [MODIFIKASI] Nomor Kamar --}}
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-muted d-block">Nomor Kamar:</small>
                                <p class="mb-0 fw-bold text-dark">{{ $penyewa->nomor_kamar ?? '-' }}</p>
                            </div>

                            {{-- Jumlah Tagihan (Data Sementara) --}}
                            <div class="col-md-4">
                                <small class="text-muted d-block">Jumlah Tagihan</small>
                                <p class="mb-0 text-nominal">Rp 1.500.000</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Menu Pembayaran --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">

                        {{-- Tab Bayar Tagihan / Perpanjang Sewa --}}
                        <ul class="nav nav-pills mb-4 justify-content-center justify-content-md-start" id="paymentTab"
                            role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="bayar-tab" data-bs-toggle="pill"
                                    data-bs-target="#bayar-content" type="button" role="tab">
                                    Bayar Tagihan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link ms-2" id="perpanjang-tab" data-bs-toggle="pill"
                                    data-bs-target="#perpanjang-content" type="button" role="tab">
                                    Perpanjang Sewa
                                </button>
                            </li>
                        </ul>

                        {{-- Tab Content --}}
                        <div class="tab-content" id="paymentTabContent">

                            {{-- Content Bayar Tagihan --}}
                            <div class="tab-pane fade show active" id="bayar-content" role="tabpanel">

                                {{-- Rincian Pembayaran --}}
                                <div class="mb-4">
                                    <h6 class="mb-3 fw-bold text-secondary">Rincian Pembayaran</h6>
                                    <div class="bg-light p-3 rounded border">
                                        <div class="rincian-row d-flex justify-content-between">
                                            <span>Tagihan Bulan Ini:</span>
                                            <span>Rp 1.500.000</span>
                                        </div>
                                        <div class="rincian-row d-flex justify-content-between mt-2">
                                            <span class="fw-bold">Total Pembayaran:</span>
                                            <span class="text-primary fw-bold fs-5">Rp 1.500.000</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pilih Metode Pembayaran --}}
                                <div class="mb-4">
                                    <h6 class="mb-3 fw-bold text-secondary">Pilih Metode Pembayaran</h6>

                                    {{-- Transfer Bank --}}
                                    <div class="payment-method rounded p-3 mb-3" onclick="selectPayment('transfer')">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentMethod"
                                                id="transferBank" value="transfer">
                                            <label class="form-check-label w-100" for="transferBank">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>Transfer Bank</strong>
                                                        <small class="d-block text-muted">BCA, Mandiri, BNI, BRI</small>
                                                    </div>
                                                    <i class="fa-solid fa-building-columns fa-2x text-secondary"></i>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- E-Wallet --}}
                                    <div class="payment-method rounded p-3 mb-3" onclick="selectPayment('ewallet')">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentMethod"
                                                id="eWallet" value="ewallet">
                                            <label class="form-check-label w-100" for="eWallet">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>E-Wallet</strong>
                                                        <small class="d-block text-muted">GoPay, OVO, DANA,
                                                            LinkAja</small>
                                                    </div>
                                                    <i class="fa-solid fa-wallet fa-2x text-secondary"></i>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol Bayar --}}
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-bayar shadow-sm" onclick="prosesPayment()">
                                         Bayar
                                    </button>
                                </div>

                            </div>

                            {{-- Content Perpanjang Sewa --}}
                            <div class="tab-pane fade" id="perpanjang-content" role="tabpanel">

                                {{-- Pilih Durasi Pembayaran --}}
                                <div class="mb-4">
                                    <h6 class="mb-3 fw-bold text-secondary">Pilih Durasi Perpanjangan</h6>
                                    <select class="form-select form-select-lg border-secondary" id="durasiPembayaran"
                                        onchange="updateTotalPerpanjang()">
                                        <option value="1">1 Bulan</option>
                                        <option value="2" selected>2 Bulan</option>
                                        <option value="3">3 Bulan</option>
                                        <option value="6">6 Bulan</option>
                                        <option value="12">12 Bulan</option>
                                    </select>
                                </div>

                                {{-- Rincian Pembayaran Perpanjang --}}
                                <div class="mb-4">
                                    <h6 class="mb-3 fw-bold text-secondary">Rincian Pembayaran</h6>
                                    <div class="bg-light p-3 rounded border">
                                        <div class="rincian-row d-flex justify-content-between">
                                            <span>Biaya Sewa per Bulan:</span>
                                            <span id="biaya-per-bulan">Rp 1.500.000</span>
                                        </div>
                                        <div class="rincian-row d-flex justify-content-between">
                                            <span>Durasi:</span>
                                            <span id="durasi-text">2 Bulan</span>
                                        </div>
                                        <div class="rincian-row d-flex justify-content-between mt-2">
                                            <span class="fw-bold">Total Pembayaran:</span>
                                            <span class="text-primary fw-bold fs-5" id="total-perpanjang">Rp
                                                3.000.000</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pilih Metode Pembayaran --}}
                                <div class="mb-4">
                                    <h6 class="mb-3 fw-bold text-secondary">Pilih Metode Pembayaran</h6>

                                    {{-- Transfer Bank --}}
                                    <div class="payment-method-perpanjang rounded p-3 mb-3"
                                        onclick="selectPaymentPerpanjang('transfer')">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="paymentMethodPerpanjang" id="transferBankPerpanjang"
                                                value="transfer">
                                            <label class="form-check-label w-100" for="transferBankPerpanjang">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>Transfer Bank</strong>
                                                        <small class="d-block text-muted">BCA, Mandiri, BNI,
                                                            BRI</small>
                                                    </div>
                                                    <i class="fa-solid fa-building-columns fa-2x text-secondary"></i>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- E-Wallet --}}
                                    <div class="payment-method-perpanjang rounded p-3 mb-3"
                                        onclick="selectPaymentPerpanjang('ewallet')">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="paymentMethodPerpanjang" id="eWalletPerpanjang"
                                                value="ewallet">
                                            <label class="form-check-label w-100" for="eWalletPerpanjang">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>E-Wallet</strong>
                                                        <small class="d-block text-muted">GoPay, OVO, DANA,
                                                            LinkAja</small>
                                                    </div>
                                                    <i class="fa-solid fa-wallet fa-2x text-secondary"></i>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol Bayar --}}
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-bayar shadow-sm"
                                        onclick="prosesPaymentPerpanjang()">
                                        Bayar
                                    </button>
                                </div>

                            </div>

                        </div>

                    </div>
                </div> {{-- End col --}}
            </div> {{-- End row --}}
        </div> {{-- End container-fluid --}}

        {{-- Link untuk memuat JavaScript Bootstrap --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        {{-- JavaScript untuk Sliding Indicator --}}
        <script>
            // Fungsi untuk mengatur posisi sliding indicator
            function setActiveIndicator() {
                const activeTab = document.querySelector('.nav-tabs .nav-link.active');
                const navTabs = document.querySelector('.nav-tabs');

                if (activeTab && navTabs) {
                    const tabRect = activeTab.getBoundingClientRect();
                    const navRect = navTabs.getBoundingClientRect();

                    const left = tabRect.left - navRect.left;
                    const width = tabRect.width;

                    navTabs.style.setProperty('--indicator-left', `${left}px`);
                    navTabs.style.setProperty('--indicator-width', `${width}px`);
                }
            }

            // Jalankan saat halaman dimuat
            document.addEventListener('DOMContentLoaded', function() {
                setActiveIndicator();

                window.addEventListener('resize', setActiveIndicator);

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

                document.querySelector('.nav-tabs').addEventListener('mouseleave', function() {
                    setActiveIndicator();
                });
            });

            // Fungsi untuk select payment method
            function selectPayment(method) {
                document.querySelectorAll('.payment-method').forEach(el => {
                    el.classList.remove('active');
                });
                event.currentTarget.classList.add('active');
                const radio = event.currentTarget.querySelector('input[type="radio"]');
                radio.checked = true;
            }

            // Fungsi untuk select payment method perpanjang
            function selectPaymentPerpanjang(method) {
                document.querySelectorAll('.payment-method-perpanjang').forEach(el => {
                    el.classList.remove('active');
                });
                event.currentTarget.classList.add('active');
                const radio = event.currentTarget.querySelector('input[type="radio"]');
                radio.checked = true;
            }

            // Fungsi untuk proses pembayaran
            function prosesPayment() {
                const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked');

                if (!selectedMethod) {
                    alert('Silakan pilih metode pembayaran terlebih dahulu!');
                    return;
                }
                const methodName = selectedMethod.nextElementSibling.querySelector('strong').textContent;
                alert(`Memproses pembayaran dengan ${methodName}...`);
            }

            // Fungsi untuk proses pembayaran perpanjang
            function prosesPaymentPerpanjang() {
                const selectedMethod = document.querySelector('input[name="paymentMethodPerpanjang"]:checked');

                if (!selectedMethod) {
                    alert('Silakan pilih metode pembayaran terlebih dahulu!');
                    return;
                }
                const durasi = document.getElementById('durasiPembayaran').value;
                const total = document.getElementById('total-perpanjang').textContent;
                const methodName = selectedMethod.nextElementSibling.querySelector('strong').textContent;
                alert(`Memproses perpanjangan sewa ${durasi} bulan\nTotal: ${total}\nMetode: ${methodName}`);
            }

            // Fungsi untuk update total perpanjang sewa
            function updateTotalPerpanjang() {
                const durasi = parseInt(document.getElementById('durasiPembayaran').value);
                const biayaPerBulan = 1500000; // Sesuaikan dengan data dari database
                const total = durasi * biayaPerBulan;

                // Update tampilan
                document.getElementById('durasi-text').textContent = `${durasi} Bulan`;
                document.getElementById('total-perpanjang').textContent =
                    `Rp ${total.toLocaleString('id-ID')}`;
            }

            // Event listener untuk perubahan durasi
            document.addEventListener('DOMContentLoaded', function() {
                // Initial load
                updateTotalPerpanjang();
            });
        </script>
</body>

</html>
