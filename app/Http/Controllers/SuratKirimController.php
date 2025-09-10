<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKirimBarang;
use App\Models\DetailKirimBarang;
use App\Models\Cabang;
use App\Models\Barang;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class SuratKirimController extends Controller
{
    // daftar semua surat kirim
    public function index()
    {
        $suratKirim = SuratKirimBarang::with(['detail', 'terimaBarang'])->get();
        $tokoList = Cabang::all();
        $barangList = Barang::all();
        return view('barang.terimabarang', compact('suratKirim', 'tokoList', 'barangList'));
    }
    // form tambah surat
    public function create()
    {
        $tokoList = Cabang::all();     // ambil semua toko
        $barangList = Barang::all();   // ambil semua barang
        return view('barang.suratkirim_create', compact('tokoList', 'barangList'));
    }
    // simpan surat kirim
    public function store(Request $request)
    {
        $kodeToko = Session::get('kdtoko');

        // Buat 2 blok angka random 4 digit
        $random1 = rand(1000, 9999);
        $random2 = rand(1000, 9999);

        $nokirim = 'SK-' . $kodeToko . '-' . $random1 . '-' . $random2;

        SuratKirimBarang::create([
            'nokirim' => $nokirim,
            'tanggal' => Carbon::now(),
            'kdtokokirim' => $kodeToko,
            'kdtokoterima' => $request->kdtokoterima,
            'status' => 'KIRIM',
        ]);

        if ($request->has('barang')) {
            foreach ($request->barang as $item) {
                DetailKirimBarang::create([
                    'nokirim' => $nokirim,
                    'barcode' => $item['barcode'],
                    'namabarang' => $item['namabarang'],
                    'kdjenis' => $item['kdjenis'],
                    'kdbaki' => $item['kdbaki'],
                    'berat' => $item['berat'],
                    'qty' => $item['qty'],
                ]);
            }
        }

        return redirect()->route('suratkirim.index')->with('success', 'Surat kirim berhasil dibuat.');
    }

    public function edit($nokirim)
    {
        $surat = SuratKirimBarang::with('detail')->findOrFail($nokirim);
        $tokoList = Cabang::all();
        $barangList = Barang::all();
        return view('barang.suratkirim_edit', compact('surat', 'tokoList', 'barangList'));
    }

    public function update(Request $request, $nokirim)
    {
        $surat = SuratKirimBarang::findOrFail($nokirim);

        // Ambil kode toko lama
        $kodeTokoLama = $surat->kdtokokirim;

        // Update kode toko kirim dan toko penerima
        $surat->kdtokokirim = $request->kdtokokirim; // jika mau bisa diedit juga
        $surat->kdtokoterima = $request->kdtokoterima;

        // Jika kode toko kirim berubah, bikin nokirim baru
        if ($request->kdtokokirim != $kodeTokoLama) {
            $random1 = rand(1000, 9999);
            $random2 = rand(1000, 9999);
            $surat->nokirim = 'SK-' . $request->kdtokokirim . '-' . $random1 . '-' . $random2;
        }

        $surat->save();

        // Hapus detail lama dulu
        DetailKirimBarang::where('nokirim', $nokirim)->delete();

        // Simpan detail baru
        if ($request->has('barang')) {
            foreach ($request->barang as $item) {
                DetailKirimBarang::create([
                    'nokirim' => $surat->nokirim, // pakai nokirim terbaru
                    'barcode' => $item['barcode'],
                    'namabarang' => $item['namabarang'],
                    'kdjenis' => $item['kdjenis'],
                    'kdbaki' => $item['kdbaki'],
                    'berat' => $item['berat'],
                    'qty' => $item['qty'],
                ]);
            }
        }

        return redirect()->route('suratkirim.index')->with('success', 'Surat kirim berhasil diperbarui.');
    }


}
