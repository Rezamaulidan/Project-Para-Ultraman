<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardBookingController;

pemilikkos
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login_pemilik');
});

Route::get('/homepemilik', function () {
    return view('home_pemilik');
});

Route::get('/datakamarpemilik', function () {
    return view('data_kamar_pemilik');
});

Route::get('/transaksipemilik', function () {
    return view('transaksi_pemilik');
});

Route::get('/pengeluaranpemilik', function () {
    return view('pengeluaran_pemilik');
});

Route::get('/registrasistaff', function () {
    return view('registrasi_sfaff');
});

Route::get('/keamananpemilik', function () {
    return view('keamanan_pemilik');
});

Route::get('/datapenyewapemilik', function () {
    return view('data_penyewa_pemilik');
});

Route::get('/datastaffpemilik', function () {
    return view('data_staff_pemilik');
});

Route::get('/contoh', function () {
    return view('contoh');
});

// Rute untuk MENAMPILKAN halaman home (GET)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute untuk MENAMPILKAN form registrasi (GET)
Route::get('/pilihan-daftar', [RegisterController::class, 'pilihan'])->name('register.pilihan');

// Rute untuk MEMPROSES data form registrasi (POST)
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
tampilan-penyewa

// --- RUTE LOGIN ---
// Rute untuk MENAMPILKAN form login (GET)
Route::get('/login', [LoginController::class, 'index'])->name('login');

// Rute untuk MEMPROSES data login (POST)
Route::post('/login', [LoginController::class, 'store']); // <-- Rute ini akan menangani method POST ke URL /login

// --- RUTE LUPA PASSWORD (PLACEHOLDER) ---
Route::get('/lupa-kata-sandi', function () {
    return 'Halaman Lupa Kata Sandi belum dibuat.';
})->name('password.request');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute untuk menampilkan dashboard setelah login/register
Route::get('/dashboard-booking', [DashboardBookingController::class, 'booking'])->name('dashboard.booking');
=======
master
master
