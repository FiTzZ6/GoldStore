<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SelisihJualController extends Controller
{
    public function index()
    {
        return view('jual.selisihjual');
    }
}