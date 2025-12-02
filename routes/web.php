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
use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\Akun;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\StaffPenyewaController;

// === RUTE PUBLIK ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kamar/{no_kamar}', [HomeController::class, 'showPenyewa'])->name('penyewa.detailkamar');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::get('/pilihan-daftar', [RegisterController::class, 'pilihan'])->name('register.pilihan');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Lupa Password
Route::get('/lupa-kata-sandi', function () { return view('forgot-password'); })->name('password.request');
Route::post('/lupa-kata-sandi', function (Illuminate\Http\Request $request) {
    return back()->with('status', 'Link reset password telah dikirim (Simulasi)!');
})->name('password.email');
Route::get('/reset-password/{token}', function ($token, Illuminate\Http\Request $request) {
    return view('reset-password', ['token' => $token, 'email' => $request->query('email')]);
})->name('password.reset');
Route::post('/reset-password', function (Illuminate\Http\Request $request) {
    return redirect()->route('login')->with('status', 'Password berhasil direset!');
})->name('password.update');

// === RUTE AUTH ===
Route::middleware(['auth'])->group(function () {

    // --- ROLE: PENYEWA ---
    Route::middleware(['role:penyewa'])->group(function () {
        Route::get('/dashboard-booking', [DashboardBookingController::class, 'booking'])->name('dashboard.booking');
        Route::get('/dashboard-penyewa', [PenyewaController::class, 'dashboard'])->name('penyewa.dashboard');

        Route::get('/informasi-penyewa', [PenyewaController::class, 'showInformasi'])->name('penyewa.informasi');
        Route::get('/edit-informasi-penyewa', [PenyewaController::class, 'editInformasi'])->name('penyewa.edit_informasi');
        Route::put('/update-informasi-penyewa', [PenyewaController::class, 'updateInformasi'])->name('penyewa.update_informasi');
        Route::get('/informasi-keamanan', [PenyewaController::class, 'showKeamanan'])->name('penyewa.keamanan');
        Route::get('/menu-pembayaran', [PenyewaController::class, 'showPembayaran'])->name('penyewa.pembayaran');
        Route::get('/informasi-kamar-saya', [PenyewaController::class, 'showKamar'])->name('penyewa.kamar');

        Route::get('/booking/kamar/{no_kamar}', [BookingController::class, 'create'])->name('penyewa.booking.create');
        Route::post('/booking/kamar', [BookingController::class, 'store'])->name('penyewa.booking.store');
        Route::get('/booking/payment/{id}', [BookingController::class, 'showPaymentPage'])->name('penyewa.bayar');
        Route::post('/booking/payment/{id}/process', [BookingController::class, 'processPayment'])->name('penyewa.bayar.process');
    });

    // --- ROLE: PEMILIK ---
    Route::middleware(['role:pemilik'])->group(function () {
        Route::get('/homepemilik', [PemilikKosController::class, 'index'])->name('pemilik.home');

        Route::post('/profile/update-photo', [PemilikKosController::class, 'updatePhoto'])->name('profile.update_photo');
        Route::post('/profile/delete-photo', [PemilikKosController::class, 'deletePhoto'])->name('profile.delete_photo');
        // Route alternatif
        Route::post('/pemilik/profile/update-photo', [PemilikKosController::class, 'updatePhoto'])->name('pemilik.profile.updatePhoto');
        Route::post('/pemilik/profile/delete-photo', [PemilikKosController::class, 'deletePhoto'])->name('pemilik.profile.deletePhoto');

        // Manajemen Kamar
        Route::get('/datakamarpemilik', [KamarController::class, 'index'])->name('pemilik.datakamar');
        Route::get('/inputkamar', [KamarController::class, 'create'])->name('pemilik.inputkamar');
        Route::post('/inputkamar', [KamarController::class, 'store'])->name('pemilik.inputkamar.store');
        Route::get('/infokamar/{no_kamar}', [KamarController::class, 'infoKamarDetail'])->name('pemilik.infokamar');
        Route::get('/editkamar/{no_kamar}', [KamarController::class, 'edit'])->name('pemilik.editkamar');
        Route::put('/updatekamar/{no_kamar}', [KamarController::class, 'update'])->name('pemilik.editkamar.update');
        Route::delete('/kamar/{no_kamar}', [KamarController::class, 'destroy'])->name('pemilik.kamar.destroy');

        // [MODIFIKASI: Menggunakan Controller untuk fitur Search & Export]
        Route::get('/transaksipemilik', [PemilikKosController::class, 'transaksiPemilik'])->name('pemilik.transaksi');
        Route::get('/transaksi-export-lunas', [PemilikKosController::class, 'exportTransaksiLunas'])->name('transaksi.export');
        Route::get('/transaksi-search', [PemilikKosController::class, 'searchTransaksiLunas'])->name('transaksi.search');

        // [MODIFIKASI: Pengeluaran dengan fitur Tambah & Hapus]
        Route::get('/pengeluaranpemilik', [PemilikKosController::class, 'pengeluaranPemilik'])->name('pemilik.pengeluaran');
        Route::post('/pengeluaran-store', [PemilikKosController::class, 'storePengeluaran'])->name('pengeluaran.store');
        Route::delete('/pengeluaran-delete/{id}', [PemilikKosController::class, 'destroyPengeluaran'])->name('pengeluaran.destroy');

        // Menu Lain
        Route::get('/keamananpemilik', [PemilikKosController::class, 'laporanKeamanan'])->name('pemilik.keamanan');
        Route::get('/datapenyewapemilik', [PemilikKosController::class, 'dataPenyewaPemilik'])->name('pemilik.datapenyewa');

        Route::get('/info-detail-penyewa/{username}', [PemilikKosController::class, 'infoDetailPenyewa'])->name('pemilik.informasi.penyewa');
        Route::get('/info-detail-staff/{id_staf}', [PemilikKosController::class, 'infoDetailStaff'])->name('pemilik.informasi.staff');

        Route::get('/registrasistaff', fn() => view('registrasi_sfaff'))->name('pemilik.registrasi_staff');
        Route::post('/registrasi-staff', [PemilikKosController::class, 'storeStaff'])->name('pemilik.store_staff');
        Route::get('/datastaffpemilik', [PemilikKosController::class, 'dataStaff'])->name('pemilik.datastaff');

        // Manajemen Booking
        Route::get('/pemilik/permohonan-sewa', [BookingController::class, 'daftarPermohonan'])->name('pemilik.permohonan');
        Route::post('/pemilik/booking/{id}/approve', [BookingController::class, 'approveBooking'])->name('pemilik.booking.approve');
        Route::post('/pemilik/booking/{id}/reject', [BookingController::class, 'rejectBooking'])->name('pemilik.booking.reject');
        Route::post('/pemilik/booking/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('pemilik.booking.cancel');
    });

    // --- ROLE: STAF ---
// --- ROLE: STAF ---
    Route::middleware(['role:staf'])->group(function () {

        Route::get('/staff/menu', fn() => view('menu_staff'))->name('staff.menu');

        // Laporan Keamanan
        Route::get('/staff/laporan-keamanan', [LaporanKeamananController::class, 'index'])->name('staff.laporan_keamanan');
        Route::get('/staff/laporan-keamanan/create', [LaporanKeamananController::class, 'create'])->name('staff.laporan_keamanan.create');
        Route::post('/staff/laporan-keamanan', [LaporanKeamananController::class, 'store'])->name('staff.laporan_keamanan.store');

        // --- PERBAIKAN DI SINI ---
        // Pastikan hanya ada SATU baris ini untuk penyewa
        Route::get('/staff/penyewa', [StaffPenyewaController::class, 'index'])->name('staff.penyewa');

        // Absensi
        Route::get('/staff/shift-kerja', [AbsensiController::class, 'index'])->name('staff.shift_kerja');
        Route::post('/staff/absen/store', [AbsensiController::class, 'store'])->name('staff.absen.store');

        // Manajemen Profil
        Route::get('/staff/manajemen', [StafController::class, 'indexManajemen'])->name('staff.manajemen');
        Route::get('/staff/profil/{id}', [StafController::class, 'lihatProfil'])->name('staff.lihat_profil');
        Route::get('/staff/profil-saya/edit', [StafController::class, 'editProfil'])->name('staff.manajemen.edit');
        Route::put('/staff/profil-saya/update', [StafController::class, 'updateProfil'])->name('staff.manajemen.update');

        // --- HAPUS BARIS DI BAWAH INI ---
        // Route::get('/staff/penyewa', fn() => '<h1>Informasi Penyewa...</h1>')->name('staff.penyewa');
        // ^^^ INI WAJIB DIHAPUS BIAR TIDAK BENTROK
    });
});
