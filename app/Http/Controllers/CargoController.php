<?php

namespace App\Http\Controllers;
use App\Order;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();
        
        $cargoes = $query->paginate(10)->appends($request->all()); 
        return view('cargo.index', compact('cargoes'));
    }
}
