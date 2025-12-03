<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Wajib import DB
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Staf;
use Carbon\Carbon;

class StafController extends Controller
{
    // 1. Menampilkan Daftar Semua Staf (Manajemen)
    public function indexManajemen()
    {
        // Ambil semua data staf dari database
        $semua_staf = DB::table('stafs')->get();

        // Tampilkan ke view
        return view('manajemen_index', compact('semua_staf'));
    }

    // 2. Melihat Profil Detail Staf Lain
    public function lihatProfil($id)
    {
        $staf = DB::table('stafs')->where('id_staf', $id)->first();

        if(!$staf) {
            return back()->with('error', 'Data staf tidak ditemukan.');
        }

        return view('staff.profil_detail', compact('staf'));
    }

    // 3. Form Edit Profil Diri Sendiri
    public function editProfil()
    {
        // Ambil data staf yang sedang login berdasarkan username akun
        $user = Auth::user();

        $staf = DB::table('stafs')->where('username', $user->username)->first();

        if(!$staf) {
            return back()->with('error', 'Data profil Anda belum terhubung.');
        }

        return view('staff.profil_edit', compact('staf'));
    }

    // 4. Proses Update Profil Diri Sendiri
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_staf' => 'required|string|max:100',
            'no_hp'     => 'required|string|max:20',
            'email'     => 'required|email',
            'password'  => 'nullable|min:6', // Password boleh kosong jika tidak mau diganti
        ]);

        // Update Tabel Staf
        DB::table('stafs')
            ->where('username', $user->username)
            ->update([
                'nama_staf'  => $request->nama_staf,
                'no_hp'      => $request->no_hp,
                'email'      => $request->email,
                'updated_at' => now(),
            ]);

        // Update Password di Tabel Akun (Jika diisi)
        if ($request->filled('password')) {
            DB::table('akuns')
                ->where('username', $user->username)
                ->update([
                    'password'   => Hash::make($request->password),
                    'updated_at' => now(),
                ]);
        }

        return redirect()->route('staff.menu')->with('sukses', 'Profil berhasil diperbarui!');
    }

    public function presensistaff()
    {
        return view('staff_shif_kerja');
    }

    public function checkStaf(Request $request)
    {
        $request->validate([
            'staf_id' => 'required'
        ]);

        // Cari staf berdasarkan primary key (id_staf)
        $staf = Staf::find($request->staf_id);

        if ($staf) {
            return response()->json([
                'status' => 'success',
                'data' => $staf
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'ID Staf tidak ditemukan.'
            ], 404);
        }
    }

    // Method untuk menyimpan presensi (Aksi tombol di dalam modal)
   public function storePresensi(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'staf_id_presensi' => 'required|exists:stafs,id_staf',
            'jenis_presensi'   => 'required|in:masuk,keluar'
        ]);

        // 2. Cari Staf
        $staf = Staf::find($request->staf_id_presensi);

        // Ambil jam sekarang (0 - 23)
        $jamSekarang = Carbon::now()->hour;

        // 3. Logika Update Status
        if ($request->jenis_presensi == 'masuk') {

            // --- VALIDASI JAM SHIFT (LOGIKA BARU) ---

            // Aturan Shift Pagi (07:00 - 19:00)
            if (strtolower($staf->jadwal) == 'pagi') {
                // Jika jam sekarang KURANG DARI 7 atau LEBIH DARI/SAMA DENGAN 19
                if ($jamSekarang < 7 || $jamSekarang >= 19) {
                    return redirect()->back()->with('error', 'Gagal: Maaf, Shift Pagi hanya bisa presensi antara jam 07:00 - 19:00.');
                }
            }
            // Aturan Shift Malam (19:00 - 07:00)
            elseif (strtolower($staf->jadwal) == 'malam') {
                // Logika Malam: Boleh masuk jika jam >= 19 (19,20,...) ATAU jam < 7 (0,1,2...6)
                // Jadi, dilarang masuk jika jam antara 07:00 sampai 18:59
                if ($jamSekarang >= 7 && $jamSekarang < 19) {
                    return redirect()->back()->with('error', 'Gagal: Maaf, Shift Malam hanya bisa presensi antara jam 19:00 - 07:00.');
                }
            }

            // --- SELESAI VALIDASI JAM ---

            // Cek jika status user saat ini mengandung kata 'aktif'
            if (str_contains(strtolower($staf->status), 'aktif') && !str_contains(strtolower($staf->status), 'non')) {
                 return redirect()->back()->with('error', 'Gagal: Anda sudah berstatus Aktif sebelumnya.');
            }

            $staf->status = 'Aktif';
            $pesan = "Selamat bekerja, " . $staf->nama_staf . "! (Shift: " . $staf->jadwal . ")";

        } else {
            // Logika Keluar (Tidak perlu validasi jam, karena pulang bisa kapan saja jika lembur/pulang cepat)

            if (str_contains(strtolower($staf->status), 'non')) {
                return redirect()->back()->with('error', 'Gagal: Anda sudah Pulang (Non Aktif) sebelumnya.');
            }

            $staf->status = 'Non Aktif';
            $pesan = "Terima kasih, " . $staf->nama_staf . ". Hati-hati di jalan!";
        }

        // 4. Simpan ke Database
        $staf->save();

        return redirect()->back()->with('success', $pesan);
    }
    public function menu()
    {
        $stafAktif = Staf::where('status', 'LIKE', '%aktif%')
                         ->where('status', 'NOT LIKE', '%non%')
                         ->first();

        // Sesuaikan 'staff_menu' dengan nama file blade menu staff Anda
        return view('staff_menu', compact('stafAktif'));
    }
}
