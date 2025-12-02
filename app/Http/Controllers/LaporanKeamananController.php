<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Wajib import DB agar bisa pakai DB::table
use Carbon\Carbon;

class LaporanKeamananController extends Controller
{
    // 1. Menampilkan Halaman Daftar Laporan
    public function index()
    {
        // Mengambil data laporan dan menggabungkannya dengan tabel staf untuk dapat nama
        $laporans = DB::table('laporan_keamanans')
            ->join('stafs', 'laporan_keamanans.id_staf', '=', 'stafs.id_staf')
            ->select('laporan_keamanans.*', 'stafs.nama_staf')
            ->orderBy('laporan_keamanans.created_at', 'desc')
            ->get();

        // Mengirim data ke View 'laporan_keamanan_staf.blade.php'
        return view('laporan_keamanan_staf', compact('laporans'));
    }

    // 2. Menampilkan Form Tambah Laporan
    public function create()
    {
        // Mengambil daftar staf untuk pilihan dropdown
        $daftarStaf = DB::table('stafs')
            ->select('id_staf', 'nama_staf', 'jadwal')
            ->get();

        return view('tambah_laporan_staf', compact('daftarStaf'));
    }

    // 3. Menyimpan Data (BAGIAN INI YANG DIPERBAIKI)
    public function store(Request $request)
    {
        // A. Validasi Input (Wajib diisi semua)
        $request->validate([
            'id_staf'        => 'required|exists:stafs,id_staf',
            'judul'          => 'required|string|max:200',
            'tanggal'        => 'required|date',
            // Data detail tetap divalidasi agar user tidak mengosongkannya
            'waktu'          => 'required',
            'jenis_kejadian' => 'required',
            'lokasi'         => 'required',
            'deskripsi'      => 'required',
        ]);

        // B. GABUNGKAN DATA (Solusi agar tidak error 'Column Not Found')
        // Karena di database kamu tidak ada kolom waktu/lokasi, kita gabung jadi satu teks
        // lalu kita simpan ke kolom 'keterangan'.
        $keterangan_lengkap = "Waktu Kejadian: " . $request->waktu . "\n" .
                              "Lokasi: " . $request->lokasi . "\n" .
                              "Jenis Kejadian: " . $request->jenis_kejadian . "\n\n" .
                              "Detail Kronologi:\n" . $request->deskripsi;

        // C. Simpan ke Database
        DB::table('laporan_keamanans')->insert([
            'id_staf'       => $request->id_staf,

            // Input 'judul' dari form masuk ke kolom 'judul_laporan' di DB
            'judul_laporan' => $request->judul,

            // Input 'tanggal' dari form masuk ke kolom 'tanggal' di DB
            'tanggal'       => $request->tanggal,

            // PENTING: Semua data detail tadi masuk ke kolom 'keterangan'
            'keterangan'    => $keterangan_lengkap,

            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // D. Redirect kembali ke halaman daftar laporan
        return redirect()->route('staff.laporan_keamanan')
            ->with('sukses', 'Laporan berhasil ditambahkan!');
    }
}
