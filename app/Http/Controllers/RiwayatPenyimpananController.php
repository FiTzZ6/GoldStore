<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class RiwayatPenyimpananController extends Controller
{
    public function index()
    {
        return view('barang.cucisepuh.riwayatpenyimpanan');
    }
}