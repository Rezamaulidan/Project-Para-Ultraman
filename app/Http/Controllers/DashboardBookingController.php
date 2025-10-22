<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardBookingController extends Controller
{
    public function booking()
    {
        return view('dashboard_booking');
    }
}
