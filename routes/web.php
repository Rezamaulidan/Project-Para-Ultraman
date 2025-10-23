<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardBookingController;

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
// RUTE UNTUK PENYEWA (Tampilan Penyewa)
// =======================================================

// Rute Halaman Utama (Home)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute untuk MENAMPILKAN form registrasi (GET)
Route::get('/pilihan-daftar', [RegisterController::class, 'pilihan'])->name('register.pilihan');

// Rute untuk MEMPROSES data form registrasi (POST)
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Rute untuk MENAMPILKAN form login (GET)
Route::get('/login', [LoginController::class, 'index'])->name('login');

// Rute untuk MEMPROSES data login (POST)
Route::post('/login', [LoginController::class, 'store']);

// Rute untuk menampilkan dashboard setelah login/register
Route::get('/dashboard-booking', [DashboardBookingController::class, 'booking'])->name('dashboard.booking');

// Rute Lupa Password (Placeholder)
Route::get('/lupa-kata-sandi', function () {
    return 'Halaman Lupa Kata Sandi belum dibuat.';
})->name('password.request');

// Rute Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// =======================================================
// RUTE UNTUK PEMILIK KOS
// =======================================================

// Rute Home Pemilik Kos
Route::get('/homepemilik', function () {
    return view('home_pemilik');
})->name('pemilik.home');

// Rute Data Kamar Pemilik Kos
Route::get('/datakamarpemilik', function () {
    return view('data_kamar_pemilik');
})->name('pemilik.datakamar');

// Rute Transaksi Pemilik Kos
Route::get('/transaksipemilik', function () {
    return view('transaksi_pemilik');
})->name('pemilik.transaksi');

// Rute Pengeluaran Pemilik Kos
Route::get('/pengeluaranpemilik', function () {
    return view('pengeluaran_pemilik');
})->name('pemilik.pengeluaran');

// Rute Registrasi Staff oleh Pemilik Kos
Route::get('/registrasistaff', function () {
    return view('registrasi_sfaff'); // Perbaiki nama view jika salah
})->name('pemilik.registrasi_staff');

// Rute Keamanan Pemilik Kos
Route::get('/keamananpemilik', function () {
    return view('keamanan_pemilik');
})->name('pemilik.keamanan');

// Rute Data Penyewa Pemilik Kos
Route::get('/datapenyewapemilik', function () {
    return view('data_penyewa_pemilik');
})->name('pemilik.datapenyewa');

// Rute Data Staff Pemilik Kos
Route::get('/datastaffpemilik', function () {
    return view('data_staff_pemilik');
})->name('pemilik.datastaff');
