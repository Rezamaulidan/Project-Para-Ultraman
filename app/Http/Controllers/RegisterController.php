<?php

namespace App\Http\Controllers;

use App\Models\User; // <-- 1. Import model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- 2. Import Hash untuk enkripsi password
use Illuminate\Support\Facades\Auth; // <-- 3. Import Auth untuk login otomatis

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
            'name'          => 'required|max:255',
            'username'      => 'required|min:3|max:255|unique:users', // Validasi username
            'jenis_kelamin' => 'required',
            'no_hp'         => 'required|min:10|max:15',
            'email'         => 'required|email:dns|unique:users',
            'password'      => 'required|min:5|max:255'
        ]);

        // 2. Enkripsi password sebelum disimpan
        $validatedData['password'] = Hash::make($validatedData['password']);

        // 3. Simpan data pengguna baru ke database
        $user = User::create($validatedData);

        // 4. Login pengguna yang baru saja dibuat secara otomatis
        Auth::login($user);

        // 5. Arahkan pengguna ke halaman dashboard booking
        return redirect('/');
    }
}
