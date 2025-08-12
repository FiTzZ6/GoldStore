<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\DataMaster;
use App\Models\DataBarang;
use Carbon\Carbon;

class BarangController extends Controller
{
    protected $petugas;
    protected $toko;
    protected $typeuser;
    protected $array_holding;
    protected $array_toko;
    protected $serverconnection;

    public function __construct()
    {
        // Set timezone
        date_default_timezone_set('Asia/Jakarta');

        // Session data
        $this->typeuser = Session::get('typeuser');
        $this->petugas  = Session::get('username');
        $this->toko     = Session::get('kdtoko');
        $this->array_holding = [1, 2, 3, 4, 5];
        $this->array_toko    = [6, 7, 8, 9];

        // Simulasi pemanggilan method model
        $this->serverconnection = (new DataMaster())->serverconnection();

        // Jika kamu ingin validasi login:
        // if (is_null($this->typeuser)) {
        //     Session::flush();
        //     return Redirect::to('/login')->send();
        // }

        // if (empty($this->petugas)) {
        //     return Redirect::to('/login')->send();
        // }
    }

    public function index()
    {
        $sessusername = Session::get('username');
        $toko = $this->toko;

        $supplier = (new DataMaster())->getdata('supplier');
        $baki = (new DataMaster())->getdata('baki');

        $cabang = (new DataBarang())->get_list_cabang();
        $opt = ['' => 'Semua Cabang'];
        foreach ($cabang as $tokocabang) {
            $opt[$tokocabang] = $tokocabang;
        }

        // Jika kamu pakai Blade untuk dropdown, form_dropdown CI bisa diganti di view
        return view('barang.index', [
            'sessusername' => $sessusername,
            'sesstoko' => $toko,
            'page' => 'Barang',
            'subpage' => 'Data Barang',
            'supplier' => $supplier,
            'baki' => $baki,
            'form_cabang' => $opt
        ]);
    }
}
    