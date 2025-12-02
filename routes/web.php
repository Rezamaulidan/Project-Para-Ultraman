<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

// --- IMPORT SEMUA CONTROLLER ---
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
// use App\Http\Controllers\AbsensiController; // SUDAH TIDAK DIPAKAI (Pindah ke StafController)

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini tempat Anda mendaftarkan rute web untuk aplikasi Anda.
|
*/

// ====================================================
// 1. RUTE PUBLIK (Bisa diakses tanpa login)
// ====================================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kamar/{no_kamar}', [HomeController::class, 'showPenyewa'])->name('penyewa.detailkamar');

// --- OTENTIKASI (Login & Register & Logout) ---
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/pilihan-daftar', [RegisterController::class, 'pilihan'])->name('register.pilihan');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');


// ====================================================
// 2. LOGIKA LUPA PASSWORD / RESET PASSWORD (REAL)
// ====================================================

// A. Tampilkan Form Input Email
Route::get('/lupa-kata-sandi', function () {
    return view('forgot-password');
})->name('password.request');

// B. Proses Kirim Link ke Email
Route::post('/lupa-kata-sandi', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', 'Link reset password telah dikirim ke email Anda!')
        : back()->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
})->name('password.email');

// C. Tampilkan Form Reset Password
Route::get('/reset-password/{token}', function ($token, Request $request) {
    return view('reset-password', ['token' => $token, 'email' => $request->query('email')]);
})->name('password.reset');

// D. Proses Simpan Password Baru
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', 'Password berhasil direset! Silakan login.')
        : back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kadaluarsa.']);
})->name('password.update');


