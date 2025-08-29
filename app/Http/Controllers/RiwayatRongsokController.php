<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class RiwayatRongsokController extends Controller
{
    public function index()
    {
        return view('barang.rongsok.riwayatrongsok');
    }
}