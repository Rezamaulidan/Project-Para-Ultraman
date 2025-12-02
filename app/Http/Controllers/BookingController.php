<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Menampilkan Form Booking Awal
     */
    public function create($no_kamar)
    {
        $kamar = Kamar::where('no_kamar', $no_kamar)->firstOrFail();
        $user = Auth::user();
        $penyewa = Penyewa::where('username', $user->username)->firstOrFail();

        if (strtolower($kamar->status) != 'tersedia') {
            return redirect()->back()->with('error', 'Maaf, kamar ini sudah tidak tersedia.');
        }

        return view('form_booking', compact('kamar', 'penyewa'));
    }

    /**
     * Menyimpan Data Booking Awal
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kamar'      => 'required|exists:kamars,no_kamar',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi_sewa'   => 'required|integer|min:1|max:12',
            'foto_ktp'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $penyewa = Penyewa::where('username', $user->username)->firstOrFail();
        $kamar = Kamar::where('no_kamar', $request->no_kamar)->firstOrFail();

        // Upload KTP
        $ktpPath = $request->file('foto_ktp')->store('ktp_penyewa', 'public');
        $penyewa->foto_ktp = $ktpPath;
        $penyewa->save();

        // Hitung Nominal
        $totalNominal = $kamar->harga * $request->durasi_sewa;

        // Simpan Booking
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

        return redirect()->route('penyewa.dashboard')->with('success', 'Permintaan terkirim! Menunggu konfirmasi pemilik.');
    }

    // ==========================================================
    // FITUR PEMILIK (Approval)
    // ==========================================================
    public function daftarPermohonan()
    {
        $bookings = Booking::with(['penyewa', 'kamar'])
                    ->where('status_booking', 'pending')
                    ->orderBy('created_at', 'desc')->get();
        return view('permohonan_booking', compact('bookings'));
    }

    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status_booking = 'confirmed';
        $booking->save();
        return redirect()->back()->with('success', 'Booking diterima! Menunggu pembayaran penyewa.');
    }

    public function rejectBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status_booking = 'rejected';
        $booking->save();
        return redirect()->back()->with('success', 'Permohonan booking ditolak.');
    }

    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->status_booking == 'confirmed') {
            $booking->status_booking = 'rejected';
            $booking->keterangan .= ' [Dibatalkan Pemilik]';
            $booking->save();
            return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
        }
        return redirect()->back()->with('error', 'Gagal membatalkan.');
    }


    // ==========================================================
    // FITUR PEMBAYARAN (SIMULASI MANUAL)
    // ==========================================================

    /**
     * 1. Tampilkan Halaman Simulasi Pembayaran
     * (Digunakan saat user klik "Bayar Sekarang" dari Dashboard)
     */
    public function showPaymentPage($id)
    {
        $user = Auth::user();

        $booking = Booking::with(['kamar', 'penyewa'])
                    ->where('id_booking', $id)
                    ->where('username', $user->username)
                    ->whereIn('status_booking', ['confirmed', 'terlambat', 'pending'])
                    ->firstOrFail();

        // Tidak ada token Midtrans, langsung kirim data booking saja
        return view('payment_simulation', compact('booking'));
    }

    /**
     * 2. Proses Pembayaran (Action dari Halaman Payment Simulation)
     * Langsung ubah status jadi LUNAS.
     */
    public function processPayment($id)
    {
        DB::transaction(function () use ($id) {
            $booking = Booking::findOrFail($id);

            // Update Status Booking
            $booking->status_booking = 'lunas';
            $booking->save();

            // Update Status Kamar
            $kamar = Kamar::where('no_kamar', $booking->no_kamar)->first();
            if ($kamar) {
                $kamar->status = 'terisi';
                $kamar->save();
            }
        });

        return redirect()->route('penyewa.dashboard')->with('success', 'Pembayaran Berhasil! Status sewa Anda kini Aktif.');
    }

    /**
     * 3. Bayar Tagihan Bulanan (Action dari Menu Pembayaran Tab 1)
     * Untuk melunasi status 'terlambat'
     */
    public function bayarTagihan($id)
    {
        $booking = Booking::findOrFail($id);

        if (!in_array($booking->status_booking, ['terlambat', 'pending', 'confirmed'])) {
            return back()->with('error', 'Tagihan ini tidak valid atau sudah lunas.');
        }

        // Simulasi Bayar Langsung Lunas
        $booking->status_booking = 'lunas';
        $booking->save();

        // Pastikan kamar terisi
        $kamar = Kamar::where('no_kamar', $booking->no_kamar)->first();
        if ($kamar) {
            $kamar->status = 'terisi';
            $kamar->save();
        }

        return redirect()->route('penyewa.dashboard')->with('success', 'Tagihan berhasil dibayar! Terima kasih.');
    }

    /**
     * 4. Perpanjang Sewa (Action dari Menu Pembayaran Tab 2)
     * Menambah durasi untuk bulan berikutnya.
     */
    public function perpanjangSewa(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'durasi'   => 'required|integer|min:1',
            'no_kamar' => 'required'
        ]);

        // Cari booking terakhir yang aktif (Lunas/Terlambat)
        $bookingTerakhir = Booking::where('username', $user->username)
                            ->where('no_kamar', $request->no_kamar)
                            ->whereIn('status_booking', ['lunas', 'terlambat'])
                            ->latest()
                            ->first();

        if (!$bookingTerakhir) {
            return back()->with('error', 'Data sewa aktif tidak ditemukan.');
        }

        // Hitung Tanggal Mulai Baru (Lanjutkan dari yang lama)
        $tanggalMulaiBaru = Carbon::parse($bookingTerakhir->tanggal)->addMonths($bookingTerakhir->durasi_sewa);

        // Hitung Nominal
        $kamar = Kamar::where('no_kamar', $request->no_kamar)->first();
        $nominalBaru = $kamar->harga * $request->durasi;

        // Buat Booking Baru (Langsung Lunas untuk Simulasi)
        Booking::create([
            'username'        => $user->username,
            'no_kamar'        => $request->no_kamar,
            'jenis_transaksi' => 'pembayaran_sewa',
            'status_booking'  => 'lunas', // Langsung aktif karena simulasi
            'tanggal'         => $tanggalMulaiBaru,
            'durasi_sewa'     => $request->durasi,
            'nominal'         => $nominalBaru,
            'keterangan'      => 'Perpanjangan Sewa ' . $request->durasi . ' Bulan',
        ]);

        return redirect()->route('penyewa.dashboard')->with('success', 'Sewa berhasil diperpanjang ' . $request->durasi . ' bulan!');
    }
}
