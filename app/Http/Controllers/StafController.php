<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Staf;
use App\Models\Penyewa;
use App\Models\Booking;

class StafController extends Controller
{
    // --- 1. MANAJEMEN PROFIL & DAFTAR REKAN KERJA ---

    public function indexManajemen()
    {
        $stafs = Staf::all();
        return view('pilih_staf', compact('stafs'));
    }

    public function lihatProfil($id)
    {
        $staf = Staf::findOrFail($id);
        return view('detail_profil_staf', compact('staf'));
    }

    public function editProfil($id)
    {
        $staf = Staf::findOrFail($id);
        return view('edit_profil_staf', compact('staf'));
    }

    public function updateProfil(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'no_hp'        => 'required|string|max:20',
            // Email unik di tabel staf, kecuali milik diri sendiri
            'email'        => 'required|email|max:100|unique:stafs,email,'.$id.',id_staf',
            'foto_staf'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Ambil Data Staf
        $staf = Staf::findOrFail($id);

        // 3. Update Data Teks
        $staf->nama_staf = $request->nama_lengkap;
        $staf->no_hp     = $request->no_hp;
        $staf->email     = $request->email;

        // 4. Update Foto
        if ($request->hasFile('foto_staf')) {
            if ($staf->foto_staf && Storage::disk('public')->exists($staf->foto_staf)) {
                Storage::disk('public')->delete($staf->foto_staf);
            }
            $path = $request->file('foto_staf')->store('foto_staf', 'public');
            $staf->foto_staf = $path;
        }
        $staf->save();

        return redirect()->route('staff.lihat_profil', ['id' => $staf->id_staf])
                         ->with('sukses', 'Profil berhasil diperbarui!');
    }

    // --- DAFTAR PENYEWA ---
    public function daftarPenyewa()
    {
        // 1. Dapatkan daftar username penyewa yang pernah/sedang bertransaksi
        $penyewaUsernamesRelevan = Booking::whereIn('status_booking', ['lunas', 'terlambat'])
                                          ->pluck('username')
                                          ->unique()
                                          ->toArray();

        // 2. Ambil data Penyewa berdasarkan username yang relevan tersebut
        $daftar_penyewa = Penyewa::whereIn('username', $penyewaUsernamesRelevan)->get();

        // 3. Kirim ke view
        return view('staff_informasi_penyewa', compact('daftar_penyewa'));
    }

    // =================================================
    // 3. FITUR ABSENSI
    // =================================================

    public function absenIndex()
    {
        $daftarStaf = Staf::all();
        return view('shift_kerja_staf', compact('daftarStaf'));
    }

    public function absenStore(Request $request)
    {
        $request->validate([
            'id_staf' => 'required|exists:stafs,id_staf',
        ]);

        $id_staf_input = $request->id_staf;
        $staf = Staf::where('id_staf', $id_staf_input)->first();

        if (!$staf) {
            return back()->with('error', 'Data staf tidak ditemukan.');
        }

        $shift_sekarang = ucfirst($staf->jadwal);
        $sessionKey = 'absen_' . $staf->id_staf . '_' . Carbon::today()->format('Y-m-d');

        // PENTING: SET SESSION IDENTITAS PELAPOR untuk membuat laporan keamanan
        session()->put('current_staf_id', $staf->id_staf);

        // Cek apakah sudah absen hari ini?
        if (session()->has($sessionKey)) {
            return back()->with('error', "Halo $staf->nama_staf, Anda sudah melakukan absensi hari ini! (Sesi Aktif)");
        }

        // Simpan ke SESSION Browser (Logika Harian)
        session()->put($sessionKey, [
            'jam'    => Carbon::now()->toTimeString(),
            'shift'  => $shift_sekarang,
            'status' => 'Hadir'
        ]);

        return back()->with('sukses', "Berhasil Check-in! Selamat bekerja, $staf->nama_staf (Shift: $shift_sekarang).");
    }
}
