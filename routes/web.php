<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardBookingController;

// Rute untuk MENAMPILKAN halaman home (GET)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute untuk MENAMPILKAN form registrasi (GET)
Route::get('/pilihan-daftar', [RegisterController::class, 'pilihan'])->name('register.pilihan');

// Rute untuk MEMPROSES data form registrasi (POST)
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

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
