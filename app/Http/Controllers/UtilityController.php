<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilityController extends Controller
{
    public function index()
    {
        return view('utility.company_profile'); // pastikan file ini ada di resources/views/utility/index.blade.php
    }
}
