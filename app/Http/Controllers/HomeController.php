<?php

namespace App\Http\Controllers;

use App\ShipmentTracking;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = array();
        $data['ActiveModule'] = 'Dashboard';

        return view('dashboards.home',$data);
    }

    public function LoadTrackPoints(Request $request){
        $data=[];

        $data['trackingPoint'] = ShipmentTracking::with(['DeliveryStatus'])->where('shipment_details_id',$request->id)->orderBy('id','desc')->get();

        return view('dashboards.trackpoints',$data);
    }
}
