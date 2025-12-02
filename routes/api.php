<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini tempat mendaftarkan route API untuk aplikasi Anda.
| Route ini dimuat oleh RouteServiceProvider dan diberi grup middleware "api".
|
*/

// Route bawaan Laravel (Default)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route Test (Opsional, bisa Anda hapus jika tidak perlu)
Route::get('/test', function () {
    return response()->json(['message' => 'API route working!']);
});
