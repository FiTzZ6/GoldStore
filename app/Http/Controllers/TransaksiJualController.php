<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\BarangTerjual;
use App\Models\Staff;
use App\Models\Barang;
use App\Models\StokJual;
use App\Models\TransPenjualan;
use App\Models\Pesanan;
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
        // 🔹 validasi awal
        $request->validate([
            'typepesanan' => 'required|in:umum,pesanan',
        ]);

        $data = $request->all();
        $items = json_decode($data['items'] ?? '[]', true);

        if (empty($items)) {
            return back()->with('error', 'Data item kosong atau tidak valid');
        }

        // 🔹 validasi nofaktur duplikat
        if (TransPenjualan::where('nofaktur', $data['nofaktur'])->exists()) {
            return back()->with('error', 'Nomor faktur sudah ada, silakan generate ulang.');
        }

        if ($data['typepesanan'] === 'umum') {
            // ======================
            // 🔹 TRANSAKSI UMUM
            // ======================
            foreach ($items as $item) {
                TransPenjualan::create([
                    'nofaktur' => $data['nofaktur'],
                    'namastaff' => $data['namastaff'],
                    'typepesanan' => 'umum',
                    'namapelanggan' => $data['namapelanggan'] ?? '-',
                    'nohp' => $data['nohp'] ?? '-',
                    'alamat' => $data['alamat'] ?? '-',
                    'barcode' => $item['code'],
                    'namabarang' => $item['name'],
                    'harga' => $item['price'],
                    'ongkos' => $item['fee'],
                    'total' => $item['total'],
                    'quantity' => $item['quantity'] ?? 1,
                    'pembayaran' => $data['pembayaran'],
                ]);

                // update stok + simpan barang terjual
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

                    // 🔹 Update stok di tabel StokJual
                    $stok->stok -= $item['quantity'];                // stok berkurang
                    $stok->stokterjual += $item['quantity'];         // stok terjual bertambah
                    $stok->stoktotal = $stok->stok + $stok->stokterjual; // hitung ulang stok total
                    $stok->save();
                }
            }
        } else {
            // ======================
            // 🔹 PESANAN
            // ======================
            $request->validate([
                'tglpesan' => 'required|date',
                'tglambil' => 'required|date|after_or_equal:tglpesan',
            ], [
                'tglpesan.required' => 'Tanggal pesan wajib diisi',
                'tglambil.required' => 'Tanggal ambil wajib diisi',
                'tglambil.after_or_equal' => 'Tanggal ambil harus >= tanggal pesan',
            ]);

            foreach ($items as $item) {
                \App\Models\Pesanan::create([
                    'nofakturpesan' => $data['nofaktur'],
                    'namabarang' => $item['name'],
                    'barcode' => $item['code'],
                    'tglpesan' => $data['tglpesan'],
                    'tglambil' => $data['tglambil'],
                    'staff' => $data['namastaff'],
                    'namapemesan' => $data['namapelanggan'],
                    'alamatpemesan' => $data['alamat'],
                    'notelp' => $data['nohp'],
                    'quantity' => $item['quantity'] ?? 1,
                    'harga' => $item['price'],
                    'ongkos' => $item['fee'],
                    'total' => $item['total'],
                ]);
            }
        }

        return redirect()->route('transpenjualan')
            ->with('success', 'Transaksi berhasil disimpan');
    }

    public function daftarPesanan()
    {
        $pesanan = Pesanan::orderBy('tglpesan', 'desc')->get();

        return view('jual.daftar_pesanan', compact('pesanan'));
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

            $newNumber = $last
                ? str_pad(intval(substr($last->nofaktur, -4)) + 1, 4, '0', STR_PAD_LEFT)
                : '0001';

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

        return view('jual.struk', compact(
            'nofaktur',
            'items',
            'tanggal',
            'total',
            'pelanggan',
            'staff',
            'pembayaran'
        ));
    }
}
