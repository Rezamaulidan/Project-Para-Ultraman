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
        // 1. Validasi input username dan password
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // 2. melakukan login HANYA dengan username & password
        if (Auth::attempt($credentials)) {

            // 3. Jika berhasil, regenerate session
            $request->session()->regenerate();

            // 4. Dapatkan data user (model Akun) yang sedang login
            $user = Auth::user();

            // 5. Cek jenis_akun dan arahkan ke rute yang sesuai
            if ($user->jenis_akun === 'pemilik') {

                // Jika 'pemilik', arahkan ke home pemilik
                return redirect()->route('pemilik.home');

            } elseif ($user->jenis_akun === 'penyewa') {

                // Jika 'penyewa', arahkan ke dashboard booking
                return redirect()->route('dashboard.booking');

            } elseif ($user->jenis_akun === 'staf') {

                // Jika 'staf', arahkan ke menu staf
                return redirect()->route('staff.menu');
            }

            // Fallback jika ada jenis akun lain
            return redirect()->route('home');

        }

        // 6. Jika login gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau Password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request): RedirectResponse
    {
        // 1. Ambil data pengguna (termasuk jenis_akun) SEBELUM logout
        $user = Auth::user();

        // 2. Lakukan proses logout standar
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 3. Periksa jenis_akun dari pengguna yang baru saja logout
        if ($user && ($user->jenis_akun === 'pemilik' || $user->jenis_akun === 'staf')) {

            // 4. Jika pemilik atau staf, arahkan ke halaman login
            return redirect()->route('login');
        }

        // 5. Jika penyewa (atau peran lain), arahkan ke halaman home
        return redirect()->route('home');
    }
}
