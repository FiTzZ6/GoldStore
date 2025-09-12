<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanTransKasController extends Controller
{
    public function index()
    {
        return view('laporan.laporankas');
    }

}