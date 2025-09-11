<?php

namespace App\Http\Controllers;

use App\Models\BarangTerjual;
use Illuminate\Http\Request;

class BarangTerjualController extends Controller
{
    public function index()
    {
        $barangTerjual = BarangTerjual::orderBy('created_at', 'desc')->get();
        return view('barang.BarangTerjual', compact('barangTerjual'));
    }
}