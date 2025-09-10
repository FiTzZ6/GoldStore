<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKirimBarang;
use App\Models\DetailKirimBarang;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class SuratKirimController extends Controller
{
    // daftar semua surat kirim
    public function index()
    {
        $suratKirim = SuratKirimBarang::with(['detail', 'terimaBarang'])->get();
        return view('barang.suratkirim', compact('suratKirim'));
    }
    // form tambah surat
    public function create()
    {
        return view('barang.suratkirim_create');
    }

    // simpan surat kirim
    public function store(Request $request)
    {
        $nokirim = 'SK-' . date('ymdHis');

        SuratKirimBarang::create([
            'nokirim' => $nokirim,
            'tanggal' => Carbon::now(),
            'kdtokokirim' => Session::get('kdtoko'), // toko admin yang kirim
            'kdtokoterima' => $request->kdtokoterima,
            'status' => 'dikirim',
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
}
