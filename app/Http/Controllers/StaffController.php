<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Cabang;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with('cabang')->get();
        return view('datamaster.staff', compact('staff'));
    }

    public function store(Request $request)
    {
        Staff::create($request->all());
        return redirect()->route('staff')->with('success', 'Staff berhasil ditambahkan');
    }

    public function update(Request $request, $kdstaffLama)
    {
        // Ambil staff berdasarkan kdstaff lama
        $staff = Staff::where('kdstaff', $kdstaffLama)->firstOrFail();

        // Update semua field termasuk kdstaff baru
        $staff->update([
            'kdstaff' => $request->kdstaff,
            'nama' => $request->nama,
            'posisi' => $request->posisi,
            'kdtoko' => $request->kdtoko,
        ]);

        return redirect()->route('staff')->with('success', 'Staff berhasil diupdate');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();
        return redirect()->route('staff')->with('success', 'Staff berhasil dihapus');
    }
}
