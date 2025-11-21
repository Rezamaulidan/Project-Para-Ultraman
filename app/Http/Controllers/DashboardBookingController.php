<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\PemilikKos;

class DashboardBookingController extends Controller
{
    public function booking()
    {
        $kamars = Kamar::all(); // Ambil semua data kamar
        $pemilik = PemilikKos::first(); // Ambil data pemilik (asumsi hanya 1 pemilik)

        return view('dashboard_booking', compact('kamars', 'pemilik'));
    }
}
