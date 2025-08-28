<?php

namespace App\Http\Controllers;
use App\Models\Pelanggan;
use App\Models\Staff;

class TransaksiJualController extends Controller
{
    public function index()
    {
        $staff = Staff::all();
        $pelanggan = Pelanggan::all();
        return view('jual.transpenjualan', compact('pelanggan','staff'));
    }
}