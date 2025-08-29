<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiwayatKetidaksesuaianController extends Controller
{
    public function index()
    {
        return view('barang.rongsok.riwayatketidaksesuai');
    }
}