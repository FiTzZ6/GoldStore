<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Baki;

class BakiBarangController extends Controller
{
    public function index(Request $request)
    {
        // fitur search
        $search = $request->input('search');
        $query = Baki::query();

        if ($search) {
            $query->where('kdbaki', 'like', "%$search%")
                  ->orWhere('namabaki', 'like', "%$search%")
                  ->orWhere('kdkategori', 'like', "%$search%");
        }

        $baki = $query->get();

        return view('datamaster.bakibarang', compact('baki', 'search'));
    }

    public function store(Request $request)
    {
        Baki::create($request->all());
        return redirect()->route('bakibarang.index')->with('success', 'Baki berhasil ditambahkan!');
    }

    public function update(Request $request, $kdbaki)
    {
        $baki = Baki::where('kdbaki', $kdbaki)->firstOrFail();
        $baki->update($request->all());
        return redirect()->route('bakibarang.index')->with('success', 'Baki berhasil diupdate!');
    }    

    public function destroy($id)
    {
        $baki = Baki::findOrFail($id);
        $baki->delete();
        return redirect()->route('bakibarang.index')->with('success', 'Baki berhasil dihapus!');
    }
}