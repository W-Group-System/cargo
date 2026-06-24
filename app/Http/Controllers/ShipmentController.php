<?php

namespace App\Http\Controllers;

use App\DeliveryStatus;
use App\Order;
use App\ProcessedOrders;
use App\TrackingPoints;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $data = array();
        $data['ActiveModule'] = 'Shipments';
        $data['trackingPoints'] = TrackingPoints::where('status','A')->pluck('description','code');
        $data['deliveryStatus'] = DeliveryStatus::where('status','A')->pluck('description','code');
        
        return view('shipments.index',$data);
    }

    public function ShipmentList(Request $request){
        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to retrieve information.",
            "total"=>0,
            "page"=>1,
            "data"=>null,
            "coloads"=>[]
        ];
        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 10;
            $coloadSoNumberArr = [];

            $ordersList = ProcessedOrders::with(['ShipmentStatus'])->select(
                "id",
                "SapServer",
                "CardCode",
                "CardName",
                "MinDocDate",
                "AvailabilityDate",
                "PickupDate",
                "CargoStatus",
                "OrderStatus",
                "ShipmentStatus",
                "is_coload",
                "coloaded_by",
                "coload_order",
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as formatted_created_at"),
                DB::raw("DATE_FORMAT(cargo_posting_date, '%Y-%m-%d') as cargo_posting_date")
            )
            ->where(function($q){
                $q->where("AvailabilityDate","<>","")->where("PickupDate","<>","");
            });

            if (isset($request->id) && !empty($request->id)) {
                $ordersList = $ordersList->with(['ShipmentDetails.ShipmentTracking','ShipmentDetails.DeliveryStatus','OrderData.OrderItemList'])->where("id",$request->id);
                $coloadList = Order::from('orders as o')->select('o.CardCode','o.DocNum')
                    ->leftJoin('processed_orders as po','po.id','=','o.process_order_id')
                    ->where('po.is_coload','1')
                    ->where('po.coloaded_by',$request->id)
                    ->orderBy('po.coload_order','asc')
                    ->get()
                    ->map(function($order) use(&$coloadSoNumberArr){
                        $coloadSoNumberArr[$order->CardCode][] = $order->DocNum;
                        return $coloadSoNumberArr;
                    });
            }

            if (isset($request->search) && !empty(isset($request->search))) {
                $search = $request->search;
                $ordersList = $ordersList->where(function ($query) use ($search) {
                    $query->where('CardCode', 'LIKE', "%{$search}%")
                        ->orWhere('CardName', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = Carbon::parse($request->start_date)->startOfDay();
                $end   = Carbon::parse($request->end_date)->endOfDay();

                $ordersList->whereBetween('created_at', [$start, $end]);
            }
            $ordersList = $ordersList->where("is_coload",null);

            $totalCount = (clone $ordersList)->count();

            $ordersList = $ordersList->orderBy("cargo_posting_date","desc")
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();
            $response["isSuccess"] = true;
            $response["message"] = "Successfully retrieved information.";
            $response["total"] = $totalCount;
            $response["data"] = $ordersList;
            $response["coloads"] = $coloadSoNumberArr;
        } catch (\Throwable $th) {
            Log::error("ERROR IN GETTING SHIPMENT LIST: ".$th);
        }
        
        return $response;
    }
}
