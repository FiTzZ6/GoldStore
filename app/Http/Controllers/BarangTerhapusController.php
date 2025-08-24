<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangTerhapusController extends Controller
{
    public function index(Request $request)
    {
        return view('barang.barangterhapus');
    }
}