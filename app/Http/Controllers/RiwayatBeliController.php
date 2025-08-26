<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class RiwayatBeliController extends Controller
{
    public function index()
    {
        return view('beli.riwayat_beli');
    }
}