@extends('profil_pemilik')
@section('title', 'Informasi Data Staff - SIMK')

@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
:root {
    --navy-primary: #001931;
    --navy-light: #002a4d;
    --white: #ffffff;
    --grey-light: #f4f7f6;
    --accent-gold: #FFD700;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--grey-light);
}

/* Container Utama */
.detail-page-container {
    padding: 40px 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Card Umum */
.custom-card {
    background: var(--white);
    border-radius: 20px;
    /* Sedikit lebih kecil dari sebelumnya, lebih cocok desktop */
    box-shadow: 0 8px 25px rgba(0, 25, 49, 0.08);
    border: none;
    overflow: hidden;
    margin-bottom: 25px;
}

/* Panel Kiri: Identitas Staff */
.panel-identitas {
    padding: 30px;
    text-align: center;
}

.profile-img-lg-wrapper {
    width: 160px;
    height: 160px;
    margin: 0 auto 20px;
    position: relative;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    overflow: hidden;
    border: 5px solid var(--white);
}

.profile-img-lg {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background-color: #ddd;
}

/* Hover untuk foto profil */
.profile-img-lg-wrapper:hover .profile-img-lg {
    transform: scale(1.1) rotate(3deg);
}

.staff-name-lg {
    color: var(--navy-primary);
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 5px;
}

.staff-id-lg {
    background-color: #eef2f5;
    color: #6c757d;
    padding: 7px 20px;
    border-radius: 25px;
    font-size: 0.9rem;
    display: inline-block;
    font-weight: 600;
    margin-bottom: 20px;
}

/* Badge Shift */
.shift-badge-lg {
    margin: 20px auto;
    padding: 12px 30px;
    border-radius: 25px;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    font-size: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease;
}

.shift-badge-lg:hover {
    transform: translateY(-3px);
}

.shift-pagi {
    background-color: #fffde7;
    /* Lebih lembut */
    color: #f57f17;
    border: 1px solid #ffe082;
}

.shift-sore {
    background-color: #e8eaf6;
    color: #3949ab;
    border: 1px solid #c5cae9;
}

/* Panel Kanan: Detail Informasi */
.panel-detail {
    padding: 30px;
}

.detail-section-title {
    color: var(--navy-primary);
    font-weight: 600;
    font-size: 1.2rem;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.detail-section-title i {
    margin-right: 10px;
    color: var(--accent-gold);
    /* Icon gold untuk highlight */
    font-size: 1.4rem;
}

.info-group {
    margin-bottom: 25px;
    padding: 15px 20px;
    background-color: var(--grey-light);
    border-radius: 15px;
}

.info-group:last-child {
    margin-bottom: 0;
}

.info-group label {
    display: block;
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 5px;
    font-weight: 500;
}

.info-group p,
.info-group a {
    color: var(--navy-primary);
    font-weight: 600;
    font-size: 1rem;
    margin: 0;
    text-decoration: none;
    display: flex;
    align-items: center;
}

.info-group a:hover {
    color: var(--navy-light);
}

.info-group p i,
.info-group a i {
    margin-right: 10px;
    color: #555;
    font-size: 1.1rem;
}

/* Tombol Hubungi */
.btn-hubungi {
    background-color: var(--navy-primary);
    color: var(--white);
    border: none;
    border-radius: 15px;
    padding: 15px 25px;
    font-weight: 600;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: background-color 0.3s ease, transform 0.2s ease;
    box-shadow: 0 4px 15px rgba(0, 25, 49, 0.2);
}

.btn-hubungi:hover {
    background-color: var(--navy-light);
    transform: translateY(-3px);
    color: var(--white);
}

/* Copy Notification */
#copyToast {
    visibility: hidden;
    min-width: 250px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 50px;
    padding: 16px;
    position: fixed;
    z-index: 99;
    left: 50%;
    bottom: 30px;
    transform: translateX(-50%);
    font-size: 14px;
}

#copyToast.show {
    visibility: visible;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

@keyframes fadein {
    from {
        bottom: 0;
        opacity: 0;
    }

    to {
        bottom: 30px;
        opacity: 1;
    }
}

@keyframes fadeout {
    from {
        bottom: 30px;
        opacity: 1;
    }

    to {
        bottom: 0;
        opacity: 0;
    }
}
</style>

<div class="detail-page-container">
    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="custom-card panel-identitas">
                <div class="profile-img-lg-wrapper">

                    <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Foto Staff" class="profile-img-lg">
                </div>

                <h2 class="staff-name-lg">Budi Santoso</h2>
                <div classs="d-flex justify-content-center">
                    <span class="staff-id-lg">ID: STF-2024-001</span>
                </div>
                <!-- <div class="d-block mt-4">
                    <div class="shift-badge-lg shift-pagi">
                        <i class="fas fa-sun"></i> <span>Shift Pagi (07:00 - 15:00)</span>
                    </div>
                </div> -->

                <a href="https://wa.me/6281348892871" target="_blank" class="btn-hubungi mt-4">
                    <i class="fab fa-whatsapp"></i> Hubungi Staff
                </a>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="custom-card panel-detail">
                <h5 class="detail-section-title">
                    <i class="fas fa-id-card-alt"></i> Informasi Kontak
                </h5>
                <div class="info-group">
                    <label>Nomor WhatsApp</label>
                    <p><i class="fas fa-phone-alt"></i>0813 4889 2871</p>
                </div>
                <div class="info-group" style="cursor: pointer;" onclick="copyEmail('budi.staff@kosan.com')">
                    <label>Email (Klik untuk salin)</label>
                    <p><i class="fas fa-envelope"></i> <span id="emailText">budi.staff@kosan.com</span> <i
                            class="far fa-copy ms-auto text-muted"></i></p>
                </div>

                <h5 class="detail-section-title mt-5">
                    <i class="fas fa-user-friends"></i> Informasi Umum Staff
                </h5>
                <div class="info-group">
                    <label>Jenis Kelamin</label>
                    <p><i class="fas fa-venus-mars"></i> Laki-laki</p>
                </div>
                <div class="info-group">
                    <label class="shift-label">Jadwal Shift</label>
                    <!-- <p><i class="fas fa-map-marker-alt"></i> Jl. Merdeka No. 45, Jakarta</p> -->
                    <i class="fas fa-sun shift-pagi"></i> <span><b>Shift Pagi (07:00 - 15:00)</b></span>
                </div>
                <div class="info-group">
                    <label>Status Karyawan</label>
                    <p><i class="fas fa-briefcase"></i> Full-time</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="copyToast">Email berhasil disalin!</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function copyEmail(email) {
    navigator.clipboard.writeText(email);
    var x = document.getElementById("copyToast");
    x.className = "show";
    setTimeout(function() {
        x.className = x.className.replace("show", "");
    }, 3000);
}
</script>
@endsection

<!-- <!DOCTYPE html>
<html lang="id">
<head>
</head>
    
<body>
</body>
</html> -->