<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pakai DB Facade biar aman

class StaffPenyewaController extends Controller
{
    public function index()
    {
        // Mengambil data penyewa dari database
        // Kita pakai DB::table agar tidak error Model seperti sebelumnya
        $penyewa = DB::table('penyewas')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff_informasi_penyewa', compact('penyewa'));
    }
}
