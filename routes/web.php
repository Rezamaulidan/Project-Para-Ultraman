<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;

// Rute untuk MENAMPILKAN halaman home (GET)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute untuk MENAMPILKAN form registrasi (GET)
Route::get('/pilihan-daftar', [RegisterController::class, 'pilihan'])->name('register.pilihan');

// Rute untuk MEMPROSES data form registrasi (POST)
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
