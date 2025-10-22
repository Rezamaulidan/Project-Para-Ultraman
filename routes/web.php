<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // Dipilih salah satu definisi
});

// Rute Pilihan Daftar / Register
Route::get('/pilihan-daftar', function () {
    return view('register');
});

// Rute Tampilkan Halaman Login (GET)
Route::get('/login', function () {
    return view('login');
})->name('login.show');

// Rute PROSES LOGIN (POST) - HANYA MENGGUNAKAN CONTROLLER
Route::post('/login', [AuthController::class, 'authenticate'])->name('login');

// Rute Lupa Kata Sandi
Route::get('/password/request', function () {
    return view('forgot-password');
})->name('password.request');

// Rute Menu Staff (MIDDLEWARE AUTH DIHAPUS SEMENTARA)
Route::get('/staff/menu', function () {
    return view('menu_staff');
})->name('staff.menu');

// Rute Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