// ====================================================
// 3. RUTE AUTH (Wajib Login)
// ====================================================
Route::middleware(['auth'])->group(function () {

    // ------------------------------------------------
    // ROLE: PENYEWA
    // ------------------------------------------------
    Route::middleware(['role:penyewa'])->group(function () {
        // Dashboard
        Route::get('/dashboard-booking', [DashboardBookingController::class, 'booking'])->name('dashboard.booking');
        Route::get('/dashboard-penyewa', [PenyewaController::class, 'dashboard'])->name('penyewa.dashboard');

        // Profil & Informasi
        Route::get('/informasi-penyewa', [PenyewaController::class, 'showInformasi'])->name('penyewa.informasi');
        Route::get('/edit-informasi-penyewa', [PenyewaController::class, 'editInformasi'])->name('penyewa.edit_informasi');
        Route::put('/update-informasi-penyewa', [PenyewaController::class, 'updateInformasi'])->name('penyewa.update_informasi');

        // Fitur Utama
        Route::get('/informasi-keamanan', [PenyewaController::class, 'showKeamanan'])->name('penyewa.keamanan');
        Route::get('/menu-pembayaran', [PenyewaController::class, 'showPembayaran'])->name('penyewa.pembayaran');
        Route::get('/informasi-kamar-saya', [PenyewaController::class, 'showKamar'])->name('penyewa.kamar');

        // Proses Booking & Pembayaran
        Route::get('/booking/kamar/{no_kamar}', [BookingController::class, 'create'])->name('penyewa.booking.create');
        Route::post('/booking/kamar', [BookingController::class, 'store'])->name('penyewa.booking.store');

        // Halaman Bayar (Simulasi/Midtrans)
        Route::get('/booking/payment/{id}', [BookingController::class, 'showPaymentPage'])->name('penyewa.bayar');

        // [BARU] Route Bayar Tagihan (Melunasi status terlambat)
        Route::post('/booking/pay-arrears/{id}', [BookingController::class, 'bayarTagihan'])->name('penyewa.bayar.tagihan');

        // [BARU] Route Perpanjang Sewa (Menambah durasi bulan depan)
        Route::post('/booking/extend', [BookingController::class, 'perpanjangSewa'])->name('penyewa.bayar.perpanjang');
    });

    // ------------------------------------------------
    // ROLE: PEMILIK
    // ------------------------------------------------
    Route::middleware(['role:pemilik'])->group(function () {
        Route::get('/homepemilik', [PemilikKosController::class, 'index'])->name('pemilik.home');

        // Manajemen Profil & Foto
        Route::post('/profile/update-photo', [PemilikKosController::class, 'updatePhoto'])->name('profile.update_photo');
        Route::post('/profile/delete-photo', [PemilikKosController::class, 'deletePhoto'])->name('profile.delete_photo');
        // Alias route
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

        // Halaman Laporan (View Only)
        Route::get('/transaksipemilik', fn() => view('transaksi_pemilik'))->name('pemilik.transaksi');
        Route::get('/pengeluaranpemilik', fn() => view('pengeluaran_pemilik'))->name('pemilik.pengeluaran');
        Route::get('/keamananpemilik', fn() => view('keamanan_pemilik'))->name('pemilik.keamanan');
        Route::get('/datapenyewapemilik', fn() => view('data_penyewa_pemilik'))->name('pemilik.datapenyewa');

        Route::get('/info-detail-penyewa', [PemilikKosController::class, 'infoDetailPenyewa'])->name('pemilik.informasi.penyewa');
        Route::get('/info-detail-staff', [PemilikKosController::class, 'infoDetailStaff'])->name('pemilik.informasi.staff');

        // Manajemen Staf
        Route::get('/registrasistaff', fn() => view('registrasi_sfaff'))->name('pemilik.registrasi_staff');
        Route::post('/registrasi-staff', [PemilikKosController::class, 'storeStaff'])->name('pemilik.store_staff');
        Route::get('/datastaffpemilik', fn() => view('data_staff_pemilik'))->name('pemilik.datastaff');

        // Manajemen Booking (Approval)
        Route::get('/pemilik/permohonan-sewa', [BookingController::class, 'daftarPermohonan'])->name('pemilik.permohonan');
        Route::post('/pemilik/booking/{id}/approve', [BookingController::class, 'approveBooking'])->name('pemilik.booking.approve');
        Route::post('/pemilik/booking/{id}/reject', [BookingController::class, 'rejectBooking'])->name('pemilik.booking.reject');
        Route::post('/pemilik/booking/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('pemilik.booking.cancel');
    });

    // ------------------------------------------------
    // ROLE: STAF
    // ------------------------------------------------
    Route::middleware(['role:staf'])->group(function () {
        Route::get('/staff/menu', fn() => view('menu_staff'))->name('staff.menu');

        // Laporan Keamanan
        Route::get('/staff/laporan-keamanan', [LaporanKeamananController::class, 'index'])->name('staff.laporan_keamanan');
        Route::get('/staff/laporan-keamanan/create', [LaporanKeamananController::class, 'create'])->name('staff.laporan_keamanan.create');
        Route::post('/staff/laporan-keamanan', [LaporanKeamananController::class, 'store'])->name('staff.laporan_keamanan.store');

        // [DIPERBARUI] Absensi sekarang menggunakan StafController
        Route::get('/staff/shift-kerja', [StafController::class, 'absenIndex'])->name('staff.shift_kerja');
        Route::post('/staff/absen', [StafController::class, 'absenStore'])->name('staff.absen.store');

        // Profil Staf
        Route::get('/staff/manajemen', [StafController::class, 'indexManajemen'])->name('staff.manajemen');
        Route::get('/staff/profil/{id}', [StafController::class, 'lihatProfil'])->name('staff.lihat_profil');
        Route::get('/staff/profil/edit/{id}', [StafController::class, 'editProfil'])->name('staff.manajemen.edit');
        Route::put('/staff/profil/update/{id}', [StafController::class, 'updateProfil'])->name('staff.manajemen.update');

        // Info Penyewa
        Route::get('/staff/penyewa', [StafController::class, 'daftarPenyewa'])->name('staff.penyewa');
    });
});
