<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangLambatLakuController extends Controller
{
    public function index(Request $request)
    {
        return view('laporan.laporanbarang.baranglambatlaku');
    }
}