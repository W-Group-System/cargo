<?php

namespace App\Http\Controllers;

use App\ShipmentTracking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request)
    {
        $data = array();
        $data['ActiveModule'] = 'Dashboard';
        $data['canCreate'] = $request->create;
        $data['canUpdate'] = $request->update;
        $data['canDelete'] = $request->delete;
        
        return view('dashboards.home',$data);
    }

    public function LoadTrackPoints(Request $request){
        $data=[];

        $data['trackingPoint'] = ShipmentTracking::with(['DeliveryStatus'])->where('shipment_details_id',$request->id)->orderBy('id','desc')->get();

        return view('dashboards.trackpoints',$data);
    }

    public function LoadShipmentCountsPerStatus(Request $request){

        $module = $request->module ?? "";

        $pending = DB::table('processed_orders as po')
            ->leftJoin('shipment_details as sd', 'sd.process_order_id', '=', 'po.id')
            ->where('po.CargoStatus', 'L')
            ->whereNull('po.is_coload')
            ->whereRaw("COALESCE(sd.delivery_status, '') IN ('P', '')");

        $inTransit = DB::table('shipment_details as sd')
            ->leftJoin('processed_orders as po', 'sd.process_order_id', '=', 'po.id')
            ->whereRaw('COALESCE(sd.delivery_status, "") = "IT"');

        $shipped = DB::table('shipment_details as sd')
            ->leftJoin('processed_orders as po', 'sd.process_order_id', '=', 'po.id')
            ->whereRaw('COALESCE(sd.delivery_status, "") <> "P"');

        $irregularities = DB::table('shipment_details as sd')
            ->leftJoin('processed_orders as po', 'sd.process_order_id', '=', 'po.id')
            ->whereRaw('COALESCE(sd.delivery_status, "") = "DLY"');

        $delivered = DB::table('shipment_details as sd')
            ->leftJoin('processed_orders as po', 'sd.process_order_id', '=', 'po.id')   
            ->whereRaw('COALESCE(sd.delivery_status, "") = "DLV"');


        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();
        
            $pending = $pending->whereBetween('po.AvailabilityDate',[$start, $end]);
            $inTransit = $inTransit->whereBetween('po.AvailabilityDate',[$start, $end]);
            $shipped = $shipped->whereBetween('po.AvailabilityDate',[$start, $end]);
            $irregularities = $irregularities->whereBetween('po.AvailabilityDate',[$start, $end]);
            $delivered = $delivered->whereBetween('po.AvailabilityDate',[$start, $end]);
        }

        $pending = $pending->count();
        $inTransit = $inTransit->count();
        $shipped = $shipped->count();
        $irregularities = $irregularities->count();
        $delivered = $delivered->count();

        return [
            'pending'        => $pending,
            'in_transit'     => $inTransit,
            'shipped'        => $shipped,
            'delivered'      => $delivered,   
            'irregularities' => $irregularities                                                    
        ];
    }
}
