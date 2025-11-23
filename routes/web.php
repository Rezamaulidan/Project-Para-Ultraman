<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\DashboardBookingController;
use App\Http\Controllers\LaporanKeamananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PemilikKosController;
use App\Http\Controllers\PenyewaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\Penyewa;
use App\Models\Staf;
use App\Models\PemilikKos;
use App\Models\Akun;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =======================================================
// 1. RUTE PUBLIK (Bisa diakses tanpa login)
// =======================================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Detail kamar untuk tamu/penyewa (belum login)
Route::get('/kamar/{no_kamar}', [HomeController::class, 'showPenyewa'])->name('penyewa.detailkamar');

// Login & Register
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::get('/pilihan-daftar', [RegisterController::class, 'pilihan'])->name('register.pilihan');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// --- Rute Lupa & Reset Password ---
Route::get('/lupa-kata-sandia', function () {
    return view('forgot-password');
})->name('password.request');

Route::post('/lupa-kata-sandia', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $email = $request->email;

    $profile = PemilikKos::where('email', $email)->first() ??
               Penyewa::where('email', $email)->first() ??
               Staf::where('email', $email)->first();

    if (!$profile) {
        return back()->withInput()->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
    }

    $akun = Akun::find($profile->username);
    if (!$akun) {
        return back()->withInput()->withErrors(['email' => 'Terjadi kesalahan: Akun untuk profil ini tidak ditemukan.']);
    }

    $akun->email = $email;

    $token = Password::createToken($akun);
    try {
        $akun->notify(new ResetPassword($token));
    } catch (\Exception $e) {
        return back()->withInput()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi nanti.']);
    }

    return back()->with('status', 'Kami telah mengirimkan link reset password ke email Anda!');
})->name('password.email');

Route::get('/reset-password/{token}', function ($token, Request $request) {
    return view('reset-password', ['token' => $token, 'email' => $request->query('email')]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:5|confirmed',
    ]);

    // 1. Validasi token manual
    $tokenData = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->first();

    // 2. Cek validitas token
    if (!$tokenData || !Hash::check($request->token, $tokenData->token)) {
        return back()->withInput()->withErrors(['email' => 'Token reset password ini tidak valid atau sudah kedaluwarsa.']);
    }

    // 3. Temukan profil
    $profile = PemilikKos::where('email', $request->email)->first() ??
               Penyewa::where('email', $request->email)->first() ??
               Staf::where('email', $request->email)->first();

    if (!$profile) {
        return back()->withInput()->withErrors(['email' => 'Email tidak ditemukan.']);
    }

    // 4. Update password akun
    $akun = Akun::find($profile->username);
    if ($akun) {
        $akun->password = Hash::make($request->password);
        $akun->save();
    } else {
        return back()->withInput()->withErrors(['email' => 'Akun untuk profil ini tidak ditemukan.']);
    }

    // 5. Hapus token
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return redirect()->route('login')->with('status', 'Password Anda telah berhasil direset! Silakan login.');
})->name('password.update');


// =======================================================
// 2. RUTE YANG MEMERLUKAN LOGIN (AUTH)
// =======================================================

Route::middleware(['auth'])->group(function () {

    // Logout (untuk semua role)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ====================== ROLE: PENYEWA ======================
    Route::middleware(['role:penyewa'])->group(function () {
        Route::get('/dashboard-booking', [DashboardBookingController::class, 'booking'])->name('dashboard.booking');
        Route::get('/dashboard-penyewa', fn() => view('dashboard_penyewa'))->name('penyewa.dashboard');
        Route::get('/informasi-penyewa', [PenyewaController::class, 'showInformasi'])->name('penyewa.informasi');
        Route::get('/edit-informasi-penyewa', [PenyewaController::class, 'editInformasi'])->name('penyewa.edit_informasi');
        Route::get('/informasi-keamanan', [PenyewaController::class, 'showKeamanan'])->name('penyewa.keamanan');
        Route::get('/menu-pembayaran', [PenyewaController::class, 'showPembayaran'])->name('penyewa.pembayaran');
        Route::get('/informasi-kamar-saya', [PenyewaController::class, 'showKamar'])->name('penyewa.kamar');
    });

    // ====================== ROLE: PEMILIK KOS ======================
    Route::middleware(['role:pemilik'])->group(function () {
        Route::get('/homepemilik', [PemilikKosController::class, 'index'])->name('pemilik.home');

        // Daftar Kamar
        Route::get('/datakamarpemilik', [KamarController::class, 'index'])->name('pemilik.datakamar');

        // Input & Simpan Kamar
        Route::get('/inputkamar', [KamarController::class, 'create'])->name('pemilik.inputkamar');
        Route::post('/inputkamar', [KamarController::class, 'store'])->name('pemilik.inputkamar.store');

        // Detail Kamar
        Route::get('/infokamar/{no_kamar}', [KamarController::class, 'infoKamarDetail'])->name('pemilik.infokamar');

        // Edit Kamar
        Route::get('/editkamar/{no_kamar}', [KamarController::class, 'edit'])->name('pemilik.editkamar');
        Route::put('/updatekamar/{no_kamar}', [KamarController::class, 'update'])->name('pemilik.editkamar.update');

        // // Hapus Kamar
        Route::delete('/kamar/{no_kamar}', [KamarController::class, 'destroy'])->name('pemilik.kamar.destroy');

        // Menu Lain Pemilik
        Route::get('/transaksipemilik', fn() => view('transaksi_pemilik'))->name('pemilik.transaksi');
        Route::get('/pengeluaranpemilik', fn() => view('pengeluaran_pemilik'))->name('pemilik.pengeluaran');
        Route::get('/keamananpemilik', fn() => view('keamanan_pemilik'))->name('pemilik.keamanan');
        Route::get('/datapenyewapemilik', fn() => view('data_penyewa_pemilik'))->name('pemilik.datapenyewa');

        // Manajemen Staff oleh Pemilik
        Route::get('/registrasistaff', fn() => view('registrasi_sfaff'))->name('pemilik.registrasi_staff');
        Route::post('/registrasi-staff', [PemilikKosController::class, 'storeStaff'])->name('pemilik.store_staff');
        Route::get('/datastaffpemilik', fn() => view('data_staff_pemilik'))->name('pemilik.datastaff');
    });

    // ====================== ROLE: STAF ======================
    Route::middleware(['role:staf'])->group(function () {

        // Menu Utama Staff
        Route::get('/staff/menu', fn() => view('menu_staff'))->name('staff.menu');

        // --- LAPORAN KEAMANAN ---

        // 1. Menampilkan Daftar Laporan (Index)
        Route::get('/staff/laporan-keamanan', [LaporanKeamananController::class, 'index'])
            ->name('staff.laporan_keamanan');

        // 2. Menampilkan Form Tambah (Create)
        Route::get('/staff/laporan-keamanan/create', [LaporanKeamananController::class, 'create'])
            ->name('staff.laporan_keamanan.create');

        // 3. Menyimpan Data Laporan (Store)
        Route::post('/staff/laporan-keamanan', [LaporanKeamananController::class, 'store'])
            ->name('staff.laporan_keamanan.store');

        // Placeholder Menu Lain Staff
        Route::get('/staff/manajemen', fn() => '<h1>Halaman Manajemen Staff...</h1>')->name('staff.manajemen');
        Route::get('/staff/penyewa', fn() => '<h1>Halaman Informasi Penyewa Staff (Segera Hadir)</h1>')->name('staff.penyewa');
        Route::get('/staff/shift-kerja', fn() => '<h1>Halaman Shift Kerja (Segera Hadir)</h1>')->name('staff.shift_kerja');
    });
});