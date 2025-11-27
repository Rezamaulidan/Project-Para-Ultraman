<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\DashboardBookingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LaporanKeamananController;
use App\Http\Controllers\PemilikKosController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\StafController;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\Akun;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// === RUTE PUBLIK ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kamar/{no_kamar}', [HomeController::class, 'showPenyewa'])->name('penyewa.detailkamar');

// Login & Register
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::get('/pilihan-daftar', [RegisterController::class, 'pilihan'])->name('register.pilihan');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Lupa Password (Standar)
Route::get('/lupa-kata-sandia', function () { return view('forgot-password'); })->name('password.request');
// ... (Logika lupa password lengkap Anda tetap bisa ditaruh di sini) ...

// === RUTE AUTH ===
Route::middleware(['auth'])->group(function () {

    // --- ROLE: PENYEWA ---
    Route::middleware(['role:penyewa'])->group(function () {
        Route::get('/dashboard-booking', [DashboardBookingController::class, 'booking'])->name('dashboard.booking');
        Route::get('/dashboard-penyewa', [PenyewaController::class, 'dashboard'])->name('penyewa.dashboard');
        
        // Menu Penyewa
        Route::get('/informasi-penyewa', [PenyewaController::class, 'showInformasi'])->name('penyewa.informasi');
        Route::get('/edit-informasi-penyewa', [PenyewaController::class, 'editInformasi'])->name('penyewa.edit_informasi');
        Route::put('/update-informasi-penyewa', [PenyewaController::class, 'updateInformasi'])->name('penyewa.update_informasi');
        Route::get('/informasi-keamanan', [PenyewaController::class, 'showKeamanan'])->name('penyewa.keamanan');
        Route::get('/menu-pembayaran', [PenyewaController::class, 'showPembayaran'])->name('penyewa.pembayaran');
        Route::get('/informasi-kamar-saya', [PenyewaController::class, 'showKamar'])->name('penyewa.kamar');
        
        // Transaksi
        Route::get('/booking/kamar/{no_kamar}', [BookingController::class, 'create'])->name('penyewa.booking.create');
        Route::post('/booking/kamar', [BookingController::class, 'store'])->name('penyewa.booking.store');
        Route::get('/booking/payment/{id}', [BookingController::class, 'showPaymentPage'])->name('penyewa.bayar');
        Route::post('/booking/payment/{id}/process', [BookingController::class, 'processPayment'])->name('penyewa.bayar.process');
    });

    // --- ROLE: PEMILIK ---
    Route::middleware(['role:pemilik'])->group(function () {
        Route::get('/homepemilik', [PemilikKosController::class, 'index'])->name('pemilik.home');

        // Foto Profil
        Route::post('/profile/update-photo', [PemilikKosController::class, 'updatePhoto'])->name('profile.update_photo');
        Route::post('/profile/delete-photo', [PemilikKosController::class, 'deletePhoto'])->name('profile.delete_photo');
        Route::post('/pemilik/profile/update-photo', [PemilikKosController::class, 'updatePhoto'])->name('pemilik.profile.updatePhoto');
        Route::post('/pemilik/profile/delete-photo', [PemilikKosController::class, 'deletePhoto'])->name('pemilik.profile.deletePhoto');

        // Kamar
        Route::get('/datakamarpemilik', [KamarController::class, 'index'])->name('pemilik.datakamar');
        Route::get('/inputkamar', [KamarController::class, 'create'])->name('pemilik.inputkamar');
        Route::post('/inputkamar', [KamarController::class, 'store'])->name('pemilik.inputkamar.store');
        Route::get('/infokamar/{no_kamar}', [KamarController::class, 'infoKamarDetail'])->name('pemilik.infokamar');
        Route::get('/editkamar/{no_kamar}', [KamarController::class, 'edit'])->name('pemilik.editkamar');
        Route::put('/updatekamar/{no_kamar}', [KamarController::class, 'update'])->name('pemilik.editkamar.update');
        Route::delete('/kamar/{no_kamar}', [KamarController::class, 'destroy'])->name('pemilik.kamar.destroy');

        // Menu Lain
        Route::get('/transaksipemilik', fn() => view('transaksi_pemilik'))->name('pemilik.transaksi');
        Route::get('/pengeluaranpemilik', fn() => view('pengeluaran_pemilik'))->name('pemilik.pengeluaran');
        Route::get('/keamananpemilik', fn() => view('keamanan_pemilik'))->name('pemilik.keamanan');
        Route::get('/datapenyewapemilik', fn() => view('data_penyewa_pemilik'))->name('pemilik.datapenyewa');
        
        // Info Detail (Dari Master)
        Route::get('/info-detail-penyewa', [PemilikKosController::class, 'infoDetailPenyewa'])->name('pemilik.informasi.penyewa');
        Route::get('/info-detail-staff', [PemilikKosController::class, 'infoDetailStaff'])->name('pemilik.informasi.staff');

        // Staff
        Route::get('/registrasistaff', fn() => view('registrasi_sfaff'))->name('pemilik.registrasi_staff');
        Route::post('/registrasi-staff', [PemilikKosController::class, 'storeStaff'])->name('pemilik.store_staff');
        Route::get('/datastaffpemilik', fn() => view('data_staff_pemilik'))->name('pemilik.datastaff');

        // Manajemen Booking (PENTING)
        Route::get('/pemilik/permohonan-sewa', [BookingController::class, 'daftarPermohonan'])->name('pemilik.permohonan');
        Route::post('/pemilik/booking/{id}/approve', [BookingController::class, 'approveBooking'])->name('pemilik.booking.approve');
        Route::post('/pemilik/booking/{id}/reject', [BookingController::class, 'rejectBooking'])->name('pemilik.booking.reject');
        Route::post('/pemilik/booking/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('pemilik.booking.cancel');
    });

    // --- ROLE: STAF ---
    Route::middleware(['role:staf'])->group(function () {
        Route::get('/staff/menu', fn() => view('menu_staff'))->name('staff.menu');
        Route::get('/staff/laporan-keamanan', [LaporanKeamananController::class, 'index'])->name('staff.laporan_keamanan');
        Route::get('/staff/laporan-keamanan/create', [LaporanKeamananController::class, 'create'])->name('staff.laporan_keamanan.create');
        Route::post('/staff/laporan-keamanan', [LaporanKeamananController::class, 'store'])->name('staff.laporan_keamanan.store');
    });
});