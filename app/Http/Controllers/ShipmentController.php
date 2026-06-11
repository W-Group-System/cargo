<?php

namespace App\Http\Controllers;

use App\ProcessedOrders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $data = array();
        $data['ActiveModule'] = 'Shipments';
        $dummyList = array();
        for ($i=0; $i < 100; $i++) { 
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

    public function ShipmentList(Request $request){
        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to retrieve information.",
            "total"=>0,
            "page"=>1,
            "data"=>null
        ];
        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 10;

            $ordersList = ProcessedOrders::with(['ShipmentStatus'])->select("*")->where("CargoStatus","<>","");

            if (isset($request->id) && !empty($request->id)) {
                $ordersList = $ordersList->where("id",$request->id);
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

            $ordersList = $ordersList->orderBy("id","desc") 
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();
            $response["isSuccess"] = true;
            $response["message"] = "Successfully retrieved information.";
            $response["total"] = $totalCount;
            $response["data"] = $ordersList;
        } catch (\Throwable $th) {
            Log::error("ERROR IN GETTING SHIPMENT LIST: ".$th);
        }
        
        return $response;
    }
}
