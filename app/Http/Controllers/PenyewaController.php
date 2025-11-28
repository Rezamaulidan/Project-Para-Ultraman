<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use App\Models\Kamar;
use App\Models\Akun;
use App\Models\Booking;
use App\Models\LaporanKeamanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class PenyewaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // 1. Ambil SEMUA data booking
        $allBookings = Booking::with('kamar')
                        ->where('username', $user->username)
                        ->whereIn('status_booking', ['pending', 'confirmed', 'lunas', 'rejected'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        // 2. Cek apakah user ini sudah punya setidaknya SATU booking yang 'lunas'
        $sudahPunyaKamarAktif = $allBookings->contains('status_booking', 'lunas');

        // 3. Lakukan Filtering Data
        if ($sudahPunyaKamarAktif) {
            // Jika sudah punya kamar aktif, sembunyikan history rejected
            $bookingsSaya = $allBookings->filter(function ($booking) {
                return $booking->status_booking !== 'rejected';
            });
        } else {
            // Jika belum punya kamar aktif (masih pending/rejected semua), tampilkan semua
            $bookingsSaya = $allBookings;
        }

        // PERUBAHAN: Kirim variabel $sudahPunyaKamarAktif ke view
        return view('dashboard_penyewa', compact('bookingsSaya', 'sudahPunyaKamarAktif'));
    }

    public function updateInformasi(Request $request)
    {
        $request->validate([
            'nama_penyewa'  => 'required|string|max:100',
            'no_hp'         => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'foto_profil'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $penyewa = Penyewa::where('username', $user->username)->firstOrFail();
        $akun = Akun::where('username', $user->username)->firstOrFail();

        $penyewa->nama_penyewa  = $request->nama_penyewa;
        $penyewa->no_hp         = $request->no_hp;
        $penyewa->jenis_kelamin = $request->jenis_kelamin;

        if ($request->hasFile('foto_profil')) {
            if ($penyewa->foto_profil && Storage::disk('public')->exists($penyewa->foto_profil)) {
                Storage::disk('public')->delete($penyewa->foto_profil);
            }
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $penyewa->foto_profil = $path;
        }

        if ($request->filled('password')) {
            $akun->password = Hash::make($request->password);
            $akun->save();
        }

        $penyewa->save();

        return redirect()->route('penyewa.informasi')->with('success', 'Profil berhasil diperbarui!');
    }

    public function showKeamanan()
    {
        $laporans = LaporanKeamanan::with('staf')
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('informasi_keamanan_penyewa', compact('laporans'));
    }

    public function showPembayaran()
    {
        $penyewa = Penyewa::where('username', Auth::user()->username)->firstOrFail();
        return view('menu_pembayaran', ['penyewa' => $penyewa]);
    }

    public function showKamar()
    {
        $user = Auth::user();
        // Cari booking aktif (lunas/confirmed) terakhir
        $bookingTerakhir = Booking::where('username', $user->username)
                            ->whereIn('status_booking', ['confirmed', 'lunas'])
                            ->latest()
                            ->first();

        if ($bookingTerakhir) {
            $kamar = Kamar::where('no_kamar', $bookingTerakhir->no_kamar)->firstOrFail();
            return view('informasi_kamar_penyewa', ['kamar' => $kamar]);
        }

        return redirect()->route('dashboard.booking')->with('error', 'Anda belum memiliki kamar aktif.');
    }

    public function showInformasi()
    {
        $penyewa = Penyewa::where('username', Auth::user()->username)->firstOrFail();
        return view('informasi_penyewa', ['penyewa' => $penyewa]);
    }

    public function editInformasi()
    {
        $penyewa = Penyewa::where('username', Auth::user()->username)->firstOrFail();
        return view('edit_informasi_penyewa', ['penyewa' => $penyewa]);
    }
}