<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; //
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller //
{
    /**
     * Menampilkan halaman/form login.
     */
    public function index() // <-- 2. Method untuk route GET
    {
        return view('login'); // <-- Mengarahkan ke file login.blade.php
    }

    /**
     * Menangani proses autentikasi (login).
     */
    public function store(Request $request) // <-- 3. Method untuk route POST
    {
        // Validasi input dari form
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Mencoba untuk melakukan login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Jika berhasil, arahkan ke halaman dashboard (atau halaman lain)
            return redirect()->intended('/dashboard');
        }

        // Jika gagal, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau Password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }
}
