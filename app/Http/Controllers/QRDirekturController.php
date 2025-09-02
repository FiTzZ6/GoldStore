<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class QRDirekturController extends Controller
{
    public function index()
    {
        return view('utility.setting.qr_direktur');
    }
}