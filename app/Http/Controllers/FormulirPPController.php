<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPP;
use App\Models\Toko;
use Carbon\Carbon;
use App\Models\PermintaanPembelian;

class FormulirPPController extends Controller
{
    public function index()
    {
        $toko = Toko::all();
        return view('utility.permintaan_pembelian.formulir_pp', compact('toko'));
    }

    public function store(Request $request)
    {
        $kdtoko = $request->input('kdtoko'); // ambil kode toko dari form
        $tanggal_permintaan = Carbon::now();
        $tanggal_dibutuhkan = $request->input('tanggal_dibutuhkan');
        $supplier_pilih = $request->input('supplier_pilih', []);

        // ====== Generate No. PP Otomatis ======
        $tahunBulan = Carbon::now()->format('ym'); // contoh: 2509 untuk Sept 2025

        $last = DetailPP::where('kdtoko', $kdtoko)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->nopp, -4)); // ambil 4 digit terakhir
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $nopp = "PP-{$tahunBulan}-{$kdtoko}-{$newNumber}";
        // =====================================

        // Ambil semua input array
        $namabarang = $request->input('namabarang', []);
        $spesifikasi = $request->input('spesifikasi', []);
        $jumlah = $request->input('jumlah', []);
        $satuan = $request->input('satuan', []);
        $supplier1 = $request->input('supplier1', []);
        $harga1 = $request->input('harga1', []);
        $supplier2 = $request->input('supplier2', []);
        $harga2 = $request->input('harga2', []);
        $supplier3 = $request->input('supplier3', []);
        $harga3 = $request->input('harga3', []);

        // Loop simpan barang
        for ($i = 0; $i < count($namabarang); $i++) {
            if (empty($namabarang[$i]))
                continue;

            DetailPP::create([
                'nopp' => $nopp,
                'kdtoko' => $kdtoko,
                'namabarang' => $namabarang[$i],
                'spesifikasi' => $spesifikasi[$i],
                'jumlah' => $jumlah[$i],
                'satuan' => $satuan[$i],
                'supplier1' => $supplier1[$i],
                'harga1' => $harga1[$i],
                'supplier2' => $supplier2[$i],
                'harga2' => $harga2[$i],
                'supplier3' => $supplier3[$i],
                'harga3' => $harga3[$i],
                'supplier_pilih' => $supplier_pilih[$i],
                'tanggal_permintaan' => $tanggal_permintaan,
                'tanggal_dibutuhkan' => $tanggal_dibutuhkan,
            ]);
        }

        return redirect()->route('formulir_pp')->with('success', "Data berhasil disimpan dengan No. PP {$nopp}!");
    }

    public function create()
    {
        // Ambil data toko buat pilihan
        $toko = DB::table('toko')->get();

        return view('formulir_pp', compact('toko'));
    }


    public function generateNopp($kdtoko)
    {
        $tahunBulan = Carbon::now()->format('ym');

        // Cari nomor terakhir untuk toko & periode yg sama
        $last = DetailPP::where('kdtoko', $kdtoko)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->nopp, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $nopp = "PP-{$tahunBulan}-{$kdtoko}-{$newNumber}";

        return response()->json(['nopp' => $nopp]);
    }

    public function searchBarang(Request $request)
    {
        $keyword = $request->get('q');

        $barang = \App\Models\Barang::with('supplier')
            ->where('namabarang', 'like', "%{$keyword}%")
            ->limit(10)
            ->get(['kdbarang', 'namabarang', 'hargabeli', 'kdsupplier']);

        $result = $barang->groupBy('namabarang')->map(function ($items) {
            return [
                'namabarang' => $items->first()->namabarang,
                'options' => $items->map(function ($row) {
                    return [
                        'kdbarang' => $row->kdbarang,
                        'harga' => $row->hargabeli,
                        'supplier' => $row->supplier->namasupplier ?? '-'
                    ];
                })->values()
            ];
        })->values();

        return response()->json($result);
    }

    public function riwayat(Request $request)
    {
        $query = DetailPP::with('toko');

        // filter tanggal
        if ($request->filled('tanggal_permintaan')) {
            $query->whereDate('tanggal_permintaan', $request->tanggal_permintaan);
        }

        if ($request->filled('tanggal_dibutuhkan')) {
            $query->whereDate('tanggal_dibutuhkan', $request->tanggal_dibutuhkan);
        }

        // search keyword (No. PP atau nama barang)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nopp', 'like', "%$q%")
                    ->orWhere('namabarang', 'like', "%$q%");
            });
        }

        // pagination
        $riwayat = $query->paginate(10)->withQueryString();

        return view('utility.permintaan_pembelian.index', compact('riwayat'));
    }

}