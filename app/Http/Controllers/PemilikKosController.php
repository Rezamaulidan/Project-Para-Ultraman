<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\PemilikKos;
use App\Models\Akun;
use App\Models\Penyewa;
use App\Models\Staf;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\LaporanKeamanan;
use App\Models\Pengeluaran;

class PemilikKosController extends Controller
{
    /**
     * Dashboard Utama Pemilik
     */
    public function index()
    {
        // 1. Setup Data User & Waktu
        $userAkun = Auth::user();
        $user = $userAkun->load('pemilikKos')->pemilikKos ?? $userAkun;

        $now = Carbon::now();
        $bulanSaatIni = $now->month;
        $tahunSaatIni = $now->year;

        // --- 1. DATA PENDAPATAN BULAN INI ---
        $pendapatanBulanIni = Booking::whereIn('status_booking', ['lunas', 'terlambat'])
            ->whereMonth('tanggal', $bulanSaatIni)
            ->whereYear('tanggal', $tahunSaatIni)
            ->sum('nominal');

        // --- 2. DATA KAMAR KOSONG & OKUPANSI ---
        $idKamarTerisi = Booking::where('status_booking', 'lunas')
            ->pluck('no_kamar')
            ->unique()
            ->toArray();

        $totalKamar = Kamar::count();
        $jumlahKamarTerisi = count($idKamarTerisi);
        $jumlahKamarKosong = $totalKamar - $jumlahKamarTerisi;
        $daftarKamarKosong = Kamar::whereNotIn('no_kamar', $idKamarTerisi)->get();

        // --- 3. DATA PERMINTAAN SEWA (NOTIFIKASI) ---
        $permintaanSewa = Booking::where('jenis_transaksi', 'booking')
            ->where('status_booking', 'pending')
            ->with(['penyewa', 'kamar'])
            ->orderBy('tanggal', 'desc')
            ->get();

        $jumlahPermintaan = $permintaanSewa->count();

        // --- 4. DATA BELUM LUNAS / JATUH TEMPO ---
        $belumLunas = Booking::where(DB::raw('LOWER(jenis_transaksi)'), 'pembayaran_sewa')
            ->whereIn('status_booking', ['terlambat', 'belum_lunas'])
            ->with(['penyewa', 'kamar'])
            ->get();

        $jumlahBelumLunas = $belumLunas->count();
        $totalUangBelumLunas = $belumLunas->sum('nominal');

        // --- 5. DATA CHART PENDAPATAN (6 BULAN TERAKHIR) ---
        $bulanMulai = $now->copy()->subMonths(5)->startOfMonth();

        $pendapatan6Bulan = Booking::select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('SUM(nominal) as total_nominal')
            )
            ->whereIn('status_booking', ['lunas', 'terlambat'])
            ->where('tanggal', '>=', $bulanMulai)
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
                DB::raw('SUM(nominal) as total_pengeluaran')
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
            'user', 'pendapatanBulanIni', 'daftarKamarKosong', 'jumlahKamarKosong',
            'jumlahKamarTerisi', 'totalKamar', 'permintaanSewa', 'jumlahPermintaan',
            'belumLunas', 'jumlahBelumLunas', 'totalUangBelumLunas', 'dataChart',
            'labelsChart', 'dataPengeluaranChart'
        ));
    }

    // =========================================================================
    // METHOD BARU: DAFTAR PENYEWA DINAMIS
    // =========================================================================

    // app/Http/Controllers/PemilikKosController.php

    public function dataPenyewaPemilik(Request $request)
    {
        // 1. MENDAPATKAN USERNAME penyewa dari tabel 'bookings'
        // Menggunakan 'no_kamar' karena kolom ini yang diisi dengan username penyewa (misal: 'irfan123')
        $penyewaUsernamesDenganTransaksi = Booking::whereIn('status_booking', ['lunas', 'terlambat'])
        ->pluck('username') // <--- PERBAIKAN PENTING DI SINI! Pluck dari kolom yang menyimpan username
        ->unique()
        ->toArray();

        // 2. Query Penyewa: Memfilter berdasarkan 'username'
        // Menggunakan 'username' karena itulah Primary Key di tabel penyewas.
        $query = Penyewa::whereIn('username', $penyewaUsernamesDenganTransaksi)
        ->with(['booking' => function ($q) {
            // Ambil booking terbaru yang statusnya lunas/terlambat untuk mendapatkan No. Kamar
            $q->whereIn('status_booking', ['lunas', 'terlambat'])
                ->latest('tanggal')
                ->with('kamar');
        }]);

        // 3. Implementasi fitur pencarian
        if ($request->filled('q')) {
            $searchTerm = $request->q;

            // Gunakan 'where' pada query utama
            $query->where(function ($q) use ($searchTerm) {
                // Cari berdasarkan Nama Penyewa (wildcard: mengandung string)
                $q->where('nama_penyewa', 'LIKE', '%' . $searchTerm . '%')
                    // Cari berdasarkan Username Penyewa (wildcard: mengandung string)
                    ->orWhere('username', 'LIKE', '%' . $searchTerm . '%');
            });

            // Tambahkan pencarian berdasarkan No. Kamar (melalui relasi Booking ke Kamar)
            // Penting: Ini mencari No. Kamar di tabel 'kamars'
            $query->orWhereHas('booking.kamar', function ($q) use ($searchTerm) {
                // Ambil booking yang statusnya lunas/terlambat dan filter No. Kamar
                $q->whereIn('status_booking', ['lunas', 'terlambat'])
                    ->where('no_kamar', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // 4. Ambil data dan kirim ke view
        $penyewas = $query->orderBy('username', 'asc')->get();
        $storageUrl = Storage::url('');

        return view('data_penyewa_pemilik', compact('penyewas', 'storageUrl'));
    }

    // =========================================================================
    // METHOD BARU: INFO DETAIL PENYEWA (DINAMIS)
    // =========================================================================
    public function infoDetailPenyewa($username)
    {
        // Cari data Penyewa berdasarkan username (Primary Key)
        // Eager load relasi booking dan kamar untuk menampilkan informasi sewa
        $penyewa = Penyewa::where('username', $username)
            ->with(['booking' => function ($q) {
                // Ambil booking terakhir yang berstatus aktif (lunas/terlambat)
                $q->whereIn('status_booking', ['lunas', 'terlambat'])
                  ->latest('tanggal')
                  ->with('kamar');
            }])
            ->firstOrFail(); // Gagal jika penyewa tidak ditemukan

        // URL untuk mengakses file di storage, misalnya foto KTP atau foto profil
        $storageUrl = Storage::url('');

        // Tentukan status penyewa berdasarkan booking terakhir
        $statusPenyewa = 'Tidak Aktif';
        if ($penyewa->booking) {
            if ($penyewa->booking->status_booking == 'lunas') {
                $statusPenyewa = 'Penyewa Aktif';
            } elseif ($penyewa->booking->status_booking == 'terlambat') {
                $statusPenyewa = 'Terlambat Bayar';
            }
        }

        return view('info_detail_penyewa_pmlk', compact('penyewa', 'storageUrl', 'statusPenyewa'));
    }

    // --- LOGIKA PENYIMPANAN STAFF (Dipertahankan sesuai versi Anda) ---
    public function storeStaff(Request $request)
    {
        // 1. Validasi Data
        $validatedData = $request->validate([
            'nama_staf' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15|unique:stafs,no_hp', // Asumsi kolom 'staff' memiliki kolom 'no_hp'
            'email' => 'required|email|max:255|unique:stafs,email', // Asumsi kolom 'staff' memiliki kolom 'email'
            'jadwal' => 'required|in:Pagi,Malam',
            'foto_staf' => 'nullable|image|file|max:1024', // Maks 1MB
        ], [
            'no_hp.unique' => 'Nomor HP ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah terdaftar.',
        ]);

        // Gunakan transaksi jika melibatkan banyak operasi database
        try {
            DB::beginTransaction();
            $nama_staff = $validatedData['nama_staf'];
            $staff = new Staf();
            $staff->nama_staf = $nama_staff;
            $staff->no_hp = $validatedData['no_hp'];
            $staff->email = $validatedData['email'];
            $staff->jadwal = $validatedData['jadwal'];
            // Tambahkan kolom lain yang relevan

            // 2. Upload Foto (Jika ada)
            if ($request->file('foto_staf')) {
                // Simpan file dan dapatkan path-nya (misalnya di folder 'public/staff-photos')
                $staff->foto_staf = $request->file('foto_staf')->store('staff-photos');
            }

            $staff->save();
            DB::commit();

            // 3. Redirect dengan SweetAlert (Menggunakan Session 'staff_saved')
            return redirect()->route('pemilik.datastaff')->with('staff_saved', $nama_staff);

        } catch (\Exception $e) {
            DB::rollBack();
            // Optional: Log error $e->getMessage()
            // Kembali ke halaman form dengan error umum
            return back()->with('error', 'Gagal menyimpan data staff. Silakan coba lagi.')->withInput();
        }
    }

    public function infoDetailStaff($id_staf)
    {
        // 1. Cari data staff berdasarkan ID atau tampilkan error 404 jika tidak ditemukan
        $staff = Staf::findOrFail($id_staf);

        // 2. Tentukan URL dasar untuk storage
        $storageUrl = Storage::url('storage/');

        // 3. Kirim data staff dan storage URL ke view
        return view('info_detail_staff', compact('staff', 'storageUrl'));
    }

    public function dataStaff()
    {
        // 1. Ambil semua data staff dari database
        $stafs = Staf::all(); // Mengambil semua data dari tabel 'stafs'

        // 2. Tentukan URL dasar untuk storage (tempat foto disimpan)
        $storageUrl = Storage::url('storage/');

        // 3. Kirim data ke view
        return view('data_staff_pemilik', compact('stafs', 'storageUrl'));
    }

    // --- FOTO PROFIL ---
    public function updatePhoto(Request $request)
    {
        $request->validate(['foto' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        $akun = Auth::user();
        $pemilik = $akun->pemilikKos ?? $akun->PemilikKos;

        if (!$pemilik) return response()->json(['success' => false, 'message' => 'Data pemilik tidak ditemukan.'], 404);

        if ($pemilik->foto_profil && Storage::disk('public')->exists($pemilik->foto_profil)) {
            Storage::disk('public')->delete($pemilik->foto_profil);
        }

        $path = $request->file('foto')->store('foto_profil', 'public');
        $pemilik->foto_profil = $path;
        $pemilik->save();

        return response()->json(['success' => true, 'message' => 'Foto profil berhasil diubah!', 'foto_url' => Storage::url($path)]);
    }

    public function deletePhoto()
    {
        $akun = Auth::user();
        $pemilik = $akun->pemilikKos ?? $akun->PemilikKos;

        if (!$pemilik) return response()->json(['success' => false, 'message' => 'Data pemilik tidak ditemukan.'], 404);

        if ($pemilik->foto_profil) {
            if (Storage::disk('public')->exists($pemilik->foto_profil)) {
                Storage::disk('public')->delete($pemilik->foto_profil);
            }
            $pemilik->foto_profil = null;
            $pemilik->save();
            return response()->json(['success' => true, 'message' => 'Foto profil berhasil dihapus!', 'default_url' => asset('images/pp-default.jpg')]);
        }
        return response()->json(['success' => false, 'message' => 'Tidak ada foto untuk dihapus']);
    }

    // =========================================================================
    // FITUR BARU DARI MASTER (TRANSAKSI & PENGELUARAN)
    // =========================================================================

    public function transaksiPemilik(Request $request)
    {
        $query = Booking::where('status_booking', 'lunas')->with(['penyewa', 'kamar'])->orderBy('tanggal', 'desc');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('penyewa', function ($q) use ($searchTerm) {
                $q->where('nama_penyewa', 'LIKE', $searchTerm . '%');
            })->orWhere('id_booking', 'LIKE', '%' . $searchTerm . '%');
        }

        $transaksis = $query->paginate(10);
        return view('transaksi_pemilik', compact('transaksis'));
    }

    public function searchTransaksiLunas(Request $request)
    {
        $searchTerm = $request->input('query');
        $transaksis = Booking::where('status_booking', 'lunas')
            ->where(function ($query) use ($searchTerm) {
                $query->whereHas('penyewa', function ($q) use ($searchTerm) {
                    $q->where('nama_penyewa', 'LIKE', $searchTerm . '%');
                })->orWhere('id_booking', 'LIKE', '%' . $searchTerm . '%');
            })
            ->with(['penyewa', 'kamar'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        $view = view('partials.transaksi_table_rows', compact('transaksis'))->render();

        return response()->json([
            'html' => $view,
            'pagination_info' => 'Menampilkan ' . $transaksis->firstItem() . ' - ' . $transaksis->lastItem() . ' dari ' . $transaksis->total() . ' Transaksi',
            'pagination_links' => $transaksis->links('pagination::bootstrap-4')->toHtml(),
        ]);
    }

    public function exportTransaksiLunas()
    {
        $transaksis = Booking::where('status_booking', 'lunas')->with(['penyewa', 'kamar'])->orderBy('tanggal', 'desc')->get();
        $fileName = 'transaksi_lunas_' . now()->format('Ymd_His') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="' . $fileName . '";'];

        $callback = function() use ($transaksis) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID. Transaksi', 'Penyewa', 'Kamar', 'Periode Bayar', 'Jumlah Bayar', 'Tgl. Bayar', 'Status'], ';');
            foreach ($transaksis as $transaksi) {
                fputcsv($file, [
                    'TRKS-' . str_pad($transaksi->id_booking, 3, '0', STR_PAD_LEFT),
                    $transaksi->penyewa ? $transaksi->penyewa->nama_penyewa : '*Penyewa Tidak Ditemukan*',
                    $transaksi->kamar ? $transaksi->kamar->no_kamar : '*N/A*',
                    \Carbon\Carbon::parse($transaksi->tanggal)->format('M Y'),
                    number_format($transaksi->nominal, 0, '', ''),
                    \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y'),
                    'Lunas'
                ], ';');
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function pengeluaranPemilik()
    {
        $pengeluarans = Pengeluaran::orderBy('tanggal', 'desc')->paginate(10);
        $totalBulanIni = Pengeluaran::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('nominal');
        return view('pengeluaran_pemilik', compact('pengeluarans', 'totalBulanIni'));
    }

    public function storePengeluaran(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'nominal' => 'required|numeric|min:0',
        ]);

        Pengeluaran::create($request->all());
        return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function destroyPengeluaran($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();
        return redirect()->back()->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    public function laporanKeamanan()
    {
        // Ambil semua laporan, urutkan berdasarkan tanggal terbaru, dan preload data staf
        // Asumsi: Ada relasi 'staf' di Model LaporanKeamanan
        try {
            $laporans = LaporanKeamanan::with('staf')->latest('tanggal')->get();
        } catch (\Exception $e) {
            // Ini akan membantu jika ada masalah database atau Model/Relasi
            $laporans = collect();
            // Opsional: Anda bisa log error $e jika di Production
        }

        // Mengirim variabel $laporans yang sudah terdefinisi ke view
        return view('keamanan_pemilik', compact('laporans'));
    }
}
