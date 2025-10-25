<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function index(Request $request)
    {
        return view('cargo.index');
    }
}
