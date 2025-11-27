<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\PemilikKos;
use App\Models\Akun;
use App\Models\Staf;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Kamar;

class PemilikKosController extends Controller
{
    /**
     * Dashboard Utama Pemilik
     */
    public function index()
    {
        $user = Auth::user();

        // --- 1. DATA PENDAPATAN BULAN INI ---
        // Mengambil data yang statusnya 'lunas'
        $pendapatanBulanIni = Booking::where('status_booking', 'lunas')
            ->whereMonth('created_at', Carbon::now()->month) // Gunakan created_at atau tanggal bayar
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('nominal');

        // --- 2. DATA KAMAR KOSONG ---
        // Mengambil semua kamar yang statusnya 'tersedia'
        // Pastikan di tabel kamars kolomnya 'status' dan valuenya 'tersedia'
        $daftarKamarKosong = Kamar::where('status', 'tersedia')->get();
        $jumlahKamarKosong = $daftarKamarKosong->count();
        $totalKamar = Kamar::count();

        // --- 3. DATA PERMINTAAN SEWA (NOTIFIKASI) ---
        // [PERBAIKAN PENTING]: Ubah 'menunggu' menjadi 'pending' sesuai BookingController
        $permintaanSewa = Booking::where('status_booking', 'pending')
            ->with(['penyewa', 'kamar']) // Eager load relasi
            ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
            ->get();

        $jumlahPermintaan = $permintaanSewa->count();

        // --- 4. DATA BELUM LUNAS ---
        // Logika: Booking sudah CONFIRMED (diterima pemilik) tapi belum LUNAS
        $belumLunas = Booking::where('status_booking', 'confirmed')
            ->with(['penyewa', 'kamar'])
            ->get();

        $jumlahBelumLunas = $belumLunas->count();
        $totalUangBelumLunas = $belumLunas->sum('nominal');

        return view('home_pemilik', compact(
            'user',
            'pendapatanBulanIni',
            'daftarKamarKosong',
            'jumlahKamarKosong',
            'totalKamar',
            'permintaanSewa',
            'jumlahPermintaan',
            'belumLunas',
            'jumlahBelumLunas',
            'totalUangBelumLunas'
        ));
    }

    // --- LOGIKA PENYIMPANAN STAFF ---
    public function storeStaff(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_staf' => 'required|string|max:100',
            'no_hp'     => 'required|string|max:20',
            'email'     => 'required|email|unique:stafs,email',
            'jadwal'    => 'required|in:Pagi,Malam',
            'foto_staf' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'username'  => 'required|string|min:4|unique:akuns,username',
            'password'  => 'required|string|min:6',
        ]);

        DB::beginTransaction();

        try {
            // 2. Buat Data Akun (Login)
            Akun::create([
                'username'   => $request->username,
                'password'   => Hash::make($request->password),
                'jenis_akun' => 'staf',
            ]);

            // 3. Upload Foto
            $fotoPath = null;
            if ($request->hasFile('foto_staf')) {
                $fotoPath = $request->file('foto_staf')->store('foto_staf', 'public');
            }

            // 4. Buat Data Profil Staf
            Staf::create([
                'username'  => $request->username,
                'nama_staf' => $request->nama_staf,
                'email'     => $request->email,
                'no_hp'     => $request->no_hp,
                'jadwal'    => $request->jadwal,
                'foto_staf' => $fotoPath,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Akun Staff berhasil didaftarkan!');

        } catch (\Exception $e) {
            DB::rollback();

            if (isset($fotoPath) && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // --- FOTO PROFIL ---
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $akun = Auth::user();

        // Asumsi relasi di model Akun bernama 'pemilikKos' (camelCase)
        // Pastikan di model Akun: public function pemilikKos() { return $this->hasOne(PemilikKos::class, 'username', 'username'); }
        $pemilik = $akun->pemilikKos;

        if (!$pemilik) {
            return response()->json(['success' => false, 'message' => 'Data pemilik tidak ditemukan.'], 404);
        }

        try {
            if ($pemilik->foto_profil && Storage::disk('public')->exists($pemilik->foto_profil)) {
                Storage::disk('public')->delete($pemilik->foto_profil);
            }

            $path = $request->file('foto')->store('foto_profil', 'public');

            $pemilik->foto_profil = $path;
            $pemilik->save();

            return response()->json([
                'success' => true,
                'foto_url' => Storage::url($path)
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deletePhoto()
    {
        $pemilik = Auth::user()->pemilikKos;

        if ($pemilik && $pemilik->foto_profil) {
            Storage::disk('public')->delete($pemilik->foto_profil);
            $pemilik->foto_profil = null;
            $pemilik->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada foto untuk dihapus']);
    }
}
