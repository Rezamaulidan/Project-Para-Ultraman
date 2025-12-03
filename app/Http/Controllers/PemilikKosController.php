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
use App\Models\LaporanKeamanan;
use App\Models\Penyewa;

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

        // --- 1. DATA PENDAPATAN BULAN INI (CASH FLOW - updated_at) ---
        $pendapatanBulanIni = Booking::where('status_booking', 'lunas')
            ->whereMonth('updated_at', $bulanSaatIni)
            ->whereYear('updated_at', $tahunSaatIni)
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


        // --- 5. DATA CHART PENDAPATAN (6 BULAN TERAKHIR - CASH FLOW) ---
        $bulanMulai = $now->copy()->subMonths(5)->startOfMonth();

        $pendapatan6Bulan = Booking::select(
                DB::raw('MONTH(updated_at) as bulan'),
                DB::raw('YEAR(updated_at) as tahun'),
                DB::raw('SUM(nominal) as total_nominal')
            )
            ->where('status_booking', 'lunas')
            ->where('updated_at', '>=', $bulanMulai)
            ->groupBy(DB::raw('YEAR(updated_at)'), DB::raw('MONTH(updated_at)'))
            ->orderBy(DB::raw('YEAR(updated_at)'), 'asc')
            ->orderBy(DB::raw('MONTH(updated_at)'), 'asc')
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

    // --- LOGIKA PENYIMPANAN STAFF ---
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
                $staff->foto_staf = $request->file('foto_staf')->store('foto_staf', 'public');
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

    // =========================================================================
    // FITUR BARU DARI REMOTE (TRANSAKSI & PENGELUARAN)
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
        // Mengambil semua laporan, urutkan berdasarkan tanggal terbaru, dan preload data staf
        try {
            $laporans = LaporanKeamanan::with('staf')->latest('tanggal')->get();
        } catch (\Exception $e) {
            $laporans = collect();
        }

        return view('keamanan_pemilik', compact('laporans'));
    }

    public function dataPenyewaPemilik(Request $request)
    {
        $penyewaUsernamesDenganTransaksi = Booking::whereIn('status_booking', ['lunas', 'terlambat'])->pluck('username')->unique()->toArray();

        $query = Penyewa::whereIn('username', $penyewaUsernamesDenganTransaksi)
            ->with(['booking' => function ($q) {
                $q->whereIn('status_booking', ['lunas', 'terlambat'])
                    ->latest('tanggal')
                    ->with('kamar');
            }]);

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_penyewa', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('username', 'LIKE', '%' . $searchTerm . '%');
            });

            $query->orWhereHas('booking.kamar', function ($q) use ($searchTerm) {
                $q->whereIn('status_booking', ['lunas', 'terlambat'])
                    ->where('no_kamar', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $penyewas = $query->orderBy('username', 'asc')->get();
        $storageUrl = Storage::url('');

        return view('data_penyewa_pemilik', compact('penyewas', 'storageUrl'));
    }

    public function infoDetailPenyewa($username)
    {
        $penyewa = Penyewa::where('username', $username)
            ->with(['booking' => function ($q) {
                $q->whereIn('status_booking', ['lunas', 'terlambat'])
                  ->latest('tanggal')
                  ->with('kamar');
            }])
            ->firstOrFail();

        $storageUrl = Storage::url('');

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

    public function dataStaff()
    {
        // 1. Ambil semua data staff dari database
        $stafs = Staf::all();

        // 2. Tentukan URL dasar untuk storage (tempat foto disimpan)
        // KOREKSI: Gunakan Storage::url('') atau asset('storage')
        // agar sesuai dengan path yang di-link Laravel.
        $storageUrl = Storage::url('');

        // 3. Kirim data ke view
        return view('data_staff_pemilik', compact('stafs', 'storageUrl'));
    }

        // DI DALAM class PemilikKosController.php

// --- LOGIKA UPDATE FOTO PROFIL PEMILIK ---
public function updatePhoto(Request $request)
{
    $request->validate([
        'foto' => 'required|image|file|max:2048', // Max 2MB
    ]);

    $pemilik = Auth::user()->pemilikKos;

    if (!$pemilik) {
        return response()->json(['success' => false, 'message' => 'Data pemilik tidak ditemukan.'], 404);
    }

    try {
        DB::beginTransaction();

        // 1. Hapus foto lama jika ada
        if ($pemilik->foto_profil) {
            Storage::disk('public')->delete($pemilik->foto_profil);
        }

        // 2. Simpan foto baru ke folder 'foto_profil'
        $path = $request->file('foto')->store('foto_profil', 'public');

        // 3. Update path di database
        $pemilik->foto_profil = $path;
        $pemilik->save();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui.',
            'foto_url' => Storage::url($path)
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => 'Gagal mengupload foto.'], 500);
    }
}

// --- LOGIKA HAPUS FOTO PROFIL PEMILIK (KOREKSI UTAMA) ---
public function deleteProfilePhoto(Request $request)
{
    $pemilik = Auth::user()->pemilikKos;

    if (!$pemilik || !$pemilik->foto_profil) {
        return response()->json(['success' => false, 'message' => 'Tidak ada foto profil untuk dihapus.'], 400);
    }

    $filePath = $pemilik->foto_profil;
    $defaultImagePath = asset('images/pp-default.jpg'); // Kirim URL default ke JS

    try {
        DB::beginTransaction();

        // 1. Hapus file fisik dari storage
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // 2. Hapus path foto dari database (set menjadi NULL)
        $pemilik->foto_profil = null;
        $pemilik->save();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus.',
            'default_url' => $defaultImagePath // URL default
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => 'Gagal menghapus foto. Silakan coba lagi.'], 500);
    }
}

    public function infoDetailStaff($id_staf)
{
    // 1. Ambil data staff berdasarkan id_staf
    // Gunakan findOrFail agar otomatis melempar 404 jika staf tidak ditemukan
    $staff = Staf::findOrFail($id_staf);

    // 2. Tentukan URL dasar untuk storage
    $storageUrl = Storage::url('');

    // 3. Kirim data ke view
    return view('info_detail_staff', compact('staff', 'storageUrl'));
}

    // Method untuk menampilkan halaman
    public function editJadwal()
    {
        // Mengambil semua staf untuk ditampilkan di dropdown/list
        $stafs = Staf::all();

        // Jika menggunakan Blade biasa, return view('namaview', compact('stafs'));
        // Jika menggunakan React/Inertia, return Inertia::render('ShiftManager', ['stafs' => $stafs]);
        return view('edit_data_shif', compact('stafs'));
    }

    // Method untuk menyimpan perubahan (Logic Inti No. 3)
    public function updateJadwal(Request $request)
    {
        // Validasi input
        // Kita mengharapkan array ID untuk pagi dan array ID untuk malam
        $request->validate([
            'pagi_ids' => 'present|array', // 'present' berarti field harus ada meski kosong
            'pagi_ids.*' => 'exists:stafs,id_staf', // Pastikan ID ada di tabel
            'malam_ids' => 'present|array',
            'malam_ids.*' => 'exists:stafs,id_staf',
        ]);

        try {
            DB::beginTransaction();

            // 1. Reset jadwal (Opsional, tapi aman untuk menghindari duplikasi tak terduga)
            // Atau langsung update berdasarkan ID yang dikirim.

            // 2. Update Staf Shift Pagi
            if (!empty($request->pagi_ids)) {
                Staf::whereIn('id_staf', $request->pagi_ids)
                    ->update(['jadwal' => 'Pagi']);
            }

            // 3. Update Staf Shift Malam
            if (!empty($request->malam_ids)) {
                Staf::whereIn('id_staf', $request->malam_ids)
                    ->update(['jadwal' => 'Malam']);
            }

            // Opsional: Handle staf yang dikeluarkan dari kedua list (set null atau libur?)
            // $allIds = array_merge($request->pagi_ids, $request->malam_ids);
            // Staf::whereNotIn('id_staf', $allIds)->update(['jadwal' => 'Libur']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

}
