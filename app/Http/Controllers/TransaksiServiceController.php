<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\TransService;
use Illuminate\Http\Request;

class TransaksiServiceController extends Controller
{
    public function index()
    {
        $staff = Staff::all();
        return view('service.transaksiservice', compact('staff'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'staff' => 'required',
            'tanggalservice' => 'required|date',
            'tanggalambil' => 'required|date',
            'tipepelanggan' => 'required|in:pelanggan,umum',
            'namapelanggan' => 'required|string',
            'notelp' => 'required|string',
            'alamat' => 'nullable|string',
            'namabarang' => 'required|array',
            'jenis' => 'required|array',
            'berat' => 'required|array',
            'qty' => 'required|array',
            'harga' => 'required|array',   // ✅
            'ongkos' => 'required|array',
        ]);

        // generate no faktur unik format: SY-XXXX-XXXX-XXXX
        $random1 = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $random2 = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $random3 = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $faktur = "SY-$random1-$random2-$random3";

        $trans = TransService::create([
            'fakturservice' => $faktur,
            'staff' => $request->staff,
            'tanggalservice' => $request->tanggalservice,
            'tanggalambil' => $request->tanggalambil,
            'tipepelanggan' => $request->tipepelanggan,
            'nopelanggan' => $request->nopelanggan ?? '-',
            'namapelanggan' => $request->namapelanggan,
            'notelp' => $request->notelp,
            'alamat' => $request->alamat,
            'namabarang' => implode(',', $request->namabarang),
            'jenis' => implode(',', $request->jenis),
            'berat' => implode(',', $request->berat),
            'qty' => implode(',', $request->qty),
            'harga' => implode(',', $request->harga),   // ✅ simpan array harga, bukan jumlah
            'ongkos' => implode(',', $request->ongkos),
            'deskripsi' => implode(',', $request->deskripsi),
        ]);

        return redirect()->route('transaksiservice')
            ->with('success', 'Transaksi berhasil disimpan dengan No Faktur: ' . $trans->fakturservice);
    }

    public function data()
    {
        $data = TransService::latest()->get();
        return response()->json($data);
    }

    public function cetak($id)
    {
        $trans = TransService::findOrFail($id);

        // pecah string koma jadi array
        $trans->namabarang = explode(',', $trans->namabarang);
        $trans->jenis = explode(',', $trans->jenis);
        $trans->berat = explode(',', $trans->berat);
        $trans->qty = explode(',', $trans->qty);
        $trans->ongkos = explode(',', $trans->ongkos);
        $trans->harga = explode(',', $trans->harga);
        $trans->deskripsi = explode(',', $trans->deskripsi);

        return view('service.cetak', compact('trans'));
    }

}