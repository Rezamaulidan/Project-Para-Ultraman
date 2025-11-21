<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PemilikKos;
use Illuminate\Http\Request;

class PemilikKosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pastikan $user berisi data lengkap (termasuk email dan no_hp)
        $user = Auth::user();

        // Kirim data user ke view
        return view('profil_pemilik', compact('user'));
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
