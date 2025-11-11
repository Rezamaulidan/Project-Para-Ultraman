<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardBookingController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\Penyewa;
use App\Models\Staf;
use App\Models\Akun;
use App\Models\PemilikKos;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// =======================================================
// RUTE PUBLIK (Bisa diakses tanpa login)
// =======================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
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
    $email = $request->query('email');
    return view('reset-password', ['token' => $token, 'email' => $email]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:5|confirmed',
    ]);
    $profile = PemilikKos::where('email', $request->email)->first() ??
               Penyewa::where('email', $request->email)->first() ??
               Staf::where('email', $request->email)->first();
    if (!$profile) {
        return back()->withInput()->withErrors(['email' => 'Email tidak ditemukan.']);
    }
    $akun = Akun::find($profile->username);
    if (!$akun) {
        return back()->withInput()->withErrors(['email' => 'Akun untuk profil ini tidak ditemukan.']);
    }
    $tokenData = DB::table('password_reset_tokens')
                     ->where('email', $request->email)
                     ->first();
    if (!$tokenData || !Hash::check($request->token, $tokenData->token)) {
         return back()->withInput()->withErrors(['email' => 'Token reset password ini tidak valid atau sudah kedaluwarsa.']);
    }
    $akun->password = Hash::make($request->password);
    $akun->save();
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();
    return redirect()->route('login')->with('status', 'Password Anda telah berhasil direset! Silakan login.');
})->name('password.update');


// =======================================================
// RUTE YANG DILINDUNGI (Wajib Login)
// =======================================================
Route::middleware(['auth'])->group(function () {

    // Rute Logout (Bisa diakses semua peran)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // --- GRUP PENYEWA ---
    // Hanya bisa diakses oleh 'penyewa'
    Route::middleware(['role:penyewa'])->group(function () {
        Route::get('/dashboard-booking', [DashboardBookingController::class, 'booking'])->name('dashboard.booking');
    });

    // --- GRUP PEMILIK KOS ---
    // Hanya bisa diakses oleh 'pemilik'
    Route::middleware(['role:pemilik'])->group(function () {
        Route::get('/homepemilik', function () {
            return view('home_pemilik');
        })->name('pemilik.home');

        Route::get('/datakamarpemilik', function () {
            return view('data_kamar_pemilik');
        })->name('pemilik.datakamar');

        Route::get('/transaksipemilik', function () {
            return view('transaksi_pemilik');
        })->name('pemilik.transaksi');

        Route::get('/pengeluaranpemilik', function () {
            return view('pengeluaran_pemilik');
        })->name('pemilik.pengeluaran');

        Route::get('/keamananpemilik', function () {
            return view('keamanan_pemilik');
        })->name('pemilik.keamanan');

        Route::get('/datapenyewapemilik', function () {
            return view('data_penyewa_pemilik');
        })->name('pemilik.datapenyewa');

        // Rute untuk Staf (dikelola oleh Pemilik)
        Route::get('/registrasistaff', function () {
            return view('registrasi_sfaff');
        })->name('pemilik.registrasi_staff');

        Route::get('/datastaffpemilik', function () {
            return view('data_staff_pemilik');
        })->name('pemilik.datastaff');
    });

    // --- GRUP STAF ---
    // Hanya bisa diakses oleh 'staf'
    Route::middleware(['role:staf'])->group(function () {
        Route::get('/staff/menu', function () {
            return view('menu_staff');
        })->name('staff.menu');
    });

});
