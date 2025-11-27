<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - SIMK Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Segoe UI', sans-serif;
        }

        .payment-container {
            max-width: 900px;
            margin: 40px auto;
        }

        .header-logo {
            font-weight: bold;
            font-size: 1.5rem;
            color: #001931;
        }

        /* Box Styling */
        .payment-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .order-summary {
            background-color: #fff;
            padding: 30px;
        }

        .payment-methods {
            background-color: #f8f9fa;
            padding: 30px;
            border-left: 1px solid #eee;
        }

        /* Timer */
        .countdown-timer {
            background: #ffeeba;
            color: #856404;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 20px;
        }

        /* Accordion Custom */
        .accordion-button:not(.collapsed) {
            background-color: #e7f1ff;
            color: #0c63e4;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0, 0, 0, .125);
        }

        .bank-logo {
            width: 60px;
            height: auto;
            margin-right: 15px;
            object-fit: contain;
        }

        /* Total Amount */
        .total-amount {
            font-size: 1.8rem;
            font-weight: 800;
            color: #001931;
        }

        @media (max-width: 768px) {
            .payment-methods {
                border-left: none;
                border-top: 1px solid #eee;
            }
        }
    </style>
</head>

<body>

    <div class="container payment-container">
        <div class="card payment-card">
            <div class="row g-0">

                {{-- KOLOM KIRI: DETAIL ORDER --}}
                <div class="col-md-5 order-summary">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="header-logo"><i class="fas fa-home me-2"></i>SIMK Kos</div>
                    </div>

                    <div class="text-muted small mb-1">Total Pembayaran</div>
                    <div class="total-amount mb-4">Rp {{ number_format($booking->nominal, 0, ',', '.') }}</div>

                    <div class="mb-4">
                        <div class="countdown-timer">
                            <i class="fas fa-clock me-2"></i> Selesaikan dalam <span id="timer">01:00:00</span>
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Rincian Pesanan</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Order ID</span>
                        <span class="fw-bold">#BOOK-{{ $booking->id_booking }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Item</span>
                        <span class="fw-bold">Sewa Kamar No. {{ $booking->no_kamar }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Durasi</span>
                        <span class="fw-bold">{{ $booking->durasi_sewa }} Bulan</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Penyewa</span>
                        <span class="fw-bold">{{ Str::limit($booking->penyewa->nama_penyewa, 15) }}</span>
                    </div>

                    <div class="mt-5 text-center">
                        <a href="{{ route('penyewa.dashboard') }}" class="text-decoration-none text-muted small">
                            <i class="fas fa-arrow-left me-1"></i> Batal & Kembali
                        </a>
                    </div>
                </div>

                {{-- KOLOM KANAN: METODE PEMBAYARAN --}}
                <div class="col-md-7 payment-methods">
                    <h5 class="fw-bold mb-4">Pilih Metode Pembayaran</h5>

                    <div class="accordion" id="accordionPayment">

                        {{-- 1. Virtual Account --}}
                        <div class="accordion-item mb-2 border rounded overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseVA">
                                    <i class="fas fa-university me-2"></i> Virtual Account
                                </button>
                            </h2>
                            <div id="collapseVA" class="accordion-collapse collapse show"
                                data-bs-parent="#accordionPayment">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush">
                                        <label class="list-group-item d-flex align-items-center cursor-pointer">
                                            <input class="form-check-input me-3" type="radio" name="payment_method"
                                                value="bca" checked>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg"
                                                class="bank-logo" alt="BCA">
                                            <div>
                                                <div class="fw-bold">BCA Virtual Account</div>
                                                <small class="text-muted">Bayar via m-BCA atau ATM</small>
                                            </div>
                                        </label>
                                        <label class="list-group-item d-flex align-items-center cursor-pointer">
                                            <input class="form-check-input me-3" type="radio" name="payment_method"
                                                value="bri">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/BANK_BRI_logo.svg"
                                                class="bank-logo" alt="BRI">
                                            <div>
                                                <div class="fw-bold">BRI Virtual Account</div>
                                                <small class="text-muted">Bayar via BRIMO</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. E-Wallet --}}
                        <div class="accordion-item mb-2 border rounded overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseEwallet">
                                    <i class="fas fa-wallet me-2"></i> E-Wallet / QRIS
                                </button>
                            </h2>
                            <div id="collapseEwallet" class="accordion-collapse collapse"
                                data-bs-parent="#accordionPayment">
                                <div class="accordion-body">
                                    <label class="d-flex align-items-center mb-3">
                                        <input class="form-check-input me-3" type="radio" name="payment_method"
                                            value="qris">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1200px-Logo_QRIS.svg.png"
                                            class="bank-logo" alt="QRIS">
                                        <div class="fw-bold">QRIS (GoPay/OVO/Dana)</div>
                                    </label>
                                    <div class="alert alert-info small mb-0">
                                        Scan QR code yang akan muncul setelah Anda menekan tombol bayar.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- FORM SIMULASI BAYAR --}}
                    <form action="{{ route('penyewa.bayar.process', $booking->id_booking) }}" method="POST"
                        id="paymentForm">
                        @csrf
                        <button type="button" onclick="simulateProcessing()"
                            class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm" id="payBtn">
                            Bayar Sekarang <i class="fas fa-chevron-right ms-2"></i>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script Simulasi Loading & Timer --}}
    <script>
        // 1. Simulasi Timer Mundur
        function startTimer(duration, display) {
            var timer = duration,
                hours, minutes, seconds;
            setInterval(function() {
                hours = parseInt(timer / 3600, 10);
                minutes = parseInt((timer % 3600) / 60, 10);
                seconds = parseInt(timer % 60, 10);

                hours = hours < 10 ? "0" + hours : hours;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = hours + ":" + minutes + ":" + seconds;

                if (--timer < 0) {
                    timer = duration;
                }
            }, 1000);
        }

        window.onload = function() {
            var twentyFourHours = 60 * 60 * 1;
            var display = document.querySelector('#timer');
            startTimer(twentyFourHours, display);
        };

        // 2. Simulasi Loading saat klik Bayar
        function simulateProcessing() {
            const btn = document.getElementById('payBtn');
            const form = document.getElementById('paymentForm');

            // Ubah tombol jadi loading
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i> Memproses Pembayaran...';
            btn.disabled = true;

            // Simulasi delay 2 detik seolah-olah menghubungi Midtrans
            setTimeout(() => {
                form.submit();
            }, 2000);
        }
    </script>

</body>

</html>
