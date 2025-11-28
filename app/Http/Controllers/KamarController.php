<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Tambahkan facade File untuk hapus gambar

class KamarController extends Controller
{
    // ... (method index, create, store, infoKamarDetail biarkan saja seperti sebelumnya) ...

    public function index()
    {
        // 1. Ambil semua kamar, urutkan
        $kamars = Kamar::orderBy('lantai')->orderBy('no_kamar', 'asc')->get();


        // 2. Cek status ketersediaan kamar berdasarkan booking yang LUNAS
        $kamarsWithStatus = $kamars->map(function ($kamar) {

            // Cek apakah ada booking yang statusnya 'lunas' untuk kamar ini
            // Jika ada, anggap kamar TERISI.
            $isTerisi = Booking::where('no_kamar', $kamar->no_kamar)
                               ->where('status_booking', 'lunas')
                               ->exists();

            // Tambahkan properti status_ketersediaan ke objek kamar
            $kamar->status_ketersediaan = $isTerisi ? 'Terisi' : 'Tersedia';

            return $kamar;
            return view('info_data_kamar', compact('kamar', 'statusKetersediaan'));
        });

        $kamars = Kamar::orderBy('lantai')->orderBy('no_kamar', 'asc')->get();
        return view('data_kamar_pemilik', ['kamars' => $kamarsWithStatus]);
        // return view('home', compact('kamars'));

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
        $validatedData['status'] = 'tersedia'; // Beri nilai default
        Kamar::create($validatedData);
        return redirect()->route('pemilik.datakamar')->with('success', 'Data kamar berhasil disimpan!');
    }

    public function infoKamarDetail($nomor)
    {
        // 1. Ambil data kamar berdasarkan nomor
        $kamar = Kamar::where('no_kamar', $nomor)->firstOrFail();

        // 2. Cek status ketersediaan berdasarkan booking yang LUNAS
        // Kita menggunakan Query Builder langsung untuk efisiensi
        $isTerisi = Booking::where('no_kamar', $kamar->no_kamar)
                           ->where('status_booking', 'lunas')
                           ->exists();

        // 3. Tentukan status_ketersediaan baru
        $statusKetersediaan = $isTerisi ? 'Terisi' : 'Tersedia';

        // 4. Kirim variabel kamar dan status barunya ke view
        return view('info_data_kamar', compact('kamar', 'statusKetersediaan'));
    }

    public function edit($no_kamar)
    {

        // 1. Cari SATU kamar berdasarkan no_kamar
        $kamar = Kamar::where('no_kamar', $no_kamar)->firstOrFail();
        
        // 2. Cek status ketersediaan berdasarkan booking yang LUNAS
        $isTerisi = Booking::where('no_kamar', $no_kamar)
                           ->where('status_booking', 'lunas')
                           ->exists();

        // 3. Tentukan status ketersediaan dinamis
        $statusKetersediaan = $isTerisi ? 'terisi' : 'tersedia';
        
        // 4. Kirim variabel kamar dan status barunya ke view
        // Kita akan menggunakan $statusKetersediaan di view
        return view('edit_data_kamar', compact('kamar', 'statusKetersediaan'));

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