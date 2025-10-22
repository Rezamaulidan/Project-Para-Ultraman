<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman/form login.
     */
    public function index()
    {
        return view('login');
    }

    /**
     * Menangani proses autentikasi (login).
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Arahkan ke dashboard booking setelah login berhasil
            return redirect()->route('dashboard.booking'); // <-- SEPERTI INI
        }

        return back()->withErrors([
            'username' => 'Username atau Password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Menangani proses logout.
     *
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout(); // Mengakhiri sesi pengguna

        $request->session()->invalidate(); // Membatalkan data sesi

        $request->session()->regenerateToken(); // Membuat ulang token keamanan

        // Arahkan kembali ke halaman home setelah logout
        return redirect('/'); // <-- Kembali ke view home.blade
    }
}
