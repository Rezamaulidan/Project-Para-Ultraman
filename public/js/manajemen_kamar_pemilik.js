// manajemen_kamar_pemilik.js

document.addEventListener("DOMContentLoaded", function () {
    // --- 1. DAFTAR SEMUA VIEW --- (TIDAK BERUBAH)
    const views = {
        // ... (Semua view tetap didefinisikan di sini) ...
        manajemen: document.getElementById("manajemen-kamar-view"),
        lantaiSelection: document.getElementById(
            "input-data-kamar-lantai-selection-view"
        ),
        lantai1: document.getElementById("input-data-kamar-lantai-1-view"),
        lantai2: document.getElementById("input-data-kamar-lantai-2-view"),
        form: document.getElementById("input-data-kamar-form-view"),

        editLantaiSelection: document.getElementById(
            "edit-data-kamar-lantai-selection-view"
        ),
        editLantai1: document.getElementById("edit-data-kamar-lantai-1-view"),
        editLantai2: document.getElementById("edit-data-kamar-lantai-2-view"),
        detailKamar: document.getElementById("detail-kamar-view"),
        editForm: document.getElementById("edit-data-kamar-form-view"),

        infoLantaiSelection: document.getElementById(
            "info-data-kamar-lantai-selection-view"
        ),
        infoLantai1: document.getElementById("info-data-kamar-lantai-1-view"),
        infoLantai2: document.getElementById("info-data-kamar-lantai-2-view"),
        detailInfoKamar: document.getElementById("detail-info-kamar-view"),
    };

    let history = [];
    let currentView = "manajemen";

    function showView(viewName, pushHistory = true) {
        // ... (Fungsi showView tetap sama) ...
        if (pushHistory && viewName !== currentView) {
            history.push(currentView);
        }

        Object.values(views).forEach((view) => {
            if (view) view.style.display = "none";
        });

        if (views[viewName]) {
            views[viewName].style.display = "block";
            currentView = viewName;
            window.scrollTo(0, 0);
        }
    }

    function goBack() {
        // ... (Fungsi goBack tetap sama) ...
        if (history.length > 0) {
            const previousView = history.pop();
            showView(previousView, false);
        } else {
            showView("manajemen", false);
        }
    }

    document
        .getElementById("btn-header-back")
        ?.addEventListener("click", goBack);

    // --- 2. LOGIKA TRANSISI UTAMA (Tampilan 3 Tombol) ---
    // (Penting: Beri ID unik pada tombol Edit dan Informasi di Blade agar lebih mudah diakses)

    // A. Tombol 'Input Data Kamar'
    document
        .getElementById("btn-input-data-kamar")
        ?.addEventListener("click", () => {
            showView("lantaiSelection", true);
        });

    // B. Tombol 'Edit Data Kamar' (Kita ambil dari posisi ke-2)
    document
        .querySelector("#manajemen-kamar-view button:nth-child(2)")
        ?.addEventListener("click", () => {
            showView("editLantaiSelection", true);
        });

    // C. Tombol 'Informasi Kamar' (Kita ambil dari posisi ke-3)
    document
        .querySelector("#manajemen-kamar-view button:nth-child(3)")
        ?.addEventListener("click", () => {
            showView("infoLantaiSelection", true);
        });

    // --- 3. LOGIKA UNTUK INPUT DATA KAMAR (view 2, 3, 4, 5) ---

    // C1. Listfield Lantai 1 (Pilihan Lantai)
    document
        .getElementById("listfield-lantai-1")
        ?.addEventListener("click", () => {
            showView("lantai1", true);
        });

    // C2. Listfield Lantai 2 (Pilihan Lantai)
    document
        .getElementById("listfield-lantai-2")
        ?.addEventListener("click", () => {
            showView("lantai2", true);
        });

    // C3. Tombol 'Tambah Kamar' (Pilihan Lantai)
    document
        .getElementById("btn-tambah-kamar-1")
        ?.addEventListener("click", () => {
            showView("form", true);
        });

    // C4. Tombol 'Tambah Kamar' (Daftar Lantai 1)
    document
        .getElementById("btn-tambah-kamar-2")
        ?.addEventListener("click", () => {
            showView("form", true);
        });

    // C5. Tombol 'Tambah Kamar' (Daftar Lantai 2)
    document
        .getElementById("btn-tambah-kamar-3")
        ?.addEventListener("click", () => {
            showView("form", true);
        });

    // C6. Tombol Pindah Lantai (di dalam Tampilan Daftar Kamar Input)
    document
        .getElementById("btn-lantai-2-from-1")
        ?.addEventListener("click", () => {
            showView("lantai2", false);
        });
    document
        .getElementById("btn-lantai-1-from-2")
        ?.addEventListener("click", () => {
            showView("lantai1", false);
        });

    // --- 4. LOGIKA UNTUK EDIT DATA KAMAR (view 6, 7, 8, 9, 10) ---

    // D1. Pilih Lantai di Edit (view 6)
    document
        .getElementById("edit-listfield-lantai-1")
        ?.addEventListener("click", () => {
            showView("editLantai1", true);
        });
    document
        .getElementById("edit-listfield-lantai-2")
        ?.addEventListener("click", () => {
            showView("editLantai2", true);
        });

    // D2. Tombol Pindah Lantai (di dalam Tampilan Daftar Kamar Edit)
    document
        .getElementById("edit-btn-lantai-2-from-1")
        ?.addEventListener("click", () => {
            showView("editLantai2", false);
        });
    document
        .getElementById("edit-btn-lantai-1-from-2")
        ?.addEventListener("click", () => {
            showView("editLantai1", false);
        });

    // D3. Klik Kamar di Daftar Lantai (view 7 & 8)
    document
        .querySelectorAll(
            "#edit-room-list-lantai-1 .room-clickable, #edit-room-list-lantai-2 .room-clickable"
        )
        ?.forEach((roomItem) => {
            roomItem.addEventListener("click", () => {
                showView("detailKamar", true);
            });
        });

    // D4. Tombol Edit di Detail Kamar (view 9)
    document
        .getElementById("btn-edit-detail")
        ?.addEventListener("click", () => {
            showView("editForm", true);
        });

    // D5. Tombol Simpan di Formulir Edit (view 10)
    document
        .getElementById("form-edit-kamar")
        ?.addEventListener("submit", function (e) {
            e.preventDefault();
            alert("Data Kamar berhasil diupdate!");
            history = [];
            showView("manajemen", false);
            this.reset();
        });

    // --- 5. LOGIKA UNTUK INFORMASI KAMAR (view 11, 12, 13, 14) ---
    // (Logika yang baru Anda buat, harusnya berfungsi jika ID-nya benar)

    // E1. Pilih Lantai di Informasi (view 11)
    document
        .getElementById("info-listfield-lantai-1")
        ?.addEventListener("click", () => {
            showView("infoLantai1", true);
        });
    document
        .getElementById("info-listfield-lantai-2")
        ?.addEventListener("click", () => {
            showView("infoLantai2", true);
        });

    // E2. Tombol Pindah Lantai (di dalam Tampilan Daftar Kamar Info)
    document
        .getElementById("info-btn-lantai-2-from-1")
        ?.addEventListener("click", () => {
            showView("infoLantai2", false);
        });
    document
        .getElementById("info-btn-lantai-1-from-2")
        ?.addEventListener("click", () => {
            showView("infoLantai1", false);
        });

    // E3. Klik Kamar di Daftar Lantai (view 12 & 13)
    document
        .querySelectorAll(
            "#info-room-list-lantai-1 .info-room-clickable, #info-room-list-lantai-2 .info-room-clickable"
        )
        ?.forEach((roomItem) => {
            roomItem.addEventListener("click", () => {
                showView("detailInfoKamar", true); // Pindah ke Detail Info (Hanya Baca)
            });
        });

    // --- 6. TAMPILAN AWAL ---
    showView("manajemen", false);
});
