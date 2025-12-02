<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Opsi ini mengontrol default "guard" dan password reset "broker" untuk
    | aplikasi Anda.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Guard mendefinisikan bagaimana pengguna diautentikasi untuk setiap request.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Provider mendefinisikan bagaimana pengguna diambil dari database.
    |
    | 🛑 PENTING: DI SINI BAGIAN YANG DIUBAH
    | Kita memberitahu Laravel untuk menggunakan model App\Models\Akun
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            // Pastikan baris ini mengarah ke model Akun Anda
            'model' => App\Models\Akun::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Konfigurasi ini bertanggung jawab untuk mengatur token reset password.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens', // Pastikan tabel ini ada di database
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Jumlah detik sebelum konfirmasi password kadaluarsa.
    |
    */

    'password_timeout' => 10800,

];
