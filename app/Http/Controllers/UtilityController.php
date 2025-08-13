<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PermintaanPembelian;
use App\Models\DetailPP;
use App\Models\Toko;
use App\Models\CompanyProfile;
use Carbon\Carbon;

class UtilityController extends Controller
{
    public function index()
    {
        $data = PermintaanPembelian::all();
        return view('utility.permintaan_pembelian.formulir_pp', compact('data'));
    }

    /**
     * Halaman Permintaan Pembelian.
     * - Jika user dari toko selain HLD → tampilkan form PP.
     * - Jika HLD → tampilkan daftar PP.
     */
    public function permintaanPembelian()
    {
        $toko = auth()->user()->toko ?? null;

        if ($toko && $toko !== 'HLD') {
            $nopp       = $this->generateNoPP($toko);
            $detailToko = Toko::where('kdtoko', $toko)->first();
            $profile    = CompanyProfile::first();

            return view('utility.permintaan_pembelian.formulir_pp', [
                'data'       => [],
                'nopp'       => $nopp,
                'detailToko' => $detailToko,
                'profile'    => $profile
            ]);
        }

        // Jika toko = HLD → tampilkan list PP
        $data = PermintaanPembelian::orderBy('tgl_permintaan', 'desc')->get();

        return view('utility.permintaan_pembelian.index', [
            'data'       => $data,
            'nopp'       => null,
            'detailToko' => null,
            'profile'    => null
        ]);
    }

    /**
     * Form tambah PP (untuk toko non-HLD).
     */
    public function create()
    {
        $user = auth()->user();
        $toko = $user->toko ?? null;
    
        // Jika toko valid dan bukan HLD → tampilkan form PP
        if ($toko && strtoupper($toko) !== 'HLD') {
            $nopp       = $this->generateNoPP($toko);
            $detailToko = Toko::find($user->id_toko);
            $profile    = CompanyProfile::first();
    
            return view('utility.permintaan_pembelian.formulir_pp', [
                'data'       => [],
                'nopp'       => $nopp,
                'detailToko' => $detailToko,
                'profile'    => $profile
            ]);
        }
    
        // Jika toko = HLD → tampilkan list PP
        $data = PermintaanPembelian::orderBy('tgl_permintaan', 'desc')->get();
    
        return view('utility.permintaan_pembelian.index', [
            'data'       => $data,
            'nopp'       => null,
            'detailToko' => null,
            'profile'    => null
        ])->with('info', 'Hanya toko non-HLD yang dapat membuat PP.');
    }

    /**
     * Simpan data PP + detail barang.
     */
    public function kirimPP(Request $request)
    {
        $request->validate([
            'nopp'        => 'required|string',
            'kdtoko'      => 'required|string',
            'tgldibutuhkan' => 'required|date',
            'namabarang'  => 'required|array',
            'jumlah'      => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $tglPermintaan = Carbon::now()->toDateString();
            $tglDibutuhkan = Carbon::parse($request->tgldibutuhkan)->toDateString();

            // Simpan PP
            PermintaanPembelian::create([
                'nopp'           => $request->nopp,
                'kdtoko'         => $request->kdtoko,
                'tgl_permintaan' => $tglPermintaan,
                'tgl_butuh'      => $tglDibutuhkan,
            ]);

            // Simpan detail barang
            foreach ($request->namabarang as $index => $nama) {
                DetailPP::create([
                    'nopp'        => $request->nopp,
                    'namabarang'  => $nama,
                    'spesifikasi' => $request->spesifikasi[$index] ?? null,
                    'jumlah'      => $request->jumlah[$index],
                    'satuan'      => $request->satuan[$index] ?? null,
                    'supplier1'   => $request->supplier1[$index] ?? null,
                    'harga1'      => $request->harga1[$index] ?? null,
                    'supplier2'   => $request->supplier2[$index] ?? null,
                    'harga2'      => $request->harga2[$index] ?? null,
                    'supplier3'   => $request->supplier3[$index] ?? null,
                    'harga3'      => $request->harga3[$index] ?? null,
                ]);
            }
        });

        return redirect()->route('utility.permintaan_pembelian.index')
            ->with('success', 'Permintaan pembelian berhasil dikirim.');
    }

    /**
     * Generate nomor PP berdasarkan toko & bulan berjalan.
     */
    private function generateNoPP($toko)
    {
        $lastPP = PermintaanPembelian::where('kdtoko', $toko)
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = $lastPP ? intval(substr($lastPP->nopp, -4)) : 0;
        $newNumber  = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "PP-" . strtoupper($toko) . "-" . date('Ym') . "-" . $newNumber;
    }

    /**
     * Placeholder untuk fitur detail PP.
     */
    public function show($id)
    {
        return redirect()->route('formulir_pp.index')
            ->with('info', 'Fitur detail belum tersedia.');
    }

    /**
     * Placeholder untuk fitur edit PP.
     */
    public function edit($id)
    {
        $pp         = PPModel::findOrFail($id);
        $detailToko = Toko::find(auth()->user()->id_toko);
        $profile    = CompanyProfile::first();
    
        return view('utility.permintaan_pembelian.formulir_pp', [
            'data'       => $pp,
            'nopp'       => $pp->no_pp, // ambil dari database
            'detailToko' => $detailToko,
            'profile'    => $profile
        ]);
    }

    /**
     * Halaman profil perusahaan.
     */
    public function companyProfile()
    {
        $data = [
            'sessusername' => session('username'),
            'staff'        => auth()->user(),
            'toko'         => 'Nama Toko', // Sesuaikan jika perlu
            'company'      => DB::table('company_profile')->first(),
            'page'         => 'Utility',
            'subpage'      => 'Setting Profil Perusahaan',
        ];

        return view('utility.company.company_profile', $data);
    }

    /**
     * Simpan perubahan profil perusahaan.
     */
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

        // Upload logo jika ada
        if ($request->hasFile('logo')) {
            $fileName = $request->file('logo')->getClientOriginalName();
            $request->file('logo')->storeAs('assets_company', $fileName, 'public');
            $data['logo'] = $fileName;
        }

        DB::table('company_profile')->where('id', 1)->update($data);

        return redirect()->route('utility.company.company_profile')
            ->with('msg', 'Profil Perusahaan telah diubah!');
    }
}
