<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class BatalBeliController extends Controller
{
    public function index()
    {
        return view('beli.batal_beli');
    }
}