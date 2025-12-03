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
// AbsensiController sudah dipindahkan ke StafController

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. RUTE PUBLIK
// ====================================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kamar/{no_kamar}', [HomeController::class, 'showPenyewa'])->name('penyewa.detailkamar');

// --- OTENTIKASI ---
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/pilihan-daftar', [RegisterController::class, 'pilihan'])->name('register.pilihan');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');


// ====================================================
// 2. LOGIKA LUPA PASSWORD / RESET PASSWORD (REAL EMAIL)
// ====================================================

Route::get('/lupa-kata-sandi', function () {
    return view('forgot-password');
})->name('password.request');

Route::post('/lupa-kata-sandi', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', 'Link reset password telah dikirim ke email Anda!')
        : back()->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
})->name('password.email');

Route::get('/reset-password/{token}', function ($token, Request $request) {
    return view('reset-password', ['token' => $token, 'email' => $request->query('email')]);
})->name('password.reset');

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
        Route::get('/dashboard-booking', [DashboardBookingController::class, 'booking'])->name('dashboard.booking');
        Route::get('/dashboard-penyewa', [PenyewaController::class, 'dashboard'])->name('penyewa.dashboard');

        Route::get('/informasi-penyewa', [PenyewaController::class, 'showInformasi'])->name('penyewa.informasi');
        Route::get('/edit-informasi-penyewa', [PenyewaController::class, 'editInformasi'])->name('penyewa.edit_informasi');
        Route::put('/update-informasi-penyewa', [PenyewaController::class, 'updateInformasi'])->name('penyewa.update_informasi');

        Route::get('/informasi-keamanan', [PenyewaController::class, 'showKeamanan'])->name('penyewa.keamanan');
        Route::get('/menu-pembayaran', [PenyewaController::class, 'showPembayaran'])->name('penyewa.pembayaran');
        Route::get('/informasi-kamar-saya', [PenyewaController::class, 'showKamar'])->name('penyewa.kamar');

        // Booking & Pembayaran (Simulasi)
        Route::get('/booking/kamar/{no_kamar}', [BookingController::class, 'create'])->name('penyewa.booking.create');
        Route::post('/booking/kamar', [BookingController::class, 'store'])->name('penyewa.booking.store');
        Route::get('/booking/payment/{id}', [BookingController::class, 'showPaymentPage'])->name('penyewa.bayar');

        // Bayar & Perpanjang
        Route::post('/booking/pay-arrears/{id}', [BookingController::class, 'bayarTagihan'])->name('penyewa.bayar.tagihan');
        Route::post('/booking/extend', [BookingController::class, 'perpanjangSewa'])->name('penyewa.bayar.perpanjang');

        Route::post('/booking/payment/{id}/process', [BookingController::class, 'processPayment'])->name('penyewa.bayar.process');
    });

    // ------------------------------------------------
    // ROLE: PEMILIK
    // ------------------------------------------------
    Route::middleware(['role:pemilik'])->group(function () {
        Route::get('/homepemilik', [PemilikKosController::class, 'index'])->name('pemilik.home');

        // Manajemen Profil & Foto
        Route::post('/pemilik/profile/update-photo', [PemilikKosController::class, 'updatePhoto'])->name('pemilik.profile.update');
        Route::post('/pemilik/profile/delete-photo', [PemilikKosController::class, 'deleteProfilePhoto'])->name('pemilik.profile.delete');

        // Manajemen Kamar
        Route::get('/datakamarpemilik', [KamarController::class, 'index'])->name('pemilik.datakamar');
        Route::get('/inputkamar', [KamarController::class, 'create'])->name('pemilik.inputkamar');
        Route::post('/inputkamar', [KamarController::class, 'store'])->name('pemilik.inputkamar.store');
        Route::get('/infokamar/{no_kamar}', [KamarController::class, 'infoKamarDetail'])->name('pemilik.infokamar');
        Route::get('/editkamar/{no_kamar}', [KamarController::class, 'edit'])->name('pemilik.editkamar');
        Route::put('/updatekamar/{no_kamar}', [KamarController::class, 'update'])->name('pemilik.editkamar.update');
        Route::delete('/kamar/{no_kamar}', [KamarController::class, 'destroy'])->name('pemilik.kamar.destroy');

        // Fitur Laporan (Transaksi & Pengeluaran)
        Route::get('/transaksipemilik', [PemilikKosController::class, 'transaksiPemilik'])->name('pemilik.transaksi');
        Route::get('/transaksi-export-lunas', [PemilikKosController::class, 'exportTransaksiLunas'])->name('transaksi.export');
        Route::get('/transaksi-search', [PemilikKosController::class, 'searchTransaksiLunas'])->name('transaksi.search');

        Route::get('/pengeluaranpemilik', [PemilikKosController::class, 'pengeluaranPemilik'])->name('pemilik.pengeluaran');
        Route::post('/pengeluaran-store', [PemilikKosController::class, 'storePengeluaran'])->name('pengeluaran.store');
        Route::delete('/pengeluaran-delete/{id}', [PemilikKosController::class, 'destroyPengeluaran'])->name('pengeluaran.destroy');

        // Menu Lain
        Route::get('/keamananpemilik', [PemilikKosController::class, 'laporanKeamanan'])->name('pemilik.keamanan');
        Route::get('/datapenyewapemilik', [PemilikKosController::class, 'dataPenyewaPemilik'])->name('pemilik.datapenyewa');

        Route::get('/info-detail-penyewa/{username}', [PemilikKosController::class, 'infoDetailPenyewa'])->name('pemilik.informasi.penyewa');
        Route::get('/info-detail-staff/{id_staf}', [PemilikKosController::class, 'infoDetailStaff'])->name('pemilik.informasi.staff');

        // Manajemen Staf
        Route::get('/registrasistaff', fn() => view('registrasi_sfaff'))->name('pemilik.registrasi_staff');
        Route::post('/registrasi-staff', [PemilikKosController::class, 'storeStaff'])->name('pemilik.store_staff');
        Route::get('/datastaffpemilik', [PemilikKosController::class, 'dataStaff'])->name('pemilik.datastaff');
        // Route Tampilan Edit
        // Route::get('/staf/shift/{id_staf}', [PemilikKosController::class, 'editShift'])->name('staf.edit_shift');

        // // Route Proses Update
        // Route::put('/staf/shift/update/{id_staf}', [PemilikKosController::class, 'updateShift'])->name('staf.update_shift');

        // URL: http://website-anda.com/staf/manajemen-shift
        Route::get('/staf/manajemen-shift', [PemilikKosController::class, 'editJadwal'])
        ->name('pemilik.shift.index');

    // 2. PROSES: Route untuk tombol "Simpan" (Menerima data JSON dari React)
    // URL: http://website-anda.com/staf/update-shift
        Route::post('/staf/update-shift', [PemilikKosController::class, 'updateJadwal'])
        ->name('pemilik.shift.update');

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

        Route::get('/staff/laporan-keamanan', [LaporanKeamananController::class, 'index'])->name('staff.laporan_keamanan');
        Route::get('/staff/laporan-keamanan/create', [LaporanKeamananController::class, 'create'])->name('staff.laporan_keamanan.create');
        Route::post('/staff/laporan-keamanan', [LaporanKeamananController::class, 'store'])->name('staff.laporan_keamanan.store');

        // Absensi (Pindah ke StafController)
        Route::get('/staff/shift-kerja', [StafController::class, 'absenIndex'])->name('staff.shift_kerja');
        Route::post('/staff/absen', [StafController::class, 'absenStore'])->name('staff.absen.store');

        // Profil Staf
        Route::get('/staff/manajemen', [StafController::class, 'indexManajemen'])->name('staff.manajemen');
        Route::get('/staff/profil/{id}', [StafController::class, 'lihatProfil'])->name('staff.lihat_profil');
        Route::get('/staff/profil/edit/{id}', [StafController::class, 'editProfil'])->name('staff.manajemen.edit');
        Route::put('/staff/profil/update/{id}', [StafController::class, 'updateProfil'])->name('staff.manajemen.update');

        Route::get('/staff/penyewa', [StafController::class, 'daftarPenyewa'])->name('staff.penyewa');
    });
});
