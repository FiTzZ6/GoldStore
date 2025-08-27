<?php

namespace App\Http\Controllers;

class DaftarPesananController extends Controller
{
    public function index()
    {
        return view('pesanan.daftarpesanan');
    }
}