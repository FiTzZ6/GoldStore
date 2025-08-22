<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Pelanggan;

class CariPelangganController extends Controller
{
    public function index()
    {
        return view('datamaster.caripelanggan');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('query');

        $pelanggan = Pelanggan::where('namapelanggan', 'LIKE', "%{$keyword}%")
                        ->orWhere('kdpelanggan', 'LIKE', "%{$keyword}%")
                        ->get();

        return response()->json($pelanggan);
    }
}