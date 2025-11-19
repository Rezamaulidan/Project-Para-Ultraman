<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardBookingController;
use App\Http\Controllers\AuthController; // Ditambahkan untuk Rute Staff

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
// RUTE UNTUK PENYEWA
// =======================================================

// Rute Halaman Utama (Home)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute Registrasi
Route::get('/pilihan-daftar', [RegisterController::class, 'pilihan'])->name('register.pilihan');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Rute Login & Logout
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute Dashboard Penyewa (Setelah Login)
Route::get('/dashboard-booking', [DashboardBookingController::class, 'booking'])->name('dashboard.booking');

// Rute Lupa Password (Placeholder)
Route::get('/lupa-kata-sandi', function () {
    return 'Halaman Lupa Kata Sandi belum dibuat.';
})->name('password.request');


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

// Rute Keamanan Pemilik Kos
Route::get('/keamananpemilik', function () {
    return view('keamanan_pemilik');
})->name('pemilik.keamanan');

// Rute Data Penyewa Pemilik Kos
Route::get('/datapenyewapemilik', function () {
    return view('data_penyewa_pemilik');
})->name('pemilik.datapenyewa');


// =======================================================
// RUTE UNTUK STAFF KOS
// =======================================================

// resources/views/menu_staff.blade.php
Route::get('/staff/menu', function () {
    // Rute yang memuat menu utama
    return view('menu_staff');
})->name('staff.menu');

// Rute Manajemen Staff
Route::get('/staff/manajemen', function () {
    return '<h1>Halaman Manajemen Staff...</h1>';
})->name('staff.manajemen');

// Rute Informasi Penyewa
Route::get('/staff/penyewa', function () {
    return '<h1>Halaman Informasi Penyewa Staff (Segera Hadir)</h1>';
})->name('staff.penyewa');

// Rute Laporan Keamanan (Saatnya ganti return text ke return view)
Route::get('/staff/laporan-keamanan', function () {
    // --- GANTI BARIS INI! ---
    // Pastikan Anda sudah menyimpan kode UI Laporan Keamanan di file 'laporan_keamanan.blade.php'
    return view('laporan_keamanan');
})->name('staff.laporan_keamanan');

// Rute Shift Kerja
Route::get('/staff/shift-kerja', function () {
    return '<h1>Halaman Shift Kerja (Segera Hadir)</h1>';
})->name('staff.shift_kerja');
