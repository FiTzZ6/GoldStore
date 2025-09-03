<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PersediaanBarangController extends Controller
{
    public function index()
    {
        return view('laporan.laporanbarang.persediaanbarang');
    }
}