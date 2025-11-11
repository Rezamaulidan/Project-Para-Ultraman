<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PemilikKos;
use App\Models\Staf;

class AuthController extends Controller
{
    /**
     * Memproses permintaan login dari form.
     * Mencoba login sebagai Pemilik Kos, lalu sebagai Staf.
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi Input Form (username dan password wajib diisi)
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $credentials['username'];
        $password = $credentials['password'];

        $loggedInUser = null;
        $role = null;

        // --- 2. Coba Login Sebagai PEMILIK KOS ---
        $pemilik = PemilikKos::where('username', $username)->first();

        if ($pemilik && Hash::check($password, $pemilik->password)) {
            $loggedInUser = $pemilik;
            $role = 'pemilik_kos';
        }

        // --- 3. Coba Login Sebagai STAF ---
        if (!$loggedInUser) {
            $staf = Staf::where('username', $username)->first();

            if ($staf && Hash::check($password, $staf->password)) {
                $loggedInUser = $staf;
                $role = 'staff';
            }
        }

        // --- 4. Proses Login Jika Berhasil Ditemukan ---
        if ($loggedInUser) {
            Auth::login($loggedInUser);
            $request->session()->regenerate();

            // Pengalihan Berdasarkan Peran
            if ($role === 'staff') {
                // PENGALIHAN BERHASIL KE MENU STAFF
                return redirect()->intended(route('staff.menu'));
            } elseif ($role === 'pemilik_kos') {
                // Jika Pemilik Kos login (karena rute menu pemilik dihapus),
                // kita tolak aksesnya untuk sementara.
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Akses Pemilik Kos ditangguhkan sementara.',
                ])->onlyInput('username');
            }
        }

        // 5. Jika Login Gagal (Username tidak ditemukan atau password salah)
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
