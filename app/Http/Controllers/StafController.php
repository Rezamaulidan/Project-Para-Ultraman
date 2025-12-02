<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Wajib import DB
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
}
