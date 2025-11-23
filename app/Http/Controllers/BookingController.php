<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Untuk upload KTP

class BookingController extends Controller
{
    /**
     * Menampilkan Form Booking
     */
    public function create($no_kamar)
    {
        // 1. Ambil data kamar yang dipilih
        $kamar = Kamar::where('no_kamar', $no_kamar)->firstOrFail();

        // 2. Ambil data penyewa yang sedang login
        $user = Auth::user();
        $penyewa = Penyewa::where('username', $user->username)->firstOrFail();

        // 3. Cek apakah kamar masih tersedia (double check)
        if (strtolower($kamar->status) != 'tersedia') {
            return redirect()->back()->with('error', 'Maaf, kamar ini sudah tidak tersedia.');
        }

        // 4. Tampilkan view form
        return view('form_booking', compact('kamar', 'penyewa'));
    }

    /**
     * Menyimpan Data Booking
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'no_kamar'    => 'required|exists:kamars,no_kamar',
            'tanggal_mulai'=> 'required|date|after_or_equal:today',
            'durasi_sewa' => 'required|integer|min:1|max:12', // Durasi 1-12 bulan
            'foto_ktp'    => 'required|image|mimes:jpeg,png,jpg|max:2048', // Wajib upload KTP
        ]);

        $user = Auth::user();
        $penyewa = Penyewa::where('username', $user->username)->firstOrFail();
        $kamar = Kamar::where('no_kamar', $request->no_kamar)->firstOrFail();

        // 2. Upload Foto KTP (Update data penyewa jika belum punya KTP)
        // Simpan foto KTP di folder 'ktp_penyewa'
        $ktpPath = $request->file('foto_ktp')->store('ktp_penyewa', 'public');

        // Update KTP di tabel penyewa (agar tersimpan di profil juga)
        $penyewa->foto_ktp = $ktpPath;
        $penyewa->save();

        // 3. Hitung Total Nominal (Harga Kamar x Durasi)
        $totalNominal = $kamar->harga * $request->durasi_sewa;

        // 4. Buat Data Booking
        Booking::create([
            'username'        => $user->username,
            'no_kamar'        => $request->no_kamar,
            'jenis_transaksi' => 'booking', // Ini adalah booking baru
            'status_booking'  => 'pending', // Menunggu konfirmasi pemilik
            'tanggal'         => $request->tanggal_mulai, // Tanggal mulai ngekos
            'nominal'         => $totalNominal,
            'keterangan'      => 'Durasi: ' . $request->durasi_sewa . ' Bulan',
        ]);

        // 5. Redirect ke Dashboard / Halaman Sukses
        return redirect()->route('penyewa.dashboard')->with('success', 'Permintaan booking berhasil dikirim! Menunggu konfirmasi pemilik.');
    }
}
