<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangTerhapusController extends Controller
{
    public function index(Request $request)
    {
        $barangTerhapus = \App\Models\BarangTerhapus::all();
        return view('barang.barangterhapus', compact('barangTerhapus'));
    }
}