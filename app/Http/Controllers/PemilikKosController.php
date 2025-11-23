<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; // Penting untuk hashing password
use Illuminate\Support\Facades\DB;   // Penting untuk transaksi database
use App\Models\PemilikKos;
use App\Models\Akun; // Model Akun (Login)
use App\Models\Staf;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Kamar; // Model Staf (Profil)

class PemilikKosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // --- 1. DATA PENDAPATAN BULAN INI ---
        // Asumsi: status booking yang sudah bayar adalah 'lunas' atau 'confirmed'
        // Sesuaikan 'lunas' dengan value enum di database Anda
        $pendapatanBulanIni = Booking::where('status_booking', 'lunas')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('nominal');

        // --- 2. DATA KAMAR KOSONG ---
        // Mengambil semua kamar yang statusnya 'tersedia'
        $daftarKamarKosong = Kamar::where('status', 'tersedia')->get();
        $jumlahKamarKosong = $daftarKamarKosong->count();
        $totalKamar = Kamar::count();

        // --- 3. DATA PERMINTAAN SEWA (BARU) ---
        // Asumsi: Booking baru statusnya 'menunggu' atau 'pending'
        $permintaanSewa = Booking::where('status_booking', 'menunggu') // Sesuaikan dengan enum DB Anda
            ->with(['penyewa', 'kamar']) // Eager load relasi agar efisien
            ->orderBy('tanggal', 'desc')
            ->get();
        $jumlahPermintaan = $permintaanSewa->count();

        // --- 4. DATA BELUM LUNAS / JATUH TEMPO ---
        // Asumsi: status 'belum_lunas' atau mencari yang telat bayar
        $belumLunas = Booking::where('status_booking', 'belum_lunas') // Sesuaikan dengan enum DB Anda
            ->with(['penyewa', 'kamar'])
            ->get();
        $jumlahBelumLunas = $belumLunas->count();
        $totalUangBelumLunas = $belumLunas->sum('nominal');

        return view('home_pemilik', compact( // Pastikan nama view sesuai file Anda
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
            'email'     => 'required|email|unique:stafs,email', // Cek unik di tabel stafs
            'jadwal'    => 'required|in:Pagi,Malam',
            'foto_staf' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'username'  => 'required|string|min:4|unique:akuns,username', // Cek unik di tabel akuns
            'password'  => 'required|string|min:6',
        ]);

        // Gunakan Database Transaction agar data konsisten
        // (Jika gagal simpan staf, akun juga batal dibuat)
        DB::beginTransaction();

        try {
            // 2. Buat Data Akun (Login)
            Akun::create([
                'username'  => $request->username,
                'password'  => Hash::make($request->password), // Password wajib di-hash
                'jenis_akun'=> 'staf', // Role otomatis jadi staf
            ]);

            // 3. Upload Foto (Jika ada)
            $fotoPath = null;
            if ($request->hasFile('foto_staf')) {
                // Simpan di folder 'storage/app/public/foto_staf'
                $fotoPath = $request->file('foto_staf')->store('foto_staf', 'public');
            }

            // 4. Buat Data Profil Staf
            Staf::create([
                'username'  => $request->username, // Foreign Key ke Akun
                'nama_staf' => $request->nama_staf,
                'email'     => $request->email,
                'no_hp'     => $request->no_hp,
                'jadwal'    => $request->jadwal,
                'foto_staf' => $fotoPath,
            ]);

            // Jika semua sukses, commit ke database
            DB::commit();

            return redirect()->back()->with('success', 'Akun Staff berhasil didaftarkan!');

        } catch (\Exception $e) {
            // Jika ada error, batalkan semua perubahan database
            DB::rollback();

            // Hapus foto yang terlanjur diupload jika gagal simpan DB (Opsional tapi bagus)
            if (isset($fotoPath) && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            // Kembali dengan pesan error
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function deletePhoto()
    {
        $pemilik = Auth::user()->pemilikKos;

        if ($pemilik->foto_profil) {
            Storage::disk('public')->delete($pemilik->foto_profil);
            $pemilik->foto_profil = null; // Set kolom jadi NULL
            $pemilik->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada foto untuk dihapus']);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PemilikKos $PemilikKos)
    {
        //
    }

    // edit foto
    public function updatePhoto(Request $request)
    {
        // 1. Validasi file
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // 2. Ambil Akun yang sedang login
        $akun = Auth::user();

        // 3. Ambil data Pemilik Kos dari relasi
        $pemilik = $akun->pemilikKos;

        // Cek apakah data pemilik ada
        if (!$pemilik) {
            return response()->json(['success' => false, 'message' => 'Data pemilik tidak ditemukan.'], 404);
        }

        try {
            // 4. Hapus foto lama jika ada (agar storage tidak penuh)
            if ($pemilik->foto_profil && Storage::disk('public')->exists($pemilik->foto_profil)) {
                Storage::disk('public')->delete($pemilik->foto_profil);
            }

            // 5. Simpan foto baru ke folder 'foto_profil' di storage public
            $path = $request->file('foto')->store('foto_profil', 'public');

            // 6. Update kolom di database
            $pemilik->foto_profil = $path;
            $pemilik->save();

            // 7. Berikan respon sukses
            return response()->json([
                'success' => true,
                'foto_url' => Storage::url($path)
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PemilikKos $PemilikKos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, pemilikKos $pemilikKos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PemilikKos $PemilikKos)
    {
        //
    }
}
