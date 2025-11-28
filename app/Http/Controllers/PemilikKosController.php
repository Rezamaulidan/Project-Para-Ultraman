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
use App\Models\Pengeluaran; 

class PemilikKosController extends Controller
{
    /**
     * Dashboard Utama Pemilik
     */
    public function index()
    {
        $user = Auth::user();
        $userAkun = Auth::user();
        $now = Carbon::now();
        $bulanSaatIni = $now->month;
        $tahunSaatIni = $now->year;

        $user = $userAkun->load('pemilikKos')->pemilikKos;

        // --- 1. DATA PENDAPATAN BULAN INI ---
        $pendapatanBulanIni = Booking::where('status_booking', 'lunas')
            ->whereMonth('tanggal', $bulanSaatIni)
            ->whereYear('tanggal', $tahunSaatIni)
            ->sum('nominal');

        // =========================================================================
        // --- 2. DATA KAMAR KOSONG & OKUPANSI (DIPERBAIKI) ---
        // =========================================================================
        
        // A. Ambil daftar 'no_kamar' yang sedang aktif (status lunas) dari tabel bookings
        $idKamarTerisi = Booking::where('status_booking', 'lunas')
            ->pluck('no_kamar') // Ambil kolom no_kamar saja
            ->unique()          // Pastikan tidak ada duplikat (jika penyewa bayar berkali-kali)
            ->toArray();

        // B. Hitung Total Kamar
        $totalKamar = Kamar::count();

        // C. Hitung Jumlah Kamar Terisi berdasarkan booking yang lunas
        $jumlahKamarTerisi = count($idKamarTerisi);

        // D. Hitung Jumlah Kamar Kosong
        $jumlahKamarKosong = $totalKamar - $jumlahKamarTerisi;

        // E. Ambil Object Kamar yang benar-benar kosong (yang ID-nya TIDAK ADA di booking lunas)
        // Ini menggantikan Kamar::where('status', 'tersedia') agar list di dashboard sinkron
        $daftarKamarKosong = Kamar::whereNotIn('no_kamar', $idKamarTerisi)->get();

        // =========================================================================

        // --- 3. DATA PERMINTAAN SEWA (BARU) --- 
        $permintaanSewa = Booking::where('jenis_transaksi', 'booking') 
            ->where('status_booking', 'pending') 
            ->with(['penyewa', 'kamar'])
            ->orderBy('tanggal', 'desc')
            ->get();
            
        $jumlahPermintaan = $permintaanSewa->count();

        // --- 4. DATA BELUM LUNAS / JATUH TEMPO --- (MODIFIKASI FINAL KARENA GAGAL)
        $belumLunas = Booking::where(DB::raw('LOWER(jenis_transaksi)'), 'pembayaran_sewa')
            ->whereIn('status_booking', ['terlambat', 'belum_lunas'])
            ->with(['penyewa', 'kamar'])
            ->get();
        $jumlahBelumLunas = $belumLunas->count();
        $totalUangBelumLunas = $belumLunas->sum('nominal');
        //return $belumLunas;

        // =========================================================================
        // --- 5. DATA CHART PENDAPATAN (6 BULAN TERAKHIR) ---
        // =========================================================================
        
        $bulanMulai = $now->copy()->subMonths(5)->startOfMonth(); 
        $pendapatan6Bulan = Booking::select(
                DB::raw('MONTH(tanggal) as bulan'), // Ubah created_at -> tanggal
                DB::raw('YEAR(tanggal) as tahun'),   // Ubah created_at -> tanggal
                DB::raw('SUM(nominal) as total_nominal')
            )
            ->where('status_booking', 'lunas') 
            ->where('tanggal', '>=', $bulanMulai)    // Ubah created_at -> tanggal
            ->groupBy(DB::raw('YEAR(tanggal)'), DB::raw('MONTH(tanggal)'))
            ->orderBy(DB::raw('YEAR(tanggal)'), 'asc')
            ->orderBy(DB::raw('MONTH(tanggal)'), 'asc')
            ->get()
            ->keyBy(function($item) {
                return $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT);
            });

        $dataChart = [];
        $labelsChart = [];
        $bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        for ($i = 5; $i >= 0; $i--) {
            $bulanIterasi = $now->copy()->subMonths($i);
            $key = $bulanIterasi->year . '-' . str_pad($bulanIterasi->month, 2, '0', STR_PAD_LEFT);
            $labelsChart[] = $bulanNama[$bulanIterasi->month - 1];
            $nominal = $pendapatan6Bulan->has($key) ? $pendapatan6Bulan[$key]->total_nominal : 0;
            $dataChart[] = round($nominal / 1000000, 2); 
        }

        // --- 6. DATA CHART PENGELUARAN ---
        $pengeluaran6Bulan = Pengeluaran::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('SUM(sub_total) as total_pengeluaran')
            )
            ->where('created_at', '>=', $bulanMulai)
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
            ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
            ->get()
            ->keyBy(function($item) {
                return $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT);
            });

        $dataPengeluaranChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulanIterasi = $now->copy()->subMonths($i);
            $key = $bulanIterasi->year . '-' . str_pad($bulanIterasi->month, 2, '0', STR_PAD_LEFT);
            $nominalPengeluaran = $pengeluaran6Bulan->has($key) ? $pengeluaran6Bulan[$key]->total_pengeluaran : 0;
            $dataPengeluaranChart[] = round($nominalPengeluaran / 1000000, 2); 
        }

        return view('home_pemilik', compact(
            'user',
            'pendapatanBulanIni',
            'daftarKamarKosong',
            'jumlahKamarKosong',
            'jumlahKamarTerisi',
            'totalKamar',
            'permintaanSewa',
            'jumlahPermintaan',
            'belumLunas',
            'jumlahBelumLunas',
            'totalUangBelumLunas',
            'dataChart',
            'labelsChart',
            'dataPengeluaranChart'
        ));
    }

    /**
     * Logika Penyimpanan Staff
     */
    public function storeStaff(Request $request)
    {
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
            Akun::create([
                'username'   => $request->username,
                'password'   => Hash::make($request->password),
                'jenis_akun' => 'staf',
            ]);

            $fotoPath = null;
            if ($request->hasFile('foto_staf')) {
                $fotoPath = $request->file('foto_staf')->store('foto_staf', 'public');
            }

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
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    /**
     * Mengupload dan mengupdate foto profil pemilik kos.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $akun = Auth::user();
        // Menggunakan null coalescing operator untuk menghandle perbedaan nama relasi
        $pemilik = $akun->pemilikKos ?? $akun->PemilikKos; 
        
        if (!$pemilik) {
            return response()->json(['success' => false, 'message' => 'Data pemilik tidak ditemukan.'], 404);
        }

        // Hapus foto lama (jika ada)
        if ($pemilik->foto_profil && Storage::disk('public')->exists($pemilik->foto_profil)) {
            Storage::disk('public')->delete($pemilik->foto_profil);
        }

        // Simpan foto baru
        $path = $request->file('foto')->store('foto_profil', 'public');

        // Update database
        $pemilik->foto_profil = $path;
        $pemilik->save();

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diubah!',
            // 'foto_url' => Storage::url($path) 
            'foto_url' => Storage::url($path)
        ]);
    }
    
    /**
     * Menghapus foto profil pemilik kos.
     */
    public function deletePhoto()
    {
        $akun = Auth::user();
        $pemilik = $akun->pemilikKos ?? $akun->PemilikKos;
        
        if (!$pemilik) {
            return response()->json(['success' => false, 'message' => 'Data pemilik tidak ditemukan.'], 404);
        }

        if ($pemilik->foto_profil) {
            if (Storage::disk('public')->exists($pemilik->foto_profil)) {
                Storage::disk('public')->delete($pemilik->foto_profil);
            }
            
            $pemilik->foto_profil = null;
            $pemilik->save();

            // return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus']);
            return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus!',
            // 'foto_url' => Storage::url($path) 
            'default_url' => asset('images/pp-default.jpg')
        ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Tidak ada foto untuk dihapus']);
    }

    public function infoDetailStaff()
    {
        return view('info_detail_staff');
    }

    public function infoDetailPenyewa()
    {
        return view('info_detail_penyewa_pmlk');
    }

} // <--- INI ADALAH PENUTUP CLASS TERAKHIR