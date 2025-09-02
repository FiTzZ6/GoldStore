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
         $nofaktur = $this->generateNoFaktur();
        return view('jual.transpenjualan', compact('pelanggan', 'staff', 'stokjual','nofaktur'));
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
                'created_at' => now(),
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

    private function generateNoFaktur()
    {
        $prefix = 'FJ-';
        $tanggal = now()->format('ymd'); // contoh: 250902
        $last = TransPenjualan::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->nofaktur, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $tanggal . '-' . $newNumber;
    }

    public function struk($nofaktur)
    {
        $items = TransPenjualan::where('nofaktur', $nofaktur)->get();

        if ($items->isEmpty()) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $tanggal = $items->first()->created_at;
        $total = $items->sum('total');
        $pelanggan = $items->first()->namapelanggan ?? '-';
        $staff = $items->first()->namastaff ?? '-';
        $pembayaran = $items->first()->pembayaran ?? '-';

        return view('jual.struk', compact('nofaktur', 'items', 'tanggal', 'total', 'pelanggan', 'staff', 'pembayaran'));
    }



}