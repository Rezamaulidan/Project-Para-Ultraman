<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardBookingController extends Controller
{
    public function booking()
    {
        // Pastikan Anda sudah membuat file view ini
        return view('dashboard_booking');
    }
}
