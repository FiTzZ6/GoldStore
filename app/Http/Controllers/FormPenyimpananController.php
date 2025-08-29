<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormPenyimpananController extends Controller
{
    public function index()
    {
        return view('barang.cucisepuh.formpenyimpanan');
    }
}