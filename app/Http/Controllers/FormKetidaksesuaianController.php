<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormKetidaksesuaianController extends Controller
{
    public function index()
    {
        return view('barang.rongsok.formketidaksesuai');
    }
}