<?php

namespace App\Http\Controllers;

use App\Models\TransPenjualan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StrukController extends Controller
{
    // tampilkan struk ke browser
    public function show($id)
    {
        $transaksi = TransPenjualan::with('detail')->findOrFail($id);
        return view('jual.struk', compact('transaksi'));
    }

    // export struk jadi PDF
    public function downloadPdf($id)
    {
        $transaksi = TransPenjualan::with('detail')->findOrFail($id);
        $pdf = Pdf::loadView('jual.struk-pdf', compact('transaksi'));
        return $pdf->download('struk-'.$transaksi->nofaktur.'.pdf');
    }

    // cetak langsung ke printer POS (opsional)
    public function printPos($id)
    {
        $transaksi = TransPenjualan::with('detail')->findOrFail($id);

        // contoh pakai raw printing
        $connector = new \Mike42\Escpos\PrintConnectors\WindowsPrintConnector("POS-58"); 
        $printer = new \Mike42\Escpos\Printer($connector);

        $printer->text("=== STRUK PENJUALAN ===\n");
        $printer->text("No Faktur: ".$transaksi->nofaktur."\n");
        $printer->text("Pelanggan: ".$transaksi->namapelanggan."\n");
        $printer->text("------------------------\n");

        foreach ($transaksi->detail as $item) {
            $printer->text($item->namabarang." x".$item->jumlah." @".$item->harga."\n");
        }

        $printer->text("------------------------\n");
        $printer->text("Total: Rp ".number_format($transaksi->total,0,',','.')."\n");
        $printer->text("Terima Kasih!\n");
        $printer->cut();
        $printer->close();

        return redirect()->route('riwayatpenjualan')->with('success', 'Struk berhasil dicetak');
    }
}
