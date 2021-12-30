<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KhataController extends Controller
{
    public function index()
    {
        return view('pages.khata.dashboard');
    }
}
