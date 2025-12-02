<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Pastikan model Staf di-import (opsional jika pakai DB::table terus)
use App\Models\Staf;

class AbsensiController extends Controller
{
    // Menampilkan Halaman Absen
    public function index()
    {
        $today = Carbon::today();

        $daftar_hadir = DB::table('absensi')
            ->join('stafs', 'absensi.id_staf', '=', 'stafs.id_staf')
            ->whereDate('absensi.tanggal', $today)
            ->select('stafs.nama_staf', 'absensi.shift', 'absensi.jam_masuk', 'absensi.jam_pulang')
            ->orderBy('absensi.jam_masuk', 'desc')
            ->get();

        // Mengirim data ke View
        return view('staff_shift_kerja', compact('daftar_hadir'));
    }

    // Memproses Absen
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'id_staf' => 'required|exists:stafs,id_staf',
            'shift'   => 'required|in:Pagi,Malam', // Validasi input shift
        ], [
            'id_staf.exists' => 'ID Staff tidak ditemukan!',
            'shift.required' => 'Wajib memilih Shift Pagi atau Malam!'
        ]);

        $id_staf = $request->id_staf;
        $aksi    = $request->aksi;
        $shift   = $request->shift; // Ambil nilai dari radio button
        $now     = Carbon::now();

        // 2. Ambil Data Staf untuk pengecekan Jadwal
        $staf = DB::table('stafs')->where('id_staf', $id_staf)->first();

        // LOGIKA Validasi Jadwal (Opsional)
        // Jika Staf jadwalnya 'Pagi' tapi pilih 'Malam', akan ditolak.
        if ($staf->jadwal != $shift) {
             return back()->with('error', "Gagal! Jadwal Anda terdaftar sebagai Shift $staf->jadwal. Anda tidak bisa memilih Shift $shift.");
        }

        // 3. Cek apakah sudah absen hari ini?
        $cek_absen = DB::table('absensi')
            ->where('id_staf', $id_staf)
            ->whereDate('tanggal', $now->toDateString())
            ->first();

        // --- TOMBOL MASUK ---
        if ($aksi == 'masuk') {
            if ($cek_absen) {
                return back()->with('error', "Anda sudah absen MASUK hari ini pada jam $cek_absen->jam_masuk");
            }

            DB::table('absensi')->insert([
                'id_staf'    => $id_staf,
                'tanggal'    => $now->toDateString(),
                'jam_masuk'  => $now->toTimeString(),
                'jam_pulang' => null,
                'shift'      => $shift, // Simpan shift pilihan user
                'created_at' => $now,
                'updated_at' => $now
            ]);

            return back()->with('sukses', "Halo $staf->nama_staf, Absen MASUK ($shift) berhasil!");
        }

        // --- TOMBOL PULANG ---
        if ($aksi == 'pulang') {
            if (!$cek_absen) {
                return back()->with('error', 'Anda belum absen MASUK, tidak bisa absen pulang.');
            }

            if ($cek_absen->jam_pulang) {
                return back()->with('error', "Anda sudah absen PULANG hari ini pada jam $cek_absen->jam_pulang");
            }

            DB::table('absensi')
                ->where('id_absensi', $cek_absen->id_absensi)
                ->update([
                    'jam_pulang' => $now->toTimeString(),
                    'updated_at' => $now
                ]);

            return back()->with('sukses', "Sampai jumpa $staf->nama_staf, Absen PULANG berhasil!");
        }
    }
}
