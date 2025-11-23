<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Tambahkan facade File untuk hapus gambar

class KamarController extends Controller
{
    // ... (method index, create, store, infoKamarDetail biarkan saja seperti sebelumnya) ...

    public function index()
    {
        $kamars = Kamar::orderBy('lantai')->orderBy('no_kamar', 'asc')->get();
        return view('data_kamar_pemilik', compact('kamars'));
    }

    public function create()
    {
        return view('input_data_kamar');
    }

    public function store(Request $request)
    {
        // ... (kode store Anda sudah benar) ...
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

    public function edit($no_kamar)
    {
        $kamar = Kamar::where('no_kamar', $no_kamar)->firstOrFail();
        return view('edit_data_kamar', compact('kamar'));
    }

    // --- BAGIAN INI YANG MENANGANI UPDATE ---
    public function update(Request $request, $no_kamar)
    {
        $kamar = Kamar::where('no_kamar', $no_kamar)->firstOrFail();

        // Validasi
        $validatedData = $request->validate([
            'lantai' => 'required|integer|min:1|max:10',
            'status' => 'required|in:tersedia,terisi',
            'harga' => 'required|numeric',
            'tipe_kamar' => 'required|in:kosongan,basic,ekslusif',
            'fasilitas' => 'nullable|string',
            'ukuran' => 'required|string|max:50',
            // Gunakan nullable agar jika tidak upload foto baru, foto lama tidak hilang/error
            'foto_kamar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        // Cek apakah ada file foto baru yang diupload
        if ($request->hasFile('foto_kamar')) {
            // Hapus foto lama jika ada
            if ($kamar->foto_kamar && File::exists(public_path($kamar->foto_kamar))) {
                File::delete(public_path($kamar->foto_kamar));
            } elseif ($kamar->foto_kamar && file_exists(public_path($kamar->foto_kamar))) {
                unlink(public_path($kamar->foto_kamar));
            }

            // Simpan foto baru
            $image = $request->file('foto_kamar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/kamar'), $imageName);
            $validatedData['foto_kamar'] = 'images/kamar/' . $imageName;
        }

        // Lakukan Update
        $kamar->update($validatedData);

        // Respon JSON untuk AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Data kamar berhasil diperbarui!'
            ]);
        }

        // Fallback jika tidak pakai AJAX
        return redirect()->route('pemilik.datakamar')->with('success', 'Data kamar berhasil diperbarui!');
    }

    public function destroy($no_kamar)
    {
        $kamar = Kamar::where('no_kamar', $no_kamar)->firstOrFail();

        if ($kamar->foto_kamar && File::exists(public_path($kamar->foto_kamar))) {
            File::delete(public_path($kamar->foto_kamar));
        }

        $kamar->delete();

        return response()->json(['success' => true]);
    }
}