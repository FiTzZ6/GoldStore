<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatPindahBarangController extends Controller
{
    public function index()
    {
        $riwayat = DB::table('pindah_baki as pb')
            ->join('barang as b', 'pb.barcode', '=', 'b.barcode')
            ->select(
                'pb.created_at',
                'pb.fakturpindahbaki',
                'pb.barcode',
                'b.namabarang',
                'pb.kdbaki_asal',
                'pb.kdbaki_tujuan'
            )
            ->orderBy('pb.created_at', 'desc')
            ->get();

        return view('barang.pindahbarang.riwayatpindahbarang', compact('riwayat'));
    }
}
