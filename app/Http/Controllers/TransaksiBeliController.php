<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\TransPenjualan;
use App\Models\TransBeli;
use App\Models\StokJual;
use Illuminate\Http\Request;

class TransaksiBeliController extends Controller
{
    public function index(Request $request)
    {
        $staff = Staff::all();
        $barcode = $request->barcode;
        $selected = null;
        $riwayat = collect();
        $barang = null;

        if ($barcode) {
            $riwayat = TransPenjualan::where('barcode', $barcode)
                ->orderBy('created_at', 'desc')
                ->get();

            // kalau user pilih salah satu riwayat
            if ($request->riwayat_id) {
                $selected = TransPenjualan::find($request->riwayat_id);

                // cek barang di stokjual/barang
                $barang = StokJual::with(['barang.JenisBarang'])
                    ->where('barcode', $barcode)
                    ->first();
            }
        }

        return view('beli.transaksi_beli', compact('staff', 'riwayat', 'barcode', 'selected', 'barang'));
    }


    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'staff' => 'required|string',
                'barcode' => 'required|string',
                'namapenjual' => 'nullable|string',
                'alamat' => 'nullable|string',
                'notelp' => 'nullable|string',
                'nofaktur' => 'required|string',
                'deskripsi' => 'nullable|string',
                'jenis' => 'nullable|string',
                'kondisi' => 'required|string',
                'beratlama' => 'nullable|numeric',
                'beratbaru' => 'required|numeric',
                'hargalama' => 'nullable|numeric',
                'hargabaru' => 'required|numeric',
                'hargarata' => 'required|numeric',
                'potongan' => 'nullable|numeric',
                'total' => 'nullable|numeric',
            ]);

            // 🔹 Generate nofakturbeli otomatis
            $lastFaktur = TransBeli::orderBy('id', 'desc')->first();
            $number = $lastFaktur ? ((int) substr($lastFaktur->nofakturbeli, -4)) + 1 : 1;
            $kodeFaktur = 'FB-' . date('ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

            $data['nofakturbeli'] = $kodeFaktur;

            TransBeli::create($data);

            return redirect()->route('transaksibeli')->with('success', 'Transaksi berhasil disimpan dengan No Faktur Beli: ' . $kodeFaktur);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }


}