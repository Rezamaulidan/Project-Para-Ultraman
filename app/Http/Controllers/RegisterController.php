<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
        // 1. Validasi data
        $validatedData = $request->validate([
            'nama_penyewa'  => 'required|max:255',
            'username'      => 'required|min:3|max:255|unique:akuns', // Cek unik di tabel akun
            'jenis_kelamin' => 'required',
            'no_hp'         => 'required|min:10|max:15',
            'email'         => 'required|email:dns|unique:akuns',

            'password'      => 'required|min:5|max:255'
        ]);

        DB::beginTransaction();

        try {
            // 2. Simpan data ke tabel 'akuns' (Pusat Login)
            $akun = Akun::create([
                'username'   => $validatedData['username'],
                'password'   => Hash::make($validatedData['password']),
                'jenis_akun' => 'penyewa',
                'email'      => $validatedData['email']
            ]);

            // 3. Simpan data ke tabel 'penyewas' (Profil)
            Penyewa::create([
                'username'      => $validatedData['username'],
                'nama_penyewa'  => $validatedData['nama_penyewa'],
                'no_hp'         => $validatedData['no_hp'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'email'         => $validatedData['email'] // Simpan juga di profil sebagai backup
            ]);

            // 4. Konfirmasi perubahan database
            DB::commit();

            // Redirect ke Dashboard Penyewa (Karena login berhasil)
            return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang.');

        } catch (\Exception $e) {
            // 6. Batalkan jika error
            DB::rollBack();

            // Kembalikan ke form dengan pesan error
            return back()->withInput()->withErrors(['registrasi' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
