<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SelisihBeliController extends Controller
{
    public function index()
    {
        return view('beli.selisih_belibatal');
    }
}