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
use App\Models\Pengeluaran;

class PemilikKosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $user = $user->load('PemilikKos')->PemilikKos;
        $now = Carbon::now();
        $bulanSaatIni = $now->month;
        $tahunSaatIni = $now->year;

        // --- 1. DATA PENDAPATAN BULAN INI ---
        $pendapatanBulanIni = Booking::where('status_booking', 'lunas')
            ->whereMonth('tanggal', $bulanSaatIni)
            ->whereYear('tanggal', $tahunSaatIni)
            ->sum('nominal');

        // --- 5. DATA PENDAPATAN 6 BULAN TERAKHIR UNTUK CHART ---
        // Menghitung bulan mulai (6 bulan lalu dari bulan ini)
        $bulanMulai = $now->copy()->subMonths(5)->startOfMonth(); 

        $pendapatan6Bulan = Booking::select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('SUM(nominal) as total_nominal')
            )
            ->where('status_booking', 'lunas') // Hanya hitung yang sudah lunas
            ->where('tanggal', '>=', $bulanMulai) // Mulai dari 6 bulan terakhir
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
            // Jika Anda ingin 1 = Rp 1.000.000, maka gunakan pembagian ini.
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

        // --- 3. DATA PERMINTAAN SEWA (BARU) ---
        $permintaanSewa = Booking::where('status_booking', 'menunggu')
            ->with(['penyewa', 'kamar'])
            ->orderBy('tanggal', 'desc')
            ->get();
        $jumlahPermintaan = $permintaanSewa->count();

        // --- 4. DATA BELUM LUNAS / JATUH TEMPO ---
        $belumLunas = Booking::where('status_booking', 'belum_lunas')
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
            // Variabel Baru
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

    /**
     * Show the form for creating a new resource.
     */
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
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Dapatkan pemilik yang sedang login
        $pemilik = Auth::user();

        // Hapus foto lama jika ada
        if ($pemilik->foto_profil) {
            Storage::disk('public')->delete($pemilik->foto_profil);
        }

        // Simpan foto baru
        // 'foto_profil' adalah nama folder di dalam 'storage/app/public'
        $path = $request->file('foto')->store('foto_profil', 'public');

        // Update database
        $pemilik->foto_profil = $path;
        // $pemilik->save();
        // NOTE: Pastikan Auth::user() mengembalikan instance model yang bisa di-save.
        // Jika Auth::user() adalah model 'Akun' dan relasinya ke 'PemilikKos',
        // Anda mungkin perlu $pemilik->pemilikKos->foto_profil = ...; $pemilik->pemilikKos->save();

        // Kembalikan path foto baru agar bisa di-update di halaman
        return response()->json([
            'success' => true,
            'foto_url' => Storage::url($path) // Mengembalikan URL yang bisa diakses publik
        ]);
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