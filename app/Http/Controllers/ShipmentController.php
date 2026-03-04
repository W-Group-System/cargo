<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $data = array();
        $data['shipmentActive'] = true;
        $dummyList = array();
        for ($i=0; $i < 10; $i++) { 
            $dummyList[] = [
                "status" => "Pending",
                "dateCreated" => "Oct 1, 2025",
                "soNo" => "250010",
                "buyerCode" => "250001",
                "buyerPoNo" => "SWU-250001",
                "label" => "Rico Kraft Bag",
                "packaging" => "Rico Gel"
            ];
        }
         $data['dummyList'] = $dummyList;
        
        return view('shipments.index',$data);
    }
}
