<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HapusBarangController extends Controller
{
    public function index()
    {
        return view('laporan.laporanbarang.historyhapus');
    }
}