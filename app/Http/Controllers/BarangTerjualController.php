<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class BarangTerjualController extends Controller
{
    public function index(Request $request)
    {
        return view('barang.BarangTerjual');
    }
}