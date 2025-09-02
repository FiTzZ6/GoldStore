<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class TransaksiServiceController extends Controller
{
    public function index()
    {
        return view('service.transaksiservice');
    }
}