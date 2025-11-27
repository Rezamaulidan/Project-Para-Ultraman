<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Untuk upload KTP
use Illuminate\Support\Facades\DB;

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
        'jenis_transaksi' => 'booking',
        'status_booking'  => 'pending',
        'tanggal'         => $request->tanggal_mulai,
        'nominal'         => $totalNominal,
        'durasi_sewa'     => $request->durasi_sewa,
        'keterangan'      => 'Durasi: ' . $request->durasi_sewa . ' Bulan',
        ]);

        return redirect()->route('penyewa.dashboard')->with('success', 'Permintaan terkirim!');
    }

    /**
     * [PEMILIK] Menampilkan Daftar Permohonan Booking (Status: Pending)
     */
    public function daftarPermohonan()
    {
        // Ambil semua booking yang statusnya 'pending'
        $bookings = Booking::with(['penyewa', 'kamar'])
                    ->where('status_booking', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Arahkan ke view khusus pemilik
        return view('permohonan_booking', compact('bookings'));
    }

    /**
     * [PEMILIK] Terima Booking (Status -> confirmed)
     */
    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);

        // Ubah status
        $booking->status_booking = 'confirmed';
        $booking->save();

        return redirect()->back()->with('success', 'Booking diterima! Menunggu pembayaran penyewa.');
    }

    /**
     * [PEMILIK] Tolak Booking (Status -> rejected)
     */
    public function rejectBooking($id)
    {
        $booking = Booking::findOrFail($id);

        // Ubah status
        $booking->status_booking = 'rejected';
        $booking->save();

        // Opsional: Jika ditolak, kembalikan status kamar jadi 'tersedia' jika sebelumnya diubah (tapi di tahap pending biasanya kamar belum di-lock penuh)

        return redirect()->back()->with('success', 'Permohonan booking ditolak.');
    }

    public function cancelBooking($id)
    {
    $booking = Booking::findOrFail($id);

    // Validasi: Hanya bisa cancel jika status confirmed
    // Jika status sudah 'lunas', sebaiknya tidak bisa dicancel sembarangan lewat tombol ini
    if ($booking->status_booking == 'confirmed') {

        $booking->status_booking = 'rejected'; // Ubah jadi rejected

        // Tambahkan catatan kenapa dibatalkan (Opsional, agar tidak lupa)
        $booking->keterangan = $booking->keterangan . ' [Dibatalkan Pemilik: Belum ada pembayaran]';

        $booking->save();

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan. Kamar kembali tersedia untuk orang lain.');
    }

    return redirect()->back()->with('error', 'Hanya booking yang statusnya DISETUJUI (belum lunas) yang bisa dibatalkan.');
    }

    public function showPaymentPage($id)
    {
        $user = Auth::user();

        // Pastikan booking milik user yang login & statusnya confirmed
        $booking = Booking::with(['kamar', 'penyewa'])
                    ->where('id_booking', $id)
                    ->where('username', $user->username)
                    ->where('status_booking', 'confirmed')
                    ->firstOrFail();

        return view('payment_simulation', compact('booking'));
    }

    /**
     * Proses Pembayaran (Simulasi Auto Lunas)
     */
    public function processPayment($id)
    {
        // Gunakan DB Transaction agar data konsisten (Booking lunas & Kamar terisi)
        DB::transaction(function () use ($id) {
            $booking = Booking::findOrFail($id);

            // 1. Update Status Booking
            $booking->status_booking = 'lunas';
            $booking->save();

            // 2. Update Status Kamar menjadi Terisi
            $kamar = Kamar::where('no_kamar', $booking->no_kamar)->first();
            if ($kamar) {
                $kamar->status = 'terisi';
                $kamar->save();
            }
        });

        return redirect()->route('penyewa.dashboard')
            ->with('success', 'Pembayaran Berhasil!');
    }
}
