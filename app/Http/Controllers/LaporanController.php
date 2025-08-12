<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function dashboard()
    {
        $company = DB::table('company_profile')->first();
        return view('laporan.dashboard', compact('company'));
    }

}