@extends('profil_pemilik')
@section('title', 'Informasi Data Penyewa - SIMK')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --kos-navy: #001931;
    --kos-soft-navy: #002a52;
    --kos-white: #ffffff;
}

/* Styling Container agar rapi */
.detail-container {
    padding: 40px 20px;
    background-color: #f4f6f9;
    /* Background halaman abu sangat muda */
    min-height: 80vh;
}

/* Card Utama */
.card-penyewa {
    border: none;
    border-radius: 25px;
    /* Sudut melengkung biar "lucu"/soft */
    background-color: var(--kos-white);
    box-shadow: 0 10px 30px rgba(0, 25, 49, 0.1);
    /* Bayangan soft warna navy */
    overflow: hidden;
}

/* Bagian Header Biru di dalam Card */
.card-header-custom {
    background-color: var(--kos-navy);
    color: white;
    padding: 20px 30px;
    border-bottom: 5px solid #e9ecef;
    position: relative;
}

/* Hiasan lengkungan kecil di header */
.card-header-custom::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 100%;
    height: 20px;
    background-color: var(--kos-white);
    border-radius: 20px 20px 0 0;
}

/* Foto Profil */
.profile-section {
    text-align: center;
    padding: 30px;
}

.img-thumbnail-custom {
    width: 180px;
    height: 180px;
    object-fit: cover;
    border-radius: 50%;
    /* Lingkaran */
    border: 5px solid var(--kos-navy);
    padding: 3px;
    background: white;
}

.user-badge {
    background-color: var(--kos-navy);
    color: white;
    padding: 5px 15px;
    border-radius: 50px;
    font-size: 0.85rem;
    margin-top: 10px;
    display: inline-block;
}

/* Styling Label dan Value Data */
.data-row {
    margin-bottom: 20px;
    border-bottom: 1px dashed #e0e0e0;
    padding-bottom: 10px;
}

.data-label {
    font-weight: 600;
    color: #555;
    font-size: 0.9rem;
    display: block;
    margin-bottom: 5px;
}

.data-value {
    font-weight: 700;
    color: var(--kos-navy);
    font-size: 1.1rem;
}

.icon-box {
    width: 40px;
    height: 40px;
    background-color: #e6f0fa;
    /* Biru sangat muda */
    color: var(--kos-navy);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    margin-right: 15px;
    font-size: 1.2rem;
}

/* Tombol Back */
.btn-back {
    background-color: transparent;
    color: var(--kos-navy);
    border: 2px solid var(--kos-navy);
    border-radius: 50px;
    padding: 8px 25px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-back:hover {
    background-color: var(--kos-navy);
    color: white;
}
</style>

<div class="detail-container">
    <div class="container">

        <div class="d-flex align-items-center mb-4">
            <a href="datapenyewapemilik" class="btn btn-back me-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h3 class="m-0 fw-bold" style="color: #001931;">Detail Identitas Penyewa</h3>
        </div>

        <div class="card card-penyewa">

            <div class="card-header-custom text-center">
                <h5 class="m-0"><i class="fas fa-id-card me-2"></i> Informasi Personal</h5>
            </div>

            <div class="card-body p-0">
                <div class="row g-0">

                    <div class="col-md-4 d-flex flex-column align-items-center justify-content-center"
                        style="background-color: #fcfcfc; border-right: 1px solid #eee;">
                        <div class="profile-section">
                            <img src="https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436188.jpg"
                                alt="Foto Penyewa" class="img-thumbnail-custom shadow-sm">

                            <h4 class="mt-3 fw-bold" style="color: #001931;">Rizky Ramadhan</h4>
                            <p class="text-muted mb-1">@rizky_rmdhn</p> <span class="user-badge">Penyewa Aktif</span>
                        </div>
                    </div>

                    <div class="col-md-8 p-5">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start data-row">
                                    <div class="icon-box"><i class="fas fa-user"></i></div>
                                    <div>
                                        <span class="data-label">Nama Lengkap</span>
                                        <span class="data-value">Rizky Ramadhan</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-start data-row">
                                    <div class="icon-box"><i class="fas fa-venus-mars"></i></div>
                                    <div>
                                        <span class="data-label">Jenis Kelamin</span>
                                        <span class="data-value">Laki-laki</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-start data-row">
                                    <div class="icon-box"><i class="fas fa-birthday-cake"></i></div>
                                    <div>
                                        <span class="data-label">Tempat, Tanggal Lahir</span>
                                        <span class="data-value">Bandung, 12 Mei 2001</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-start data-row">
                                    <div class="icon-box"><i class="fas fa-envelope"></i></div>
                                    <div>
                                        <span class="data-label">Alamat Email</span>
                                        <span class="data-value">rizky@example.com</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex align-items-start data-row border-0">
                                    <div class="icon-box"><i class="fas fa-phone-alt"></i></div>
                                    <div>
                                        <span class="data-label">Nomor Handphone / WhatsApp</span>
                                        <span class="data-value">0853 4867 9875</span>
                                        <a href="https://wa.me/6285348679875"
                                            class="btn btn-sm btn-success ms-2 rounded-pill" target="_blank"><i
                                                class="fab fa-whatsapp"></i> Chat</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection