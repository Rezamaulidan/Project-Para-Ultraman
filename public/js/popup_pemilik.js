const profileModal = document.getElementById("profileModal");
const profilePhoto = document.getElementById("profilePhoto");
const photoInput = document.getElementById("photoInput");
const deletePhotoButton = document.getElementById("deletePhotoButton");

// Fungsi untuk membuka Modal
function openModal() {
    if (profileModal) {
        profileModal.classList.add("show");
    }
}

// Fungsi untuk menutup Modal
function closeModal() {
    if (profileModal) {
        profileModal.classList.remove("show");
    }
}

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
window.openModal = openModal;
window.closeModal = closeModal;
