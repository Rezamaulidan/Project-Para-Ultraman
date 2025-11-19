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
        // 1. Validasi input username dan password
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $authField = [
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ];

        // 2. Coba lakukan login menggunakan Auth::attempt yang sudah dimodifikasi
        if (Auth::attempt($authField)) {

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

                return redirect()->route('staff.menu');
            }

            return redirect('/');

        }

        // 6. Jika login gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau Password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout(); // Mengakhiri sesi pengguna
        $request->session()->invalidate(); // Membatalkan data sesi
        $request->session()->regenerateToken(); // Membuat ulang token keamanan
        return redirect('/'); // <-- Kembali ke view home.blade
    }
}
