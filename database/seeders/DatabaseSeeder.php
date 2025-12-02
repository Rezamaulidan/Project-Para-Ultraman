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
        // ==========================================
        // 1. DATA PEMILIK KOS
        // ==========================================
        $usernamePemilik = 'ibukos123';

        // A. Buat Akun Login Pemilik (Cek jika belum ada)
        Akun::firstOrCreate(
            ['username' => $usernamePemilik], // Kunci pencarian
            [
                'password'   => Hash::make('reza123'),
                'jenis_akun' => 'pemilik'
            ]
        );

        // B. Buat Identitas Pemilik
        PemilikKos::updateOrCreate(
            ['username' => $usernamePemilik], // Kunci pencarian
            [
                'nama_pemilik' => 'Reza',
                'email'        => 'brilyanto11@gmail.com',
                'no_hp'        => '082117104383'
            ]
        );

        // ==========================================
        // 2. DATA AKUN STAF (SHARED ACCOUNT)
        // ==========================================

        Akun::firstOrCreate(
            ['username' => 'staf'],
            [
                'password'   => Hash::make('staf123'),
                'jenis_akun' => 'staf'
            ]
        );

        // ==========================================
        // 3. SEEDER TAMBAHAN
        // ==========================================
        $this->call([
            PenyewaSeeder::class,
        ]);
    }
}
