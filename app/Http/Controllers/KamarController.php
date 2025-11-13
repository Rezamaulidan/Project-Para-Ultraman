<?php

namespace App\Http\Controllers;

use App\Models\kamar;
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

        kamar::create($validatedData);

        return redirect('/kamar')->back('/datakamarpemilik')->with('success', 'Data kamar berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
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