<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StokOpnameController extends Controller
{
    public function index()
    {
        return view('stokopname.stokopname');
    }
}