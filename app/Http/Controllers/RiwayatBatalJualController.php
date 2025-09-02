<?php

namespace App\Http\Controllers;

use App\Models\BatalJual;
use Illuminate\Http\Request;

class RiwayatBatalJualController extends Controller
{
    public function index()
    {
        // ambil data batal jual, bisa pakai pagination biar rapi
        $riwayat = BatalJual::orderBy('created_at', 'desc')->paginate(10);

        // kirim ke view
        return view('jual.riwayatbataljual', compact('riwayat'));
    }
}