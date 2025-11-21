const profileModal = document.getElementById("profileModal");

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

// Pastikan fungsi ini tersedia secara global karena dipanggil di HTML Navbar
window.openModal = openModal;
window.closeModal = closeModal;
