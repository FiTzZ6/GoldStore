<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LPPenjualanBatalJualController extends Controller
{
    public function index()
    {
        return view('laporan.laporanpenjualan.bataljual');
    }

}