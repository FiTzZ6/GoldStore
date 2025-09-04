<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Direktur;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;


class QRDirekturController extends Controller
{
    public function index()
    {
        // Ambil direktur pertama (cuma 1 data)
        $direktur = Direktur::first();
        return view('utility.setting.qr_direktur', compact('direktur'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
        ]);

        $direktur = Direktur::first();

        // Hapus QR lama kalau ada
        if ($direktur && $direktur->qrcode && Storage::disk('public')->exists(str_replace('storage/', '', $direktur->qrcode))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $direktur->qrcode));
        }

        // Bikin nama file unik
        $fileName = str_replace(' ', '_', $request->nama) . '_' . time() . '.svg';
        $filePath = 'qrdirektur/' . $fileName;

        // ✅ Generate QR Code (pakai SVG backend biar gak butuh imagick)
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        // Simpan ke storage/app/public/qrdirektur
        Storage::disk('public')->put($filePath, $writer->writeString($request->nama));

        // Simpan/update ke database
        if ($direktur) {
            $direktur->update([
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'qrcode' => 'storage/' . $filePath, // simpan path yang bisa diakses publik
            ]);
        } else {
            Direktur::create([
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'qrcode' => 'storage/' . $filePath,
            ]);
        }

        return redirect()->route('qr_direktur')->with('success', 'QR Code direktur berhasil diperbarui!');
    }


}
