<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MerchandiseController extends Controller
{
    public function index()
    {
        $merchandise = Merchandise::paginate(10); // <- pagination 10 per halaman
        $pelanggan = Pelanggan::all();
        return view('barang.merch.index', compact('merchandise', 'pelanggan'));
    }

    public function getPoin($kdpelanggan)
    {
        $pelanggan = Pelanggan::where('kdpelanggan', $kdpelanggan)->first();
        if ($pelanggan) {
            return response()->json(['poin' => $pelanggan->poin]);
        }
        return response()->json(['poin' => 0]);
    }

    public function redeem(Request $request)
    {
        $pelanggan = Pelanggan::where('kdpelanggan', $request->kdpelanggan)->first();
        $merch = Merchandise::findOrFail($request->merch_id);

        if (!$pelanggan) {
            return back()->with('error', 'Pelanggan tidak ditemukan.');
        }
        if ($pelanggan->poin < $merch->poin_harga) {
            return back()->with('error', 'Poin tidak mencukupi.');
        }
        if ($merch->stok <= 0) {
            return back()->with('error', 'Stok merch habis.');
        }

        // Kurangi poin & stok
        $pelanggan->poin -= $merch->poin_harga;
        $pelanggan->save();

        $merch->stok -= 1;
        $merch->save();

        // Simpan riwayat redeem
        DB::table('redeem')->insert([
            'kdpelanggan' => $pelanggan->kdpelanggan,
            'merch_id' => $merch->id,
            'poin_digunakan' => $merch->poin_harga,
            'stok_sisa' => $merch->stok,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Berhasil redeem ' . $merch->nama_merch);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_merch' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'poin_harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        Merchandise::create($request->all());
        return redirect()->back()->with('success', 'Merchandise berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_merch' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'poin_harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $merch = Merchandise::findOrFail($id);
        $merch->update($request->all());

        return redirect()->back()->with('success', 'Merchandise berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $merch = Merchandise::findOrFail($id);
        $merch->delete();
        return redirect()->back()->with('success', 'Merchandise berhasil dihapus!');
    }

    public function redeemHistory(Request $request)
    {
        $query = \DB::table('redeem')
            ->join('pelanggan', 'redeem.kdpelanggan', '=', 'pelanggan.kdpelanggan')
            ->join('merchandise', 'redeem.merch_id', '=', 'merchandise.id')
            ->select('redeem.*', 'pelanggan.namapelanggan', 'merchandise.nama_merch')
            ->orderBy('redeem.created_at', 'desc');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('pelanggan.namapelanggan', 'like', "%$q%")
                    ->orWhere('merchandise.nama_merch', 'like', "%$q%");
            });
        }

        $redeems = $query->paginate(10); // <- pagination di riwayat

        return view('barang.merch.redeem_history', compact('redeems'));
    }
}
