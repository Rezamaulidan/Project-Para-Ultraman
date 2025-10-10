<?php

use Illuminate\Support\Facades\Route;

Route::get('/pilihan-daftar', function () {
    return view('register');
});

Route::get('/', function () { 
    return view('home');
});
