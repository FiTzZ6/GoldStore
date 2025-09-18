<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TukarController extends Controller
{
    public function index()
    {
        $kurs = DB::table('kurs')->get();
        $transaksi = DB::table('transaksi_tukar')->orderBy('created_at', 'desc')->get();

        return view('utility.tukar.index', compact('kurs', 'transaksi'));
    }

    // ========== KURS ==========
    public function storeKurs(Request $request)
    {
        if (Session::get('typeuser') != 1) {
            return back()->with('error', 'Hanya admin yang bisa menambah kurs');
        }

        $request->validate([
            'mata_uang' => 'required|string|max:50',
            'nilai' => 'required|numeric|min:0'
        ]);

        DB::table('kurs')->insert([
            'mata_uang' => $request->mata_uang,
            'nilai' => $request->nilai,
            'created_at' => now()
        ]);

        return back()->with('success', 'Kurs berhasil ditambahkan');
    }

    public function updateKurs(Request $request, $id)
    {
        if (Session::get('typeuser') != 1) {
            return back()->with('error', 'Hanya admin yang bisa mengedit kurs');
        }

        $request->validate([
            'mata_uang' => 'required|string|max:50',
            'nilai' => 'required|numeric|min:0'
        ]);

        DB::table('kurs')->where('id', $id)->update([
            'mata_uang' => $request->mata_uang,
            'nilai' => $request->nilai,
            'updated_at' => now()
        ]);

        return back()->with('success', 'Kurs berhasil diupdate');
    }

    public function destroyKurs($id)
    {
        if (Session::get('typeuser') != 1) {
            return back()->with('error', 'Hanya admin yang bisa menghapus kurs');
        }

        DB::table('kurs')->where('id', $id)->delete();
        return back()->with('success', 'Kurs berhasil dihapus');
    }

    // ========== TRANSAKSI ==========
    public function storeTransaksi(Request $request)
    {
        $request->validate([
            'mata_uang' => 'required|string|max:50',
            'jumlah' => 'required|numeric|min:0.01',
        ]);

        $kurs = DB::table('kurs')->where('mata_uang', $request->mata_uang)->first();
        if (!$kurs) {
            return back()->with('error', 'Kurs tidak ditemukan');
        }

        $total = $request->jumlah * $kurs->nilai;

        // ambil usertype, kalau tidak ada pakai "guest"
        $userType = Session::get('typeuser') ?? 'guest';

        DB::table('transaksi_tukar')->insert([
            'user_id' => $userType, // simpan usertype di kolom user_id
            'mata_uang' => $request->mata_uang,
            'jumlah' => $request->jumlah,
            'total_rupiah' => $total,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Transaksi berhasil disimpan');
    }

}
