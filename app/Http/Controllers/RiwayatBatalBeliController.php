<?php

namespace App\Http\Controllers;

use App\Models\TransBatalBeli;
use Illuminate\Http\Request;

class RiwayatBatalBeliController extends Controller
{
    public function index()
    {
        // Ambil semua data batal beli (bisa pakai pagination juga)
        $riwayat = TransBatalBeli::orderBy('created_at', 'desc')->get();

        return view('beli.riwayat_batalbeli', compact('riwayat'));
    }
}
