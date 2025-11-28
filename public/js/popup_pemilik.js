const profileModal = document.getElementById("profileModal");
const profilePhoto = document.getElementById("profilePhoto");
const photoInput = document.getElementById("photoInput");
const deletePhotoButton = document.getElementById("deletePhotoButton");

// --- 1. FUNGSI MODAL (Buka/Tutup) ---
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

PemilikKost;
if (photoInput) {
    photoInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            uploadPhoto(file);
        }
    });
}

function uploadPhoto(file) {
    const formData = new FormData();
    formData.append("foto", file);

    // Ambil CSRF Token dari meta tag
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    fetch("/pemilik/profile/update-photo", {
        // Ganti URL sesuai route Anda
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Update tampilan foto profil
                profilePhoto.src = data.foto_url;
                // Tampilkan tombol hapus
                if (deletePhotoButton) {
                    deletePhotoButton.style.display = "";
                }
                alert("Foto profil berhasil diperbarui!");
            } else {
                alert(
                    "Gagal mengupdate foto: " +
                        (data.message || "Terjadi kesalahan.")
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Terjadi kesalahan koneksi saat mengupload foto.");
        });
}

// --- FUNGSI BARU UNTUK HAPUS FOTO ---
function deletePhoto() {
    if (!confirm("Anda yakin ingin menghapus foto profil?")) {
        return;
    }

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    fetch("/pemilik/profile/delete-photo", {
        // Ganti URL sesuai route Anda
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Ganti foto dengan foto default
                profilePhoto.src = data.default_url;
                // Sembunyikan tombol hapus
                if (deletePhotoButton) {
                    deletePhotoButton.style.display = "none";
                }
                alert(data.message);
            } else {
                alert(
                    "Gagal menghapus foto: " +
                        (data.message || "Terjadi kesalahan.")
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Terjadi kesalahan koneksi saat menghapus foto.");
        });
}

// Pastikan fungsi ini tersedia secara global karena dipanggil di HTML Navbar

// Pastikan fungsi ini tersedia secara global
master;
window.openModal = openModal;
window.closeModal = closeModal;

// --- 2. FUNGSI UPLOAD FOTO ---
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
        const response = await fetch("/profile/update-photo", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });

        // Cek jika respon bukan JSON (misal error 419 Page Expired atau 500 server error)
        if (!response.ok) {
            throw new Error(`Server Error: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            // --- UPDATE TAMPILAN (DOM) ---
            const imgElement = document.getElementById("profileImage");
            const initialElement = document.getElementById("defaultAvatar");

            // 1. Update source gambar
            if (imgElement) {
                imgElement.src = result.foto_url;
                imgElement.style.display = "block"; // Pastikan gambar muncul
            }

            // 2. Sembunyikan inisial huruf
            if (initialElement) {
                initialElement.style.display = "none";
            }

            // Tampilkan notifikasi sukses
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Foto profil diperbarui!",
                timer: 1500,
                showConfirmButton: false,
            });

            // Reload halaman agar tombol hapus muncul (jika sebelumnya tidak ada)
            // dan agar data di navbar juga terupdate
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
        // Sembunyikan loading & reset input
        if (loadingOverlay) loadingOverlay.style.display = "none";
        fileInput.value = "";
    }
}

// --- 3. FUNGSI HAPUS FOTO ---
async function deletePhoto() {
    const confirmResult = await Swal.fire({
        title: "Hapus Foto?",
        text: "Foto profil akan dihapus",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus!",
    });

    if (!confirmResult.isConfirmed) return;

    // Tampilkan loading saat menghapus
    const loadingOverlay = document.getElementById("photoLoading");
    if (loadingOverlay) loadingOverlay.style.display = "flex";

    try {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        const response = await fetch("/profile/delete-photo", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
        });

        const result = await response.json();

        if (result.success) {
            Swal.fire({
                icon: "success",
                title: "Terhapus!",
                text: "Foto profil berhasil dihapus.",
                timer: 1500,
                showConfirmButton: false,
            });

            setTimeout(() => location.reload(), 1500);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: "Gagal menghapus foto.",
        });
        if (loadingOverlay) loadingOverlay.style.display = "none";
    }
}

// Expose ke window agar bisa dipanggil via onclick HTML
window.uploadPhoto = uploadPhoto;
window.deletePhoto = deletePhoto;
