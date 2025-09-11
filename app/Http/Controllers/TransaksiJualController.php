<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\BarangTerjual;
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

        $nofaktur = $this->generateNoFaktur(); // nomor faktur otomatis

        return view('jual.transpenjualan', compact('pelanggan', 'staff', 'stokjual', 'nofaktur'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $items = json_decode($data['items'] ?? '[]', true);

        if (empty($items)) {
            return back()->with('error', 'Data item kosong atau tidak valid');
        }

        if (TransPenjualan::where('nofaktur', $data['nofaktur'])->exists()) {
            return back()->with('error', 'Nomor faktur sudah ada, silakan generate ulang.');
        }

        foreach ($items as $item) {
            // simpan transaksi penjualan
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
                'quantity' => $item['quantity'] ?? 1,
                'pembayaran' => $data['pembayaran'],
                'created_at' => now(),
            ]);

            // ambil data barang dari tabel stokjual/barang
            $stok = StokJual::with('barang')->where('barcode', $item['code'])->first();

            if ($stok && $stok->barang) {
                BarangTerjual::create([
                    'fakturbarangterjual' => $data['nofaktur'],
                    'namabarang' => $item['name'],
                    'barcode' => $item['code'],
                    'kdbaki' => $stok->barang->kdbaki ?? '-',
                    'berat' => $stok->barang->berat ?? 0,
                    'kadar' => $stok->barang->kadar ?? 0,
                    'harga' => $item['price'],
                    'namastaff' => $data['namastaff'],
                    'tanggalterjual' => now()->toDateString(),
                ]);
            } else {
                \Log::warning("Barang dengan barcode {$item['code']} tidak ditemukan di stokjual/barang");
            }

            // update stok
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
        $tanggal = now()->format('ymd'); // YYMMDD

        do {
            $random4 = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

            $last = TransPenjualan::where('nofaktur', 'like', $prefix . $tanggal . '-' . $random4 . '-%')
                ->orderBy('id', 'desc')
                ->first();

            $newNumber = $last ? str_pad(intval(substr($last->nofaktur, -4)) + 1, 4, '0', STR_PAD_LEFT) : '0001';

            $nofaktur = $prefix . $tanggal . '-' . $random4 . '-' . $newNumber;
        } while (TransPenjualan::where('nofaktur', $nofaktur)->exists());

        return $nofaktur;
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
