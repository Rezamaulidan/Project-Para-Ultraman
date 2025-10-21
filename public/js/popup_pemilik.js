const profileModal = document.getElementById("profileModal");
const profilePhotoContainer = document.getElementById("profilePhotoContainer");
const profileImage = document.getElementById("profileImage");
const defaultImage = document.getElementById("defaultImage");
const fileInput = document.getElementById("fileInput");
const clearPhotoButton = document.getElementById("clearPhotoButton");
const IMAGE_STORAGE_KEY = "uploadedProfileImage";

function openModal() {
    profileModal.classList.add("show");
}

function closeModal() {
    profileModal.classList.remove("show");
}

function showDefaultProfileImage() {
    profileImage.style.display = "none";
    defaultImage.style.display = "block";
    clearPhotoButton.style.display = "none";
    localStorage.removeItem(IMAGE_STORAGE_KEY);
}

function showUploadedImage(imageSrc) {
    profileImage.src = imageSrc;
    profileImage.style.display = "block";
    defaultImage.style.display = "none";
    clearPhotoButton.style.display = "block";
    localStorage.setItem(IMAGE_STORAGE_KEY, imageSrc);
}

document.addEventListener("DOMContentLoaded", () => {
    const savedImage = localStorage.getItem(IMAGE_STORAGE_KEY);
    if (savedImage) {
        showUploadedImage(savedImage);
    } else {
        showDefaultProfileImage();
    }
});

fileInput.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            showUploadedImage(e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

clearPhotoButton.addEventListener("click", () => {
    showDefaultProfileImage();
    fileInput.value = "";
});

// Pastikan fungsi ini tersedia secara global karena dipanggil di HTML Navbar
window.openModal = openModal;
window.closeModal = closeModal;
