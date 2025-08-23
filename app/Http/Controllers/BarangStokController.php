<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\KategoriBarang;
use App\Models\JenisBarang;
use App\Models\Baki;

class BarangStokController extends Controller
{
    public function index(Request $request)
{
    $perPage = $request->get('per_page', 15);
    $barang = Barang::with(['KategoriBarang', 'JenisBarang', 'baki', 'Cabang'])
        ->paginate($perPage);

    $kategori = KategoriBarang::all();
    $jenis = JenisBarang::all();
    $baki = Baki::all();

    return view('barang.barangStok', compact('barang', 'perPage', 'kategori', 'jenis', 'baki'));
}

    public function store(Request $request)
{
    if (session('typeuser') != 1) { 
        return back()->with('error', 'Hanya Admin yang bisa tambah barang');
    }

    $request->validate([
        'namabarang' => 'required|string',
        'kdkategori' => 'required|string',
        'kdjenis' => 'required|string',
        'kdbaki' => 'required|string',
        'kdtoko' => 'required|string',
        'berat' => 'required|numeric',
        'kadar' => 'required|string',
        'hargabeli' => 'required|numeric',
        'kdstatus' => 'required|string',
        'kdsupplier' => 'required|string',
        'atribut' => 'nullable|string',
        'hargaatribut' => 'nullable|numeric',
        'beratasli' => 'nullable|numeric',
        'beratbandrol' => 'nullable|numeric',
        'kdintern' => 'nullable|string',
        'barcode_pict' => 'nullable|string',
        'photo_type' => 'nullable|string',
        'camera_type' => 'nullable|string',
        'photo' => 'nullable|image|max:2048'
    ]);

    // Generate kdbarang & barcode (seperti sebelumnya)
    $prefix = substr($request->kdjenis, 0, 2) . substr($request->kdbaki, 0, 1);
    $lastBarang = Barang::where('kdjenis', $request->kdjenis)->orderByDesc('kdbarang')->first();
    $urutan = $lastBarang ? intval(substr($lastBarang->kdbarang, -4)) + 1 : 1;
    $kdbarang = $prefix . str_pad($urutan, 4, '0', STR_PAD_LEFT);

    // Buat barcode
    $generator = new BarcodeGeneratorPNG();
    $barcode = $kdbarang;
    $barcodeImage = $generator->getBarcode($barcode, $generator::TYPE_CODE_128);
    Storage::disk('public')->put('barcodeBarang/' . $barcode . '.png', $barcodeImage);

    // Simpan foto
    $photoName = null;
    if ($request->hasFile('photo')) {
        $photoName = $request->file('photo')->getClientOriginalName();
        $request->file('photo')->storeAs('public/barangFoto', $photoName);
    }

    Barang::create([
        'kdbarang' => $kdbarang,
        'barcode' => $barcode,
        'namabarang' => $request->namabarang,
        'kdkategori' => $request->kdkategori,
        'kdjenis' => $request->kdjenis,
        'kdbaki' => $request->kdbaki,
        'kdtoko' => $request->kdtoko,
        'berat' => $request->berat,
        'kadar' => $request->kadar,
        'hargabeli' => $request->hargabeli,
        'photo' => $photoName,
        'kdstatus' => $request->kdstatus,
        'kdsupplier' => $request->kdsupplier,
        'atribut' => $request->atribut,
        'hargaatribut' => $request->hargaatribut,
        'beratasli' => $request->beratasli,
        'beratbandrol' => $request->beratbandrol,
        'kdintern' => $request->kdintern,
        'barcode_pict' => $request->barcode_pict,
        'photo_type' => $request->photo_type,
        'camera_type' => $request->camera_type,
        'inputby' => session('username'),
        'inputdate' => now(),
    ]);

    return back()->with('success', 'Barang berhasil ditambahkan');
}


public function update(Request $request, $id)
{
    if (session('typeuser') != 1) {
        return back()->with('error', 'Hanya Admin yang bisa edit barang');
    }

    $barang = Barang::findOrFail($id);

    $request->validate([
        'namabarang' => 'required|string',
        'kdkategori' => 'required|string',
        'kdjenis' => 'required|string',
        'kdbaki' => 'required|string',
        'kdtoko' => 'required|string',
        'berat' => 'required|numeric',
        'kadar' => 'required|string',
        'hargabeli' => 'required|numeric',
        'kdstatus' => 'required|string',
        'kdsupplier' => 'required|string',
        'atribut' => 'nullable|string',
        'hargaatribut' => 'nullable|numeric',
        'beratasli' => 'nullable|numeric',
        'beratbandrol' => 'nullable|numeric',
        'kdintern' => 'nullable|string',
        'barcode_pict' => 'nullable|string',
        'photo_type' => 'nullable|string',
        'camera_type' => 'nullable|string',
        'photo' => 'nullable|image|max:2048'
    ]);

    // Update foto
    if ($request->hasFile('photo')) {
        $photoName = $request->file('photo')->getClientOriginalName();
        $request->file('photo')->storeAs('public/barangFoto', $photoName);

        if ($barang->photo) {
            Storage::disk('public')->delete('barangFoto/' . $barang->photo);
        }

        $barang->photo = $photoName;
    }

    // Update field lain
    $barang->fill($request->except(['photo']));
    $barang->editby = session('username');
    $barang->editdate = now();
    $barang->save();

    return back()->with('success', 'Barang berhasil diupdate');
}

    public function destroy($id)
    {
        if (session('typeuser') != 1) {
            return back()->with('error', 'Hanya Admin yang bisa hapus barang');
        }
        $barang = Barang::findOrFail($id);

        if ($barang->barcode) {
            Storage::disk('public')->delete('barcodeBarang/' . $barang->barcode . '.png');
        }
        if ($barang->photo) {
            Storage::disk('public')->delete('barangFoto/' . $barang->photo);
        }

        $barang->delete();
        return back()->with('success', 'Barang berhasil dihapus');
    }
}
