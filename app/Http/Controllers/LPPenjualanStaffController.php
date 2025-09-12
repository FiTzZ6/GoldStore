<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LPPenjualanStaffController extends Controller
{
    public function index()
    {
        return view('laporan.laporanpenjualan.penjualanstaff');
    }

}