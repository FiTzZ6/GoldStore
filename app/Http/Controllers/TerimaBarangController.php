<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKirimBarang;
use App\Models\SuratTerimaBarang;
use App\Models\TerimaBarang;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // pastikan ini ditambahkan

class TerimaBarangController extends Controller
{
    // Tampilkan daftar surat kirim untuk toko login
    public function index()
    {
        $kodeToko = Session::get('kdtoko');
        $suratKirim = SuratKirimBarang::with(['detail', 'terimaBarang.detail'])
            ->where('kdtokoterima', $kodeToko)
            ->get();
        return view('barang.terimabarang', compact('suratKirim'));
    }

    // Proses penerimaan barang
    public function terima(Request $request, $nokirim)
    {
        $kodeToko = Session::get('kdtoko');
        $noterima = $kodeToko . '-' . date('ymdHis');

        $surat = SuratKirimBarang::findOrFail($nokirim);

        SuratTerimaBarang::create([
            'noterima' => $noterima,
            'nokirim' => $nokirim,
            'kdtokoterima' => $kodeToko,
            'tanggalterima' => Carbon::now(),
            'picterima' => Session::get('username'),
        ]);

        if ($request->has('barang')) {
            foreach ($request->barang as $item) {
                TerimaBarang::create([
                    'noterima' => $noterima,
                    'barcode' => $item['barcode'],
                    'namabarang' => $item['namabarang'],
                    'kdjenis' => $item['kdjenis'],
                    'kdbaki' => $item['kdbaki'],
                    'berat' => $item['berat'],
                    'beratterima' => $item['beratterima'] ?? null,
                    'kdtokoterima' => $kodeToko,
                ]);
            }
        }

        return redirect()->route('terimabarang.index')->with('success', 'Surat barang berhasil diterima.');
    }

    // Cetak PDF surat
    public function cetakPdf($nokirim)
    {
        $surat = SuratKirimBarang::with(['terimaBarang.detail'])->findOrFail($nokirim);
        $pdf = Pdf::loadView('barang.surat_pdf', compact('surat'));
        return $pdf->stream('Surat-' . $nokirim . '.pdf');
    }

    public function show($nokirim)
    {
        $surat = SuratKirimBarang::with(['detail', 'terimaBarang.detail'])->findOrFail($nokirim);
        return view('barang.surat_detail', compact('surat'));
    }
}
