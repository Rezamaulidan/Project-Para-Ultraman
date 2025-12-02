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

    // --- LOGIKA PENYIMPANAN STAFF (REVERT KE AUTO INCREMENT) ---
    public function storeStaff(Request $request)
    {
        $request->validate([
            'nama_staf' => 'required|string|max:100',
            'no_hp'     => 'required|string|max:20',
            'email'     => 'required|email|unique:stafs,email',
            'jadwal'    => 'required|in:Pagi,Malam',
            'foto_staf' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // 🛑 REVERT: Hapus logika generate ID (mt_rand). Biarkan DB yang mengurus ID.

            // 2. Upload Foto
            $fotoPath = null;
            if ($request->hasFile('foto_staf')) {
                $fotoPath = $request->file('foto_staf')->store('foto_staf', 'public');
            }

            // 3. Simpan
            Staf::create([
                // 'id_staf' => $idBaru, // 🛑 HAPUS BARIS INI
                'username'  => 'staf',
                'nama_staf' => $request->nama_staf,
                'email'     => $request->email,
                'no_hp'     => $request->no_hp,
                'jadwal'    => $request->jadwal,
                'foto_staf' => $fotoPath,
            ]);

            DB::commit();
            // Pesan sukses tanpa ID
            return redirect()->back()->with('success', 'Data Staff berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();
            if (isset($fotoPath) && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
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

    public function infoDetailStaff() { return view('info_detail_staff'); }
    public function infoDetailPenyewa() { return view('info_detail_penyewa_pmlk'); }

    // =========================================================================
    // FITUR BARU (TRANSAKSI & PENGELUARAN)
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
            ->sum('nominal'); // Pakai 'nominal' sesuai model
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
}
