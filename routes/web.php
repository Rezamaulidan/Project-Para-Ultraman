<?php

use Illuminate\Support\Facades\Route;

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