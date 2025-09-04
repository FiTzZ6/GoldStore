<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembelianUmumController extends Controller
{
    public function index(Request $request)
    {
        return view('laporan.laporanpembelian.pembelianumum');
    }
}