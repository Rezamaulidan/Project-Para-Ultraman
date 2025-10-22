<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Jangan lupa import model User jika Anda akan menyimpan data
// use App\Models\User;

class RegisterController extends Controller
{
    // Method untuk menampilkan view (sudah ada)
    public function pilihan()
    {
        return view('register');
    }

    // WAJIB: Method untuk menyimpan data registrasi
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari form
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required|min:10|max:15',
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:5|max:255'
        ]);

        // Jika Anda ingin mengenkripsi password sebelum disimpan (sangat disarankan)
        // $validatedData['password'] = bcrypt($validatedData['password']);

        // 2. Logika untuk menyimpan data ke database
        // User::create($validatedData);

        // Untuk sekarang, kita coba tampilkan datanya saja untuk memastikan berhasil
        // dd('Registrasi Berhasil!', $validatedData);

        // 3. Arahkan pengguna ke halaman lain (misal: halaman login) dengan pesan sukses
        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
