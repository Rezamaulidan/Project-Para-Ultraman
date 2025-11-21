<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::orderBy('lantai')->orderBy('no_kamar', 'asc')->get();
        return view('data_kamar_pemilik', compact('kamars'));
        return view('home', compact('kamars'));
    }

    public function create()
    {
        return view('input_data_kamar');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no_kamar' => 'required|integer|unique:kamars,no_kamar',
            'foto_kamar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:tersedia,terisi',
            'ukuran' => 'required|string|max:50',
            'harga' => 'required|numeric',
            'tipe_kamar' => 'required|in:kosongan,basic,ekslusif',
            'fasilitas' => 'nullable|string',
            'lantai' => 'required|integer|min:1|max:10',
        ]);

        if ($request->hasFile('foto_kamar')) {
            $image = $request->file('foto_kamar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/kamar'), $imageName);
            $validatedData['foto_kamar'] = 'images/kamar/' . $imageName;
        }

        Kamar::create($validatedData);

        return redirect()->route('pemilik.datakamar')->with('success', 'Data kamar berhasil disimpan!');
    }

    public function infoKamarDetail($nomor)
    {
        $kamar = Kamar::where('no_kamar', $nomor)->firstOrFail();
        return view('info_data_kamar', compact('kamar'));
    }

    // FIXED: Hapus parameter $no_kamar
    public function edit($no_kamar)
    {
        // 2. Cari SATU kamar berdasarkan no_kamar itu.
        //    Gunakan firstOrFail() agar error jika tidak ketemu.
        $kamar = Kamar::where('no_kamar', $no_kamar)->firstOrFail();

        // 3. Kirim SATU variabel 'kamar' (singular) ke view
        return view('edit_data_kamar', compact('kamar'));
    }

    public function update(Request $request, $no_kamar)
    {
        $kamar = Kamar::where('no_kamar', $no_kamar)->firstOrFail();

        $validatedData = $request->validate([
            'lantai' => 'required|integer|min:1|max:10',
            'status' => 'required|in:tersedia,terisi',
            'harga' => 'required|numeric',
            'tipe_kamar' => 'required|in:kosongan,basic,ekslusif',
            'fasilitas' => 'nullable|string',
            'ukuran' => 'required|string|max:50',
            'foto_kamar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_kamar')) {
            if ($kamar->foto_kamar && file_exists(public_path($kamar->foto_kamar))) {
                unlink(public_path($kamar->foto_kamar));
            }

            $image = $request->file('foto_kamar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/kamar'), $imageName);
            $validatedData['foto_kamar'] = 'images/kamar/' . $imageName;
        }

        $kamar->update($validatedData);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Data kamar berhasil diperbarui!']);
        }

        return redirect()->route('pemilik.datakamar')->with('success', 'Data kamar berhasil diperbarui!');
    }

    public function destroy($no_kamar)
    {
        $kamar = Kamar::where('no_kamar', $no_kamar)->firstOrFail();

        if ($kamar->foto_kamar && file_exists(public_path($kamar->foto_kamar))) {
            unlink(public_path($kamar->foto_kamar));
        }

        $kamar->delete();

        return response()->json(['success' => true]);
    }
}
