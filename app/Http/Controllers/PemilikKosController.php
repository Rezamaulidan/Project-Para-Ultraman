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
use App\Models\Pengeluaran; // Import dari master

class PemilikKosController extends Controller
{
    /**
     * Dashboard Utama Pemilik
     */
    public function index()
    {
        $user = Auth::user();
        
        // Pastikan load relasi jika diperlukan
        // $user = $user->load('PemilikKos')->PemilikKos; (Opsional tergantung struktur model User)

        $now = Carbon::now();
        $bulanSaatIni = $now->month;
        $tahunSaatIni = $now->year;

        // --- 1. DATA PENDAPATAN BULAN INI ---
        $pendapatanBulanIni = Booking::where('status_booking', 'lunas')
            ->whereMonth('created_at', $bulanSaatIni) // Asumsi pakai created_at atau tanggal
            ->whereYear('created_at', $tahunSaatIni)
            ->sum('nominal');

        // --- 2. DATA KAMAR KOSONG ---
        $daftarKamarKosong = Kamar::where('status', 'tersedia')->get();
        $jumlahKamarKosong = $daftarKamarKosong->count();
        $totalKamar = Kamar::count();
        // Hitung kamar terisi untuk statistik
        $jumlahKamarTerisi = $totalKamar - $jumlahKamarKosong; 

        // --- 3. DATA PERMINTAAN SEWA (NOTIFIKASI) ---
        // Menggunakan logika 'pending' agar sesuai dengan alur booking baru
        $permintaanSewa = Booking::where('status_booking', 'pending')
            ->with(['penyewa', 'kamar'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $jumlahPermintaan = $permintaanSewa->count();

        // --- 4. DATA BELUM LUNAS ---
        // Menggunakan logika 'confirmed' (sudah di-acc tapi belum bayar)
        $belumLunas = Booking::where('status_booking', 'confirmed')
            ->with(['penyewa', 'kamar'])
            ->get();
            
        $jumlahBelumLunas = $belumLunas->count();
        $totalUangBelumLunas = $belumLunas->sum('nominal');

        // --- 5. DATA CHART PENDAPATAN (DARI MASTER) ---
        $bulanMulai = $now->copy()->subMonths(5)->startOfMonth(); 
        $pendapatan6Bulan = Booking::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('SUM(nominal) as total_nominal')
            )
            ->where('status_booking', 'lunas') 
            ->where('created_at', '>=', $bulanMulai)
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
            ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
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

        // --- 6. DATA CHART PENGELUARAN (DARI MASTER) ---
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
            'jumlahKamarTerisi', // Tambahan untuk view master
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

    // --- LOGIKA PENYIMPANAN STAFF ---
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
PemilikKost

            // Kembali dengan pesan error
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
        // 1. Validasi Input
        $request->validate([
            // Hanya izinkan file foto dengan ukuran maksimal 2MB
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // Dapatkan data PemilikKos yang sedang login
        // Pastikan Auth::user() adalah model PemilikKos, atau ambil relasi jika Auth::user() adalah Akun.
        // Berdasarkan PemilikKosController::index, kita asumsikan $user adalah PemilikKos:
        // $user = Auth::user()->load('PemilikKos')->PemilikKos; <-- Ambil data PemilikKos
        // Karena kita tidak tahu persis bagaimana Auth::user() dikonfigurasi, 
        // kita akan gunakan relasi untuk amannya (asumsi relasi PemilikKos di Akun bernama 'pemilikKos')
        $akun = Auth::user();
        $pemilik = $akun->pemilikKos; // Ambil Model PemilikKos dari Akun
        
        if (!$pemilik) {
            return response()->json(['success' => false, 'message' => 'Data pemilik tidak ditemukan.'], 404);
        }

        // 2. Hapus foto lama (jika ada)
        if ($pemilik->foto_profil) {
            // Cek dan hapus dari disk public
            Storage::disk('public')->delete($pemilik->foto_profil);
        }

        // 3. Simpan foto baru
        // Simpan di folder 'storage/app/public/foto_profil'
        $path = $request->file('foto')->store('foto_profil', 'public');

        // 4. Update database
        $pemilik->foto_profil = $path;
        $pemilik->save();

        // 5. Kembalikan URL yang bisa diakses publik
        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diubah!',
            'foto_url' => Storage::url($path) 
        ]);
    }
    
    /**
     * Menghapus foto profil pemilik kos.
     */
    public function deletePhoto()
    {
        $akun = Auth::user();
        $pemilik = $akun->pemilikKos;
        
        if (!$pemilik) {
            return response()->json(['success' => false, 'message' => 'Data pemilik tidak ditemukan.'], 404);
        }

        // 1. Hapus foto dari storage (jika ada)
        if ($pemilik->foto_profil) {
            Storage::disk('public')->delete($pemilik->foto_profil);

            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
master
        }
    }

    // --- FOTO PROFIL (MERGED) ---
    public function updatePhoto(Request $request)
    {
        $request->validate(['foto' => 'required|image|mimes:jpeg,png,jpg|max:2048']);

        $akun = Auth::user();
        // Pastikan relasi model benar (misal: 'pemilikKos' camelCase)
        $pemilik = $akun->pemilikKos ?? $akun->PemilikKos; 

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

            return response()->json(['success' => true, 'foto_url' => Storage::url($path)]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
PemilikKost
    }

    public function infoDetailStaff()
    {
        return view('info_detail_staff');
    }


    }

    public function deletePhoto()
    {
        $akun = Auth::user();
        $pemilik = $akun->pemilikKos ?? $akun->PemilikKos;

        if ($pemilik && $pemilik->foto_profil) {
            Storage::disk('public')->delete($pemilik->foto_profil);
            $pemilik->foto_profil = null;
            $pemilik->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada foto untuk dihapus']);
    }

    // --- METHOD DARI MASTER (INFO DETAIL) ---
    public function infoDetailStaff()
    {
        return view('info_detail_staff');
    }

master
    public function infoDetailPenyewa()
    {
        return view('info_detail_penyewa_pmlk');
    }
}