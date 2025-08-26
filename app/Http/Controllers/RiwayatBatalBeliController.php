<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class RiwayatBatalBeliController extends Controller
{
    public function index()
    {
        return view('beli.riwayat_batalbeli');
    }
}