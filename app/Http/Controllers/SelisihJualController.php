<?php

namespace App\Http\Controllers;

use App\Models\BatalJual;
use Illuminate\Http\Request;

class SelisihJualController extends Controller
{
    public function index()
    {
        $selisih = BatalJual::join('stokjual', 'trans_batal_jual.fakturjual', '=', 'stokjual.nofaktur')
            ->whereColumn('trans_batal_jual.harga', '!=', 'stokjual.hargajual')
            ->select(
                'trans_batal_jual.fakturbataljual',
                'trans_batal_jual.fakturjual',
                'trans_batal_jual.barcode',
                'stokjual.hargajual as harga_jual_asli',
                'trans_batal_jual.harga as harga_batal',
                'stokjual.ongkos',
                \DB::raw('(stokjual.hargajual - trans_batal_jual.harga) as selisih')
            )
            ->get();

        return view('jual.selisihjual', compact('selisih'));
    }

    public function destroy($id)
    {
        $batal = BatalJual::findOrFail($id);
        $batal->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}