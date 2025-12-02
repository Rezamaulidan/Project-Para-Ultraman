<?php

namespace App\Http\Controllers;

use App\Models\LaporanKeamanan;
use App\Models\Staf;
use Illuminate\Http\Request;

class LaporanKeamananController extends Controller
{
    public function index()
    {
        $laporans = LaporanKeamanan::with('staf')
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('laporan_keamanan_staf', compact('laporans'));
    }

    public function create()
    {
        // 🛑 1. CEK SESSION PRESENSI
        // Apakah ada ID staf yang tersimpan di sesi browser ini?
        if (!session()->has('current_staf_id')) {
            return redirect()->route('staff.shift_kerja')
                ->with('error', 'Akses Ditolak! Anda harus melakukan Presensi/Absen terlebih dahulu sebelum membuat laporan.');
        }

        // 🛑 2. AMBIL DATA STAF DARI SESSION
        $idAktif = session('current_staf_id');
        $stafAktif = Staf::find($idAktif);

        // Validasi jika data staf terhapus tapi sesi masih nyangkut
        if (!$stafAktif) {
            session()->forget('current_staf_id');
            return redirect()->route('staff.shift_kerja')->with('error', 'Data sesi tidak valid. Silakan absen ulang.');
        }

        // Kirim data staf AKTIF (Single Object) ke view, bukan daftar semua staf
        return view('tambah_laporan_staf', compact('stafAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_staf'        => 'required|integer',
            'judul'          => 'required|string|max:200',
            'tanggal'        => 'required|date',
            'waktu'          => 'required',
            'jenis_kejadian' => 'required',
            'lokasi'         => 'required',
            'deskripsi'      => 'required',
        ]);

        // 🛑 3. KEAMANAN DATA
        // Pastikan ID yang dikirim form SAMA dengan orang yang sedang login (Session)
        if ($request->id_staf != session('current_staf_id')) {
             return back()->withErrors(['error' => 'Sesi tidak valid atau kadaluarsa. Mohon presensi ulang.']);
        }

        $keteranganLengkap = "Pukul: " . $request->waktu . "\n" .
                             "Jenis Kejadian: " . $request->jenis_kejadian . "\n" .
                             "Lokasi: " . $request->lokasi . "\n" .
                             "Detail:\n" . $request->deskripsi;

        LaporanKeamanan::create([
            'id_staf'       => $request->id_staf,
            'judul_laporan' => $request->judul,
            'tanggal'       => $request->tanggal,
            'keterangan'    => $keteranganLengkap,
        ]);

        return redirect()->route('staff.laporan_keamanan')->with('success', 'Laporan berhasil ditambahkan!');
    }
}
