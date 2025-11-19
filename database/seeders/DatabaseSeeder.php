<?php

namespace Database\Seeders;

use App\Models\Akun;
use App\Models\PemilikKos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $usernamePemilik = 'ibukos123';
        Akun::create([
            'username' => $usernamePemilik,
            'password' => Hash::make('reza123'),
            'jenis_akun' => 'pemilik'
        ]);

        PemilikKos::create([
            'username' => $usernamePemilik,
            'nama_pemilik' => 'Reza',
            'email' => 'brilyanto11@gmail.com',
            'no_hp' => '08123456789'
        ]);

        $usernameStaf = 'staf1';
        Akun::create([
            'username' => $usernameStaf,
            'password' => Hash::make('test1234'),
            'jenis_akun' => 'staf'
        ]);
        $this->call([
            PenyewaSeeder::class,
        ]);

    }
}
