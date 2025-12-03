<?php

namespace App\Http\Controllers;

use App\Models\LaporanKeamanan;
use App\Models\Staf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Wajib import DB untuk transaction (jika ada)

class LaporanKeamananController extends Controller
{
    // 1. Menampilkan Halaman Daftar Laporan
    public function index()
    {
        // Menggunakan Model Eloquent untuk preload relasi 'staf'
        $laporans = LaporanKeamanan::with('staf')
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('laporan_keamanan_staf', compact('laporans'));
    }

    // 2. Menampilkan Form Tambah Laporan (Terkunci oleh Presensi)
    public function create()
    {
        // 🛑 1. CEK SESSION PRESENSI
        // Jika tidak ada ID staf di sesi, tendang ke halaman presensi
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

        // Kirim data staf AKTIF (Single Object) ke view
        return view('tambah_laporan_staf', compact('stafAktif'));
    }

    // 3. Menyimpan Data
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

        // 🛑 KEAMANAN DATA: Pastikan ID yang dikirim form SAMA dengan ID di session
        if ($request->id_staf != session('current_staf_id')) {
             return back()->withErrors(['error' => 'Sesi tidak valid atau kadaluarsa. Mohon presensi ulang.']);
        }

        // Gabungkan semua detail ke dalam satu kolom 'keterangan'
        $keteranganLengkap = "Pukul: " . $request->waktu . "\n" .
                             "Jenis Kejadian: " . $request->jenis_kejadian . "\n" .
                             "Lokasi: " . $request->lokasi . "\n" .
                             "Detail:\n" . $request->deskripsi;

        // Simpan ke Database menggunakan Model Eloquent
        LaporanKeamanan::create([
            'id_staf'       => $request->id_staf,
            'judul_laporan' => $request->judul,
            'tanggal'       => $request->tanggal,
            'keterangan'    => $keteranganLengkap,
        ]);

        return redirect()->route('staff.laporan_keamanan')
            ->with('sukses', 'Laporan berhasil ditambahkan!');
    }
}
