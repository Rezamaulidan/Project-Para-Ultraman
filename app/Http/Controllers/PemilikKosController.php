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

class PemilikKosController extends Controller{
    public function index()
    {
        $user = Auth::user();
        $user = $user->load('PemilikKos')->PemilikKos;
        $now = Carbon::now();
        $user = $user->load('PemilikKos')->PemilikKos;
        $bulanSaatIni = $now->month;
        $tahunSaatIni = $now->year;

        // --- 1. DATA PENDAPATAN BULAN INI ---
        $pendapatanBulanIni = Booking::where('status_booking', 'lunas')
            ->whereMonth('tanggal', $bulanSaatIni)
            ->whereYear('tanggal', $tahunSaatIni)
            ->sum('nominal');

        // --- 5. DATA PENDAPATAN 6 BULAN TERAKHIR UNTUK CHART ---
        $bulanMulai = $now->copy()->subMonths(5)->startOfMonth(); 

        $pendapatan6Bulan = Booking::select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('SUM(nominal) as total_nominal')
            )
            ->where('status_booking', 'lunas') 
            ->where('tanggal', '>=', $bulanMulai)
            ->groupBy(DB::raw('YEAR(tanggal)'), DB::raw('MONTH(tanggal)'))
            ->orderBy(DB::raw('YEAR(tanggal)'), 'asc')
            ->orderBy(DB::raw('MONTH(tanggal)'), 'asc')
            ->get()
            ->keyBy(function($item) {
                // Kunci format YYYY-MM untuk memudahkan penggabungan
                return $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT);
            });

        // Membuat array 6 bulan terakhir dengan nilai default 0
        $dataChart = [];
        $labelsChart = [];
        // Nama bulan dalam Bahasa Indonesia (jika perlu diganti)
        $bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Isi data untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $bulanIterasi = $now->copy()->subMonths($i);
            $key = $bulanIterasi->year . '-' . str_pad($bulanIterasi->month, 2, '0', STR_PAD_LEFT);
            $namaBulan = $bulanNama[$bulanIterasi->month - 1];
            
            // Tambahkan Label Bulan
            $labelsChart[] = $namaBulan;

            // Ambil data nominal, jika tidak ada, default 0
            $nominal = $pendapatan6Bulan->has($key) ? $pendapatan6Bulan[$key]->total_nominal : 0;
            
            // Konversi ke Juta Rupiah (Pastikan Anda tahu skala chart yang diinginkan)
            $dataChart[] = round($nominal / 1000000, 2); 
        }
        // END DATA PENDAPATAN 6 BULAN

        // --- 6. DATA PENGELUARAN 6 BULAN TERAKHIR UNTUK CHART ---
        $pengeluaran6Bulan = Pengeluaran::select( // <-- Query ke tabel pengeluarans
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('SUM(sub_total) as total_pengeluaran') // Asumsi field total adalah 'sub_total'
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
        
        // Isi data pengeluaran untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $bulanIterasi = $now->copy()->subMonths($i);
            $key = $bulanIterasi->year . '-' . str_pad($bulanIterasi->month, 2, '0', STR_PAD_LEFT);

            // Ambil data pengeluaran nominal, jika tidak ada, default 0
            $nominalPengeluaran = $pengeluaran6Bulan->has($key) ? $pengeluaran6Bulan[$key]->total_pengeluaran : 0;
            
            // Konversi ke Juta Rupiah (skala sama dengan Pendapatan)
            $dataPengeluaranChart[] = round($nominalPengeluaran / 1000000, 2); 
        }

        // --- 2. DATA KAMAR KOSONG --- (dst...)
        $daftarKamarKosong = Kamar::where('status', 'tersedia')->get();
        $jumlahKamarKosong = $daftarKamarKosong->count();
        $totalKamar = Kamar::count();

        // --- 3. DATA PERMINTAAN SEWA (BARU) --- (MODIFIKASI)
        $permintaanSewa = Booking::where('jenis_transaksi', 'booking') // <-- Tambah Filter 1: Jenis Transaksi HARUS 'booking'
            ->where('status_booking', 'pending') // <-- Filter 2: Status HARUS 'pending' (sesuai screenshot DB)
            ->with(['penyewa', 'kamar'])
            ->orderBy('tanggal', 'desc')
            ->get();
        $jumlahPermintaan = $permintaanSewa->count();

        // --- 4. DATA BELUM LUNAS / JATUH TEMPO --- (MODIFIKASI RINGAN)
        $belumLunas = Booking::where('jenis_transaksi', 'pembayaran_sewa')
            ->whereIn('status_booking', ['terlambat', 'belum_lunas']) // <-- Cek 2 status sekaligus
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
            'totalUangBelumLunas',
            'dataChart',
            'labelsChart',
            'dataPengeluaranChart'
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

            return redirect()->back()->with('staff_saved', 'Akun Staff berhasil didaftarkan!');

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
        }

        // 2. Update database (set kolom foto_profil menjadi NULL)
        $pemilik->foto_profil = null;
        $pemilik->save();

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus!',
            'default_url' => asset('images/pp-default.jpg') // Ganti dengan path foto default Anda
        ]);

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

    public function infoDetailStaff()
    {
        return view('info_detail_staff');
    }

    public function infoDetailPenyewa()
    {
        return view('info_detail_penyewa_pmlk');
    }
}