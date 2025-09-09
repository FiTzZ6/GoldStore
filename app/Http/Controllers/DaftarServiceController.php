<?php

namespace App\Http\Controllers;

use App\Models\TransService;

class DaftarServiceController extends Controller
{
    public function index()
    {
        // Ambil semua data transaksi, urutkan terbaru
        $data = TransService::latest()->get();

        return view('service.daftarservice', compact('data'));
    }
}
