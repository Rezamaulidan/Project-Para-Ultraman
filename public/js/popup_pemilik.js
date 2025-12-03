// ISI LENGKAP FILE popup_pemilik.js (dengan fungsi async/await yang dikoreksi)

// Menggunakan document.querySelector untuk kompatibilitas dan menghilangkan dependensi jQuery ($)
// Namun, karena Anda menggunakan SweetAlert2, pastikan ia di-load melalui CDN.

// --- 1. FUNGSI MODAL (Buka/Tutup) ---
const profileModal = document.getElementById("profileModal");

function openModal() {
    if (profileModal) {
        profileModal.classList.add("show");
    }
}

function closeModal() {
    if (profileModal) {
        profileModal.classList.remove("show");
    }
}

// Expose ke window agar bisa dipanggil via onclick HTML
window.openModal = openModal;
window.closeModal = closeModal;
window.uploadPhoto = uploadPhoto;
window.deletePhoto = deletePhoto;


// --- 2. FUNGSI UPLOAD FOTO (Mengambil dari fileInput) ---
async function uploadPhoto() {
    const fileInput = document.getElementById("fileInput");
    const file = fileInput.files[0];

    if (!file) return;

    // Validasi sederhana di sisi klien
    if (!file.type.match("image.*")) {
        Swal.fire({
            icon: "warning",
            title: "File Salah",
            text: "Harap pilih file gambar (JPG, PNG, GIF).",
        });
        return;
    }

    // Tampilkan Loading
    const loadingOverlay = document.getElementById("photoLoading");
    if (loadingOverlay) loadingOverlay.style.display = "flex";

    const formData = new FormData();
    formData.append("foto", file);

    try {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        // KOREKSI URL UNTUK UPDATE FOTO
        const response = await fetch("/pemilik/profile/update-photo", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });

        if (!response.ok) {
            throw new Error(`Server Error: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            // --- UPDATE TAMPILAN (DOM) ---
            const imgElement = document.getElementById("profileImage");

            if (imgElement) {
                imgElement.src = result.foto_url;
                imgElement.style.display = "block";
            }

            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Foto profil diperbarui!",
                timer: 1500,
                showConfirmButton: false,
            });

            // Reload setelah sukses agar tombol hapus muncul/data navbar terupdate
            setTimeout(() => location.reload(), 1500);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: "Gagal mengupload foto. Silakan coba lagi.",
        });
    } finally {
        if (loadingOverlay) loadingOverlay.style.display = "none";
        fileInput.value = "";
    }
}

// --- 3. FUNGSI HAPUS FOTO (Menggunakan SweetAlert2) ---
async function deletePhoto() {
    const confirmResult = await Swal.fire({
        title: "Hapus Foto?",
        text: "Foto profil akan dihapus permanen.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus!",
    });

    if (!confirmResult.isConfirmed) return;

    const loadingOverlay = document.getElementById("photoLoading");
    if (loadingOverlay) loadingOverlay.style.display = "flex";

    try {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        // KOREKSI URL UNTUK HAPUS FOTO
        const response = await fetch("/pemilik/profile/delete-photo", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || `Server Error: ${response.status}`);
        }

        if (result.success) {
            Swal.fire({
                icon: "success",
                title: "Terhapus!",
                text: "Foto profil berhasil dihapus.",
                timer: 1500,
                showConfirmButton: false,
            });

            // Reload setelah sukses untuk update tampilan DOM secara penuh
            setTimeout(() => location.reload(), 1500);

        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: `Gagal menghapus foto. Error: ${error.message || "Kesalahan koneksi."}`,
        });
    } finally {
        if (loadingOverlay) loadingOverlay.style.display = "none";
    }
}
