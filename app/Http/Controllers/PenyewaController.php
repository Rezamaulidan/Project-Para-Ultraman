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
use Illuminate\Support\Facades\DB; // Import DB

class PenyewaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // 1. Ambil SEMUA data booking
        $allBookings = Booking::with('kamar')
                        ->where('username', $user->username)
                        ->whereIn('status_booking', ['pending', 'confirmed', 'lunas', 'rejected', 'terlambat'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        // 2. Cek status aktif (Lunas ATAU Terlambat)
        $sudahPunyaKamarAktif = $allBookings->contains(function ($booking) {
            return in_array($booking->status_booking, ['lunas', 'terlambat']);
        });

        // 3. Filtering Data
        if ($sudahPunyaKamarAktif) {
            $bookingsSaya = $allBookings->filter(function ($booking) {
                return $booking->status_booking !== 'rejected';
            });
        } else {
            $bookingsSaya = $allBookings;
        }

        return view('dashboard_penyewa', compact('bookingsSaya', 'sudahPunyaKamarAktif'));
    }

    // 🛑 UPDATE: MENERAPKAN OPSI A (Sinkronisasi Email & Transaction)
    public function updateInformasi(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_penyewa'  => 'required|string|max:100',
            'no_hp'         => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            // Validasi email unik di tabel 'akuns', kecuali milik sendiri
            'email'         => 'required|email|unique:akuns,email,' . $user->username . ',username',
            'foto_profil'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'      => 'nullable|min:6',
        ]);

        DB::beginTransaction(); // Mulai Transaksi

        try {
            $penyewa = Penyewa::where('username', $user->username)->firstOrFail();
            $akun = Akun::where('username', $user->username)->firstOrFail();

            // 1. Update Data Profil (Tabel Penyewa)
            $penyewa->nama_penyewa  = $request->nama_penyewa;
            $penyewa->no_hp         = $request->no_hp;
            $penyewa->jenis_kelamin = $request->jenis_kelamin;
            $penyewa->email         = $request->email; // Update email profil

            if ($request->hasFile('foto_profil')) {
                if ($penyewa->foto_profil && Storage::disk('public')->exists($penyewa->foto_profil)) {
                    Storage::disk('public')->delete($penyewa->foto_profil);
                }
                $path = $request->file('foto_profil')->store('foto_profil', 'public');
                $penyewa->foto_profil = $path;
            }
            $penyewa->save();

            // 2. Update Data Login (Tabel Akun)
            $akun->email = $request->email; // Update email login (PENTING)

            if ($request->filled('password')) {
                $akun->password = Hash::make($request->password);
            }
            $akun->save();

            DB::commit(); // Simpan perubahan

            return redirect()->route('penyewa.informasi')->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika error
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
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
        $user = Auth::user();
        $penyewa = Penyewa::where('username', $user->username)->firstOrFail();

        $booking = Booking::with('kamar')
                    ->where('username', $user->username)
                    ->whereIn('status_booking', ['confirmed', 'lunas', 'terlambat'])
                    ->latest()
                    ->first();

        if (!$booking) {
            return redirect()->route('dashboard.booking')->with('error', 'Anda tidak memiliki tagihan aktif.');
        }

        return view('menu_pembayaran', compact('penyewa', 'booking'));
    }

    public function showKamar()
    {
        $user = Auth::user();
        $bookingTerakhir = Booking::where('username', $user->username)
                            ->whereIn('status_booking', ['confirmed', 'lunas', 'terlambat'])
                            ->latest()
                            ->first();

        if ($bookingTerakhir) {
            $kamar = Kamar::where('no_kamar', $bookingTerakhir->no_kamar)->firstOrFail();
            return view('informasi_kamar_penyewa', ['kamar' => $kamar]);
        }

        return redirect()->route('dashboard.booking')->with('error', 'Anda belum memiliki kamar aktif.');
    }

    // 🛑 UPDATE: MENGIRIM DATA BOOKING UNTUK TAMPILKAN NO KAMAR
    public function showInformasi()
    {
        $user = Auth::user();
        $penyewa = Penyewa::where('username', $user->username)->firstOrFail();

        // Cari booking aktif (Lunas, Terlambat, atau Confirmed)
        // Agar kita bisa ambil nomor kamarnya
        $bookingAktif = Booking::where('username', $user->username)
                        ->whereIn('status_booking', ['lunas', 'terlambat', 'confirmed'])
                        ->latest()
                        ->first();

        // Kirim $penyewa DAN $bookingAktif ke view
        return view('informasi_penyewa', compact('penyewa', 'bookingAktif'));
    }

    public function editInformasi()
    {
        $penyewa = Penyewa::where('username', Auth::user()->username)->firstOrFail();
        return view('edit_informasi_penyewa', ['penyewa' => $penyewa]);
    }
}
