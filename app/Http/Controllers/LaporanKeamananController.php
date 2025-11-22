<?php

namespace App\Http\Controllers;

use App\Models\LaporanKeamanan;
use App\Models\Staf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanKeamananController extends Controller
{
    public function index()
    {
        // Ambil data laporan urut dari yang terbaru
        $laporans = LaporanKeamanan::with('staf')
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('laporan_keamanan_staf', compact('laporans'));
    }

    public function create()
    {
        return view('tambah_laporan_staf');
    }

    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $request->validate([
            'judul'          => 'required|string|max:200',
            'tanggal'        => 'required|date',
            'waktu'          => 'required',
            'jenis_kejadian' => 'required',
            'lokasi'         => 'required',
            'deskripsi'      => 'required',
        ]);

        // 2. Ambil ID Staf dari user yang login
        $user = Auth::user();
        $staf = Staf::where('username', $user->username)->first();

        if (!$staf) {
            return back()->withErrors(['error' => 'Data staf tidak ditemukan.']);
        }

        // 3. GABUNGKAN DATA (Logika Struktur Lama)
        // Menggabungkan waktu, jenis, lokasi, dan deskripsi menjadi satu string
        $keteranganLengkap = "Pukul: " . $request->waktu . "\n" .
                             "Jenis Kejadian: " . $request->jenis_kejadian . "\n" .
                             "Lokasi: " . $request->lokasi . "\n" .
                             "Detail:\n" . $request->deskripsi;

        // 4. Simpan ke Database
        LaporanKeamanan::create([
            'id_staf'       => $staf->id_staf,
            'judul_laporan' => $request->judul,
            'tanggal'       => $request->tanggal,
            'keterangan'    => $keteranganLengkap, // Simpan hasil gabungan di sini
        ]);

        return redirect()->route('staff.laporan_keamanan')->with('success', 'Laporan berhasil ditambahkan!');
    }
}
