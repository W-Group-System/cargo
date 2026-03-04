<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $data = array();
        $data['shipmentActive'] = true;
        return view('shipments.index',$data);
    }
}
