<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // 2. Lakukan Login
        // Auth::attempt akan otomatis mengecek username & password ke tabel users/akuns
        if (Auth::attempt($credentials)) {

            // 3. Regenerasi Session (Keamanan)
            $request->session()->regenerate();

            // 4. Ambil data user login
            $user = Auth::user();

            // 5. Cek Role/Jenis Akun dan Redirect
            if ($user->jenis_akun === 'pemilik') {
                return redirect()->route('pemilik.home');
            }
            elseif ($user->jenis_akun === 'penyewa') {
                return redirect()->route('dashboard.booking');
            }
            elseif ($user->jenis_akun === 'staf') {
                return redirect()->route('staff.menu');
            }

            // Fallback jika role tidak dikenali
            return redirect()->route('home');
        }

        // 6. Jika Gagal Login
        return back()->withErrors([
            'username' => 'Username atau Password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect khusus untuk pemilik/staf ke halaman login
        if ($user && ($user->jenis_akun === 'pemilik' || $user->jenis_akun === 'staf')) {
            return redirect()->route('login');
        }

        // Default redirect ke home
        return redirect()->route('home');
    }
}
