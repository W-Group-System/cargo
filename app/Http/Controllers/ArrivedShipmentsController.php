<?php

namespace App\Http\Controllers;

use App\DeliveryStatus;
use App\Regions;
use App\SuggestionVault;
use App\TrackingPoints;
use Illuminate\Http\Request;

class ArrivedShipmentsController extends Controller
{
    public function index(Request $request)
    {
        $data = array();
        $data['ActiveModule'] = 'Arrived Shipments';
        $data['canCreate'] = $request->create;
        $data['canUpdate'] = $request->update;
        $data['canDelete'] = $request->delete;
        
        $data['trackingPoints'] = TrackingPoints::where('status','A')->pluck('description','code');
        $data['deliveryStatus'] = DeliveryStatus::select('description','code','disabled')->where('status','A')->get();
        $data['regions'] = Regions::pluck('region','id');
        $data['users'] = SuggestionVault::where("type","EMAIL")->pluck('suggestion','suggestion');
        
        return view('Arrived.index',$data);
    }
}
