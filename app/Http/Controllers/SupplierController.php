<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('kdsupplier')->get();
        return view('datamaster.supplier', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kdsupplier'   => 'required|string|max:25|unique:supplier,kdsupplier',
            'namasupplier' => 'nullable|string|max:255',
            'alamat'       => 'nullable|string',
            'hp'           => 'nullable|string|max:15',
            'email'        => 'nullable|email|max:255',
            'ket'          => 'nullable|string',
        ]);

        Supplier::create($validated);
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'kdsupplier'   => 'required|string|max:25|unique:supplier,kdsupplier,' . $supplier->kdsupplier . ',kdsupplier',
            'namasupplier' => 'nullable|string|max:255',
            'alamat'       => 'nullable|string',
            'hp'           => 'nullable|string|max:15',
            'email'        => 'nullable|email|max:255',
            'ket'          => 'nullable|string',
        ]);

        $supplier->update($validated);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return response()->json(['success' => true]);
    }
}
