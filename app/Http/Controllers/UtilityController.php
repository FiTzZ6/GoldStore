<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UtilityController extends Controller
{
    public function index()
    {
        return view('utility.company_profile'); 
    }

    public function companyProfile()
    {
        $data = [
            'sessusername' => session('username'),
            'staff'        => auth()->user(), // contoh ambil data staff
            'toko'         => 'Nama Toko',    // sesuaikan
            'company'      => DB::table('company_profile')->first(),
            'page'         => 'Utility',
            'subpage'      => 'Setting Profil Perusahaan',
        ];

        return view('utility.company_profile', $data);
    }

    public function setUpCompany(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kota'   => 'nullable|string',
            'kontak' => 'nullable|string',
            'logo'   => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $data = [
            'name'    => strtoupper($request->nama),
            'address' => $request->alamat,
            'city'    => $request->kota,
            'contact' => $request->kontak,
        ];

        // Jika ada file logo
        $fileName = null;
        if ($request->hasFile('logo')) {
            $fileName = $request->file('logo')->getClientOriginalName();
            $request->file('logo')->storeAs('assets', $fileName, 'public');
            $data['logo'] = $fileName;
        }
    

        DB::table('company_profile')->where('id', 1)->update($data);

        return redirect()->route('utility.company_profile')->with('msg', 'Profil Perusahaan Telah Diubah!');
    }
}
