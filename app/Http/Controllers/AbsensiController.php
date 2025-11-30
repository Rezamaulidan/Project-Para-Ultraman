<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staf; // Pastikan Model Staf sudah ada
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // 1. Tampilkan Halaman Shift Kerja
    public function index()
    {
        return view('shift_kerja_staf');
    }

    // 2. Proses Logika Absen (Sesuai Narasimu)
    public function store(Request $request)
    {
        // Tangkap inputan dari form
        $id_staf_input = $request->id_staf;
        $shift_pilihan = $request->shift; // Pagi, Siang, atau Malam

        // --- TAHAP 1: Cari Staff berdasarkan ID ---
        $staf = Staf::where('id_staf', $id_staf_input)->first();

        // Jika ID tidak ditemukan
        if (!$staf) {
            return back()->with('error', 'ID Staf tidak ditemukan. Apakah Anda karyawan sini?');
        }

        // --- TAHAP 2: Cek Apakah Sudah Absen Hari Ini? ---
        $sudah_absen = DB::table('absensi')
                        ->where('id_staf', $id_staf_input)
                        ->whereDate('tanggal', Carbon::today())
                        ->exists();

        if ($sudah_absen) {
            return back()->with('error', "Halo $staf->nama_staf, Anda sudah absen hari ini!");
        }

        // --- TAHAP 3: Verifikasi Jadwal (Ini Logika Utama Kamu) ---
        // Kita bandingkan: Shift yang diklik VS Jadwal di Database
        // Catatan: Pastikan tulisan di database ('pagi') sama dengan value tombol ('pagi')

        // Ubah jadi huruf kecil semua biar aman (Pagi == pagi)
        $jadwal_asli = strtolower($staf->jadwal);
        $shift_klik  = strtolower($shift_pilihan);

        if ($jadwal_asli != $shift_klik) {
            // Jika beda, tolak!
            return back()->with('error', "Maaf $staf->nama_staf, jadwal Anda adalah SHIFT $jadwal_asli, tapi Anda memilih SHIFT $shift_klik.");
        }

        // --- TAHAP 4: Jika Lolos Semua, Simpan ke Database ---
        DB::table('absensi')->insert([
            'id_staf'       => $staf->id_staf,
            'tanggal'       => Carbon::now()->toDateString(),
            'jam_masuk'     => Carbon::now()->toTimeString(),
            'shift_pilihan' => $shift_pilihan,
            'status'        => 'Hadir',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return back()->with('sukses', "Berhasil! Selamat bekerja, $staf->nama_staf.");
    }
}
