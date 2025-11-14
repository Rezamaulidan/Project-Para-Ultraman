<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('input_data_kamar');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no_kamar' => 'required|integer|unique:kamars,no_kamar',
            'foto_kamar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string',
            'ukuran' => 'required|string',
            'harga' => 'required|numeric',
            'tipe_kamar' => 'required|string',
            'fasilitas' => 'required|string',
            'lantai' => 'required|integer',
        ]);

        // Handle file upload
        if ($request->hasFile('foto_kamar')) {
            $image = $request->file('foto_kamar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/kamar'), $imageName);
            $validatedData['foto_kamar'] = 'images/kamar/' . $imageName;
        }

        Kamar::create($validatedData);

        return redirect()->route('pemilik.datakamar')->with('success', 'Data kamar berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function infoKamarDetail($nomor)
    {
        // 1. Ambil data kamar dari database menggunakan nomor kamar.
        // Ganti 'nomor_kamar' jika nama kolom di DB Anda berbeda (misalnya 'id').
        $kamar = Kamar::where('nomor_kamar', $nomor)->first(); 

        // 2. Cek jika kamar tidak ditemukan (opsional, tapi disarankan)
        if (!$kamar) {
            // Jika kamar tidak ada, kembalikan response 404
            abort(404, 'Data Kamar Tidak Ditemukan.');
        }

        // 3. Kirim data kamar ($kamar) ke view info_data_kamar.blade.php
        return view('info_data_kamar', compact('kamar'));
    }
    
    public function show(kamar $kamar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(kamar $kamar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, kamar $kamar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(kamar $kamar)
    {
        //
    }
}
