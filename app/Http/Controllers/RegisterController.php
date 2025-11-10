<?php

namespace App\Http\Controllers;

use App\Models\Akun; // <-- 1. Import model User
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- 2. Import Hash untuk enkripsi password
use Illuminate\Support\Facades\Auth; // <-- 3. Import Auth untuk login otomatis
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    // Method untuk menampilkan form register
    public function pilihan()
    {
        return view('register');
    }

    // Method untuk menyimpan data dan login
    public function store(Request $request)
    {
        // 1. Validasi data (termasuk username yang sebelumnya tidak ada)
        $validatedData = $request->validate([
            'nama_penyewa'  => 'required|max:255',
            'username'      => 'required|min:3|max:255|unique:akuns', // Validasi username
            'jenis_kelamin' => 'required',
            'no_hp'         => 'required|min:10|max:15',
            'email'         => 'required|email:dns|unique:penyewas',
            'password'      => 'required|min:5|max:255'
        ]);

        DB::beginTransaction();

        try {
            // 3. Simpan data ke tabel 'akuns'
            $akun = Akun::create([
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
                'jenis_akun' => 'penyewa' // Set jenis akun secara manual
            ]);

            // 4. Simpan data ke tabel 'penyewas'
            Penyewa::create([
                'username' => $validatedData['username'], // Pastikan sama dengan akun
                'nama_penyewa' => $validatedData['nama_penyewa'],
                'no_hp' => $validatedData['no_hp'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'email' => $validatedData['email']
            ]);

            // 5. Jika semua berhasil, konfirmasi perubahan
            DB::commit();

        } catch (\Exception $e) {
            // 6. Jika terjadi error, batalkan semua perubahan
            DB::rollBack();

            // Kembalikan ke form dengan pesan error
            return back()->withInput()->withErrors(['registrasi' => 'Terjadi kesalahan saat pendaftaran. Silakan coba lagi.']);
        }

        // 5. Arahkan pengguna ke halaman dashboard booking
        return redirect('/');
    }
}
