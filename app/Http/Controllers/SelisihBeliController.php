<?php

namespace App\Http\Controllers;

use App\Models\TransBeli;
use App\Models\TransBatalBeli;
use Illuminate\Http\Request;

class SelisihBeliController extends Controller
{
    public function index()
    {
        // join sederhana antara beli dan batal beli
        $data = TransBatalBeli::join('trans_beli', 'trans_beli.nofakturbeli', '=', 'trans_batal_beli.nofakturbeli')
            ->select(
                'trans_beli.nofakturbeli',
                'trans_batal_beli.nofakturbatalbeli',
                'trans_beli.barcode',
                'trans_beli.hargabaru as hargabeli',
                'trans_batal_beli.hargabatalbeli',
                \DB::raw('(trans_beli.hargabaru - trans_batal_beli.hargabatalbeli) as selisih')
            )
            ->get();

        return view('beli.selisih_belibatal', compact('data'));
    }
}
