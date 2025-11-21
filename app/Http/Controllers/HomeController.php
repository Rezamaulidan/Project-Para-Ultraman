<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Selalu ambil semua kamar (untuk tamu & user)
        $kamars = Kamar::orderBy('lantai')
                       ->orderBy('no_kamar')
                       ->get();

        $pemilik = \App\Models\PemilikKos::first();

        return view('home', compact('kamars', 'pemilik'));
    }

    public function showPenyewa($no_kamar)
    {
        $kamar = Kamar::where('no_kamar', $no_kamar)
                      ->where('status', 'tersedia')
                      ->firstOrFail();

        return view('detail_kamar_penyewa', compact('kamar'));
    }
}
