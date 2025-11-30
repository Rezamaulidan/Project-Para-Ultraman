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

    public function transaksiPemilik(Request $request)
    {
        // Logika query utama (hanya lunas)
        $query = Booking::where('status_booking', 'lunas')
            ->with(['penyewa', 'kamar'])
            ->orderBy('tanggal', 'desc');

        // Jika ada parameter pencarian (untuk penggunaan non-AJAX atau jika view dimuat dengan parameter)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('penyewa', function ($q) use ($searchTerm) {
                // Pencarian berdasarkan nama penyewa yang dimulai dengan kata kunci (LIKE 'kata_kunci%')
                $q->where('nama_penyewa', 'LIKE', $searchTerm . '%');
            })
            ->orWhere('id_booking', 'LIKE', '%' . $searchTerm . '%'); // Atau cari di ID Transaksi
        }

        $transaksis = $query->paginate(10); 

        return view('transaksi_pemilik', compact('transaksis'));
    }

    /**
     * Endpoint AJAX untuk Pencarian Real-Time (Keyup).
     */
    public function searchTransaksiLunas(Request $request)
    {
        // Validasi dan sanitasi input pencarian
        $searchTerm = $request->input('query');

        // Membangun Query
        $transaksis = Booking::where('status_booking', 'lunas')
            ->where(function ($query) use ($searchTerm) {
                // Mencari di Nama Penyewa yang diawali dengan kata kunci (start with)
                $query->whereHas('penyewa', function ($q) use ($searchTerm) {
                    $q->where('nama_penyewa', 'LIKE', $searchTerm . '%');
                });
                // ATAU mencari di ID Transaksi yang mengandung kata kunci
                $query->orWhere('id_booking', 'LIKE', '%' . $searchTerm . '%');
            })
            ->with(['penyewa', 'kamar'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        // Render hasil data ke view partial untuk dikirim kembali sebagai HTML
        $view = view('partials.transaksi_table_rows', compact('transaksis'))->render();

        return response()->json([
            'html' => $view,
            'pagination_info' => 'Menampilkan ' . $transaksis->firstItem() . ' - ' . $transaksis->lastItem() . ' dari ' . $transaksis->total() . ' Transaksi',
            'pagination_links' => $transaksis->links()->toHtml(),
            'pagination_links' => $transaksis->links('pagination::bootstrap-4')->toHtml(),
        ]);
    }

    public function exportTransaksiLunas()
    {
        // 1. Ambil Data Transaksi Lunas (Sama seperti di transaksiPemilik)
        $transaksis = Booking::where('status_booking', 'lunas')
            ->with(['penyewa', 'kamar'])
            ->orderBy('tanggal', 'desc')
            ->get(); // Gunakan get() alih-alih paginate() untuk ekspor semua data

        $fileName = 'transaksi_lunas_' . now()->format('Ymd_His') . '.csv';

        // 2. Siapkan header CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '";',
        ];

        // 3. Siapkan fungsi callback untuk membuat konten CSV
        $callback = function() use ($transaksis) {
            $file = fopen('php://output', 'w');

            // Baris Header CSV
            fputcsv($file, [
                'ID. Transaksi', 
                'Penyewa', 
                'Kamar', 
                'Periode Bayar', 
                'Jumlah Bayar', 
                'Tgl. Bayar', 
                'Status'
            ], ';'); // Menggunakan titik koma (;) sebagai delimiter

            // Isi Data
            foreach ($transaksis as $transaksi) {
                // Formatting data sesuai tampilan
                $idTransaksi = 'TRKS-' . str_pad($transaksi->id_booking, 3, '0', STR_PAD_LEFT);
                $namaPenyewa = $transaksi->penyewa ? $transaksi->penyewa->nama_penyewa : '*Penyewa Tidak Ditemukan*';
                $noKamar = $transaksi->kamar ? $transaksi->kamar->no_kamar : '*N/A*';
                $periodeBayar = \Carbon\Carbon::parse($transaksi->tanggal)->format('M Y');
                // Nominal tanpa simbol Rp dan titik, agar mudah diolah di Excel
                $nominal = number_format($transaksi->nominal, 0, '', ''); 
                $tglBayar = \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y');
                $status = 'Lunas';

                fputcsv($file, [
                    $idTransaksi, 
                    $namaPenyewa, 
                    $noKamar, 
                    $periodeBayar, 
                    $nominal, 
                    $tglBayar, 
                    $status
                ], ';');
            }

            fclose($file);
        };

        // 4. Kembalikan Response Stream
        return response()->stream($callback, 200, $headers);
    }

    public function pengeluaranPemilik()
    {
        // Ambil data pengeluaran urut berdasarkan tanggal terbaru
        $pengeluarans = Pengeluaran::orderBy('tanggal', 'desc')->paginate(10);

        // Hitung Total Bulan Ini
        $totalBulanIni = Pengeluaran::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('sub_total');

        return view('pengeluaran_pemilik', compact('pengeluarans', 'totalBulanIni'));
    }

    /**
     * Simpan Pengeluaran Baru
     */
    public function storePengeluaran(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'sub_total' => 'required|numeric|min:0',
        ]);

        Pengeluaran::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jumlah' => $request->jumlah,
            'sub_total' => $request->sub_total, // Kita ambil input subtotal langsung
        ]);

        return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat!');
    }

    /**
     * Hapus Pengeluaran
     */
    public function destroyPengeluaran($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();

        return redirect()->back()->with('success', 'Data pengeluaran berhasil dihapus.');
    }

}