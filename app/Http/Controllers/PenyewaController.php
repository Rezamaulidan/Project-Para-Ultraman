<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use App\Models\Kamar;
use App\Models\Akun; // Tambahkan Model Akun
use App\Models\LaporanKeamanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan Storage
use Illuminate\Support\Facades\Hash;    // Tambahkan Hash

class PenyewaController extends Controller
{
    // --- FITUR UTAMA PENYEWA ---

    public function updateInformasi(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama_penyewa'  => 'required|string|max:100',
            'no_hp'         => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'foto_profil'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Ambil Data User Login
        $user = Auth::user();

        // Ambil data penyewa dari tabel 'penyewas'
        $penyewa = Penyewa::where('username', $user->username)->firstOrFail();

        // Ambil data akun dari tabel 'akuns' (untuk password)
        $akun = Akun::where('username', $user->username)->firstOrFail();

        // 3. Update Data Teks
        $penyewa->nama_penyewa  = $request->nama_penyewa;
        $penyewa->no_hp         = $request->no_hp;
        $penyewa->jenis_kelamin = $request->jenis_kelamin;

        // 4. Handle Upload Foto Profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada (dan bukan default/kosong)
            if ($penyewa->foto_profil && Storage::disk('public')->exists($penyewa->foto_profil)) {
                Storage::disk('public')->delete($penyewa->foto_profil);
            }

            // Simpan foto baru
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $penyewa->foto_profil = $path;
        }

        // 5. Handle Ganti Password (Jika diisi)
        if ($request->filled('password')) {
            $akun->password = Hash::make($request->password);
            $akun->save();
        }

        // 6. Simpan Perubahan Data Penyewa
        $penyewa->save();

        // Mengarah ke route 'penyewa.informasi' (Halaman Lihat Profil)
        return redirect()->route('penyewa.informasi')->with('success', 'Profil berhasil diperbarui!');
    }

    // --- METHOD VIEW LAINNYA (TETAP SAMA) ---

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
        $penyewa = Penyewa::where('username', Auth::user()->username)->firstOrFail();
        // HARDCODE ID 1 Sesuai Request
        $kamar = Kamar::where('no_kamar', '1')->firstOrFail();

        return view('informasi_kamar_penyewa', ['kamar' => $kamar]);
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
