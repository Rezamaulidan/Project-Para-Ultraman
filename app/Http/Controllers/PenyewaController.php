<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use App\Models\Kamar;
use App\Http\Requests\StorePenyewaRequest;
use App\Http\Requests\UpdatePenyewaRequest;
use Illuminate\Support\Facades\Auth;

class PenyewaController extends Controller
{
    public function showKeamanan()
    {
        return view('informasi_keamanan_penyewa');
    }

    public function showPembayaran()
    {
    // Ambil data penyewa untuk ditampilkan di halaman pembayaran
    $penyewa = Penyewa::where('username', Auth::user()->username)->firstOrFail();

    return view('menu_pembayaran', [
        'penyewa' => $penyewa
        ]);
    }

    public function showKamar()
    {
        // 1. (Opsional) Tetap ambil data penyewa untuk memastikan user yang login valid
        // Meskipun kita tidak pakai data $penyewa->no_kamar sekarang
        $penyewa = Penyewa::where('username', Auth::user()->username)->firstOrFail();

        // 2. HARDCODE: Langsung ambil data Kamar nomor 1
        // Kita mengabaikan apakah penyewa ini benar-benar menghuni kamar 1 atau tidak
        $kamar = Kamar::where('no_kamar', '1')->firstOrFail();

        // 3. Kirim data kamar ke view
        return view('informasi_kamar_penyewa', [
            'kamar' => $kamar
        ]);
    }

    public function showInformasi()
    {
        // 2. Dapatkan Akun yang sedang login
        $akun = Auth::user();

        // 3. Cari data Penyewa berdasarkan 'username' dari Akun
        //    Gunakan firstOrFail() agar otomatis error jika data tidak ada
        $penyewa = Penyewa::where('username', $akun->username)->firstOrFail();

        // 4. Kirim data 'penyewa' tersebut ke view
        return view('informasi_penyewa', [
            'penyewa' => $penyewa
        ]);
    }

    public function editInformasi()
    {
        // Logika yang sama, ambil data penyewa saat ini
        $penyewa = Penyewa::where('username', Auth::user()->username)->firstOrFail();

        // Kirim ke view edit
        return view('edit_informasi_penyewa', [
            'penyewa' => $penyewa
        ]);
    }

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenyewaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Penyewa $penyewa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penyewa $penyewa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenyewaRequest $request, Penyewa $penyewa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penyewa $penyewa)
    {
        //
    }
}
