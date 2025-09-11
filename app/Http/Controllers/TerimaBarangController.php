<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKirimBarang;
use App\Models\SuratTerimaBarang;
use App\Models\TerimaBarang;
use App\Models\Cabang;
use App\Models\Barang;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TerimaBarangController extends Controller
{
    // Tampilkan daftar semua surat kirim tanpa membedakan toko
    public function index()
    {
        $suratKirim = SuratKirimBarang::with(['detail', 'terimaBarang.detail'])->get();

        // Data tambahan untuk modal tambah surat (hanya admin)
        $tokoList = Cabang::all();
        $barangList = Barang::all();

        return view('barang.terimabarang', compact('suratKirim', 'tokoList', 'barangList'));
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

        // Masukkan semua detail barang yang dikirim ke terima barang
        foreach ($surat->detail as $item) {
            TerimaBarang::create([
                'noterima' => $noterima,
                'barcode' => $item->barcode,
                'namabarang' => $item->namabarang,
                'kdjenis' => $item->kdjenis,
                'kdbaki' => $item->kdbaki,
                'berat' => $item->berat,
                'beratterima' => $item->berat,
                'kdtokoterima' => $kodeToko,
            ]);
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

    // Detail surat
    public function show($nokirim)
    {
        $surat = SuratKirimBarang::with(['detail', 'terimaBarang.detail'])->findOrFail($nokirim);
        return view('barang.surat_detail', compact('surat'));
    }

    public function previewPdf($nokirim)
    {
        $surat = SuratKirimBarang::with(['detail', 'terimaBarang.detail'])->findOrFail($nokirim);
        return view('barang.surat_pdf', compact('surat'));
    }
}
