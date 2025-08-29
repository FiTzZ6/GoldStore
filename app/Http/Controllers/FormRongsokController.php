<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormRongsokController extends Controller
{
    public function index()
    {
        return view('barang.rongsok.formrongsok');
    }
}