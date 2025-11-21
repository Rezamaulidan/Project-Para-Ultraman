<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Akun; //
use Illuminate\Support\Facades\Hash;

class PenyewaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $usernamePenyewa = 'penyewa123';

        // 1. Buat Akun Login
        Akun::create([
            'username' => $usernamePenyewa,
            'password' => Hash::make('password'),
            'jenis_akun' => 'penyewa'
        ]);
    }
}
