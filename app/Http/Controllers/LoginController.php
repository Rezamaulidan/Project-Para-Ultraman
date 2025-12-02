<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Booking;

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
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
            $user = Auth::user();

            // 3. Cek Role dan Redirect
            if ($user->jenis_akun === 'pemilik') {
                return redirect()->route('pemilik.home');
            }
            elseif ($user->jenis_akun === 'staf') {
                return redirect()->route('staff.menu');
            }
            elseif ($user->jenis_akun === 'penyewa') {
                // Agar user yang telat bayar tetap diarahkan ke Dashboard (untuk bayar), bukan disuruh cari kamar baru.
                $hasActiveBooking = Booking::where('username', $user->username)
                                    ->whereIn('status_booking', ['pending', 'confirmed', 'lunas', 'terlambat', 'rejected'])
                                    ->exists();

                if ($hasActiveBooking) {
                    // Jika punya kamar (termasuk yang telat bayar) -> Ke Dashboard Saya
                    return redirect()->route('penyewa.dashboard');
                } else {
                    // Benar-benar pengguna baru (belum pernah booking sama sekali)
                    return redirect()->route('dashboard.booking');
                }
            }

            return redirect()->route('home');
        }

        // 4. Jika Gagal Login
        return back()->withErrors([
            'username' => 'Username atau Password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $redirectRoute = 'home';

        if ($user) {
            if ($user->jenis_akun === 'pemilik' || $user->jenis_akun === 'staf') {
                $redirectRoute = 'login';
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectRoute);
    }
}
