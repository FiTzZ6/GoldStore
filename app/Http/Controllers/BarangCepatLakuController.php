<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangCepatLakuController extends Controller
{
    public function index(Request $request)
    {
        return view('laporan.laporanbarang.barangcepatlaku');
    }
}