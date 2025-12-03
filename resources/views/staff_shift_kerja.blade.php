<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Staf Masuk & Keluar</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 0; }

        .attendance-container {
            max-width: 500px; margin: 40px auto; padding: 30px;
            background-color: #ffffff; border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .title { color: #001931; text-align: center; margin-bottom: 5px; font-size: 1.8em; font-weight: 700; }
        .subtitle { text-align: center; color: #6c757d; margin-bottom: 25px; font-size: 0.95em; }

        .card { padding: 25px; border-radius: 10px; border: 1px solid #e9ecef; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; margin-bottom: 8px; color: #001931; font-weight: 600; }
        input[type="text"] {
            width: 100%; padding: 12px; border: 2px solid #ced4da;
            border-radius: 6px; box-sizing: border-box; font-size: 1em;
        }
        input[type="text"]:focus { border-color: #001931; outline: none; }

        .btn {
            padding: 12px 20px; border: none; border-radius: 6px; color: white;
            cursor: pointer; font-weight: 700; width: 100%; font-size: 1em;
            transition: background-color 0.3s;
        }
        .btn.primary { background-color: #001931; }
        .btn.primary:hover { background-color: #002b55; }

        /* Warna Tombol Dinamis */
        .btn.success { background-color: #28a745; margin-top: 15px; } /* Hijau (Masuk) */
        .btn.success:hover { background-color: #218838; }

        .btn.danger { background-color: #dc3545; margin-top: 15px; } /* Merah (Keluar) */
        .btn.danger:hover { background-color: #c82333; }

        .badge { display: inline-block; padding: 4px 10px; border-radius: 15px; font-size: 0.85em; font-weight: 600; color: white; }
        .badge.status-aktif { background-color: #28a745; }
        .badge.status-nonaktif { background-color: #dc3545; }
        .badge.shift-info { background-color: #17a2b8; }

        .message { padding: 12px; margin-top: 15px; border-radius: 6px; text-align: center; font-weight: 600;}
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        /* Modal Styles */
        .modal-overlay {
            display: none; position: fixed; z-index: 1000;
            left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(2px);
        }
        .modal-content {
            background-color: #fefefe; margin: 10% auto; padding: 0;
            border-radius: 12px; width: 90%; max-width: 450px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            animation: slideDown 0.3s ease-out; overflow: hidden;
        }
        @keyframes slideDown { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .modal-header { padding: 15px 20px; background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center; }
        .close-btn { font-size: 28px; font-weight: bold; color: #aaa; cursor: pointer; line-height: 1; }
        .close-btn:hover { color: #000; }
        .modal-body { padding: 25px; }

        .loader { border: 3px solid #f3f3f3; border-top: 3px solid #001931; border-radius: 50%; width: 20px; height: 20px; animation: spin 1s linear infinite; display: inline-block; vertical-align: middle; margin-left: 10px; display: none; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

<div class="attendance-container">
    <h2 class="title">Sistem Presensi Staf 🏢</h2>
    <p class="subtitle">Silakan masukkan ID Staf untuk Check-In / Check-Out.</p>

    @if(session('success'))
        <div class="message success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="message error">⛔ {{ session('error') }}</div>
    @endif

    <div id="step-1" class="card active">
        <div class="input-group">
            <label for="staf_id">ID Staf</label>
            <input type="text" id="staf_id" name="staf_id" placeholder="Cth: 2023001" required autocomplete="off">
        </div>

        <button type="button" onclick="checkStaf()" class="btn primary">
            <span id="btn-text">Cek Status &rarr;</span>
            <span id="btn-loader" class="loader"></span>
        </button>

        <div id="js-error-message" class="message error" style="display: none;"></div>
    </div>
</div>

<div id="stafModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin:0; color:#001931;" id="modal-title">Konfirmasi</h3>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>

        <div class="modal-body">
            <h2 style="margin-top:0; margin-bottom:5px; color:#001931;">Halo, <span id="modal-nama"></span></h2>
            <p style="color: #6c757d; font-size: 0.9em; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                <span id="modal-jabatan"></span>
            </p>

            <div style="margin-bottom: 25px;">
                <div style="margin-bottom: 10px; font-size: 1.05em;">
                    <strong>Shift Kerja:</strong>
                    <span id="modal-shift" class="badge shift-info"></span>
                </div>
                <div style="font-size: 1.05em;">
                    <strong>Status Saat Ini:</strong>
                    <span id="modal-status-badge" class="badge"></span>
                </div>
            </div>

            <form action="{{ route('presensi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="staf_id_presensi" id="modal-id-hidden">
                <input type="hidden" name="jenis_presensi" id="modal-jenis-presensi">

                <button type="submit" id="btn-submit-presensi" class="btn">
                    </button>
            </form>
        </div>
    </div>
</div>

<script>
    function checkStaf() {
        const stafId = document.getElementById('staf_id').value;
        const errorDiv = document.getElementById('js-error-message');
        const btnText = document.getElementById('btn-text');
        const btnLoader = document.getElementById('btn-loader');

        errorDiv.style.display = 'none';

        if (!stafId) {
            errorDiv.innerText = "Harap isi ID Staf terlebih dahulu.";
            errorDiv.style.display = 'block';
            return;
        }

        btnText.style.display = 'none';
        btnLoader.style.display = 'inline-block';

        fetch("{{ route('presensi.check') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ staf_id: stafId })
        })
        .then(response => response.json())
        .then(res => {
            btnText.style.display = 'inline';
            btnLoader.style.display = 'none';

            if (res.status === 'success') {
                const data = res.data;
                const statusStr = data.status ? data.status.toLowerCase() : 'non-aktif';

                // 1. Isi Data Dasar
                document.getElementById('modal-nama').innerText = data.nama_staf;
                document.getElementById('modal-jabatan').innerText = "Staf ID: " + data.id_staf;
                document.getElementById('modal-shift').innerText = data.jadwal ? data.jadwal : '-';
                document.getElementById('modal-id-hidden').value = data.id_staf;

                // 2. Setup Badge Status
                const statusBadge = document.getElementById('modal-status-badge');
                statusBadge.innerText = data.status;
                statusBadge.className = 'badge';
                if(statusStr === 'aktif') {
                    statusBadge.classList.add('status-aktif');
                } else {
                    statusBadge.classList.add('status-nonaktif');
                }

                // 3. LOGIKA CERDAS: Tentukan Tombol Masuk atau Keluar
                const btnSubmit = document.getElementById('btn-submit-presensi');
                const inputJenis = document.getElementById('modal-jenis-presensi');
                const modalTitle = document.getElementById('modal-title');

                if (statusStr === 'aktif') {
                    // Jika user AKTIF -> Tampilkan Tombol KELUAR (Merah)
                    modalTitle.innerText = "Konfirmasi Pulang";
                    btnSubmit.innerHTML = "👋 Presensi Keluar (Pulang)";
                    btnSubmit.className = "btn danger"; // Merah
                    inputJenis.value = "keluar"; // Kirim 'keluar' ke controller
                } else {
                    // Jika user NON-AKTIF -> Tampilkan Tombol MASUK (Hijau)
                    modalTitle.innerText = "Konfirmasi Masuk";
                    btnSubmit.innerHTML = "✅ Presensi Masuk";
                    btnSubmit.className = "btn success"; // Hijau
                    inputJenis.value = "masuk"; // Kirim 'masuk' ke controller
                }

                document.getElementById('stafModal').style.display = 'block';
            } else {
                errorDiv.innerText = res.message || "ID Staf tidak ditemukan.";
                errorDiv.style.display = 'block';
            }
        })
        .catch(error => {
            console.error(error);
            btnText.style.display = 'inline';
            btnLoader.style.display = 'none';
            errorDiv.innerText = "Gagal menghubungi server.";
            errorDiv.style.display = 'block';
        });
    }

    function closeModal() {
        document.getElementById('stafModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('stafModal')) {
            closeModal();
        }
    }
</script>

</body>
</html>
