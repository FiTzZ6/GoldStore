<?php

namespace App\Http\Controllers;
use App\Models\Pelanggan;
use App\Models\Staff;
use App\Models\Barang;
use App\Models\StokJual;
use App\Models\TransPenjualan;
use Illuminate\Http\Request;

class TransaksiJualController extends Controller
{
    public function index()
    {
        $stokjual = StokJual::with('barang')->get();
        $barang = Barang::all();
        $staff = Staff::all();
        $pelanggan = Pelanggan::all();
        return view('jual.transpenjualan', compact('pelanggan', 'staff', 'stokjual'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // dd($request->all());

        // cek isi request
        // dd($data);

        // kalau field items kosong/null, $items akan null
        $items = json_decode($data['items'] ?? '[]', true);

        if (empty($items)) {
            return back()->with('error', 'Data item kosong atau tidak valid');
        }

        foreach ($items as $item) {
            TransPenjualan::create([
                'nofaktur' => $data['nofaktur'],
                'namastaff' => $data['namastaff'],
                'namapelanggan' => $data['namapelanggan'],
                'nohp' => $data['nohp'],
                'alamat' => $data['alamat'],
                'barcode' => $item['code'],
                'namabarang' => $item['name'],
                'harga' => $item['price'],
                'ongkos' => $item['fee'],
                'total' => $item['total'],
                'pembayaran' => $data['pembayaran'],
            ]);

            $stok = StokJual::where('barcode', $item['code'])->first();
            if ($stok) {
                $stok->stok -= $item['quantity'];
                $stok->save();
            }
        }

        return redirect()->route('transpenjualan')
            ->with('success', 'Transaksi berhasil disimpan');
    }




}