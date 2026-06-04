<?php

namespace App\Http\Controllers;
use App\Cargo;
use App\Classes\OrderClass;
use App\Order;
use App\ProcessedOrders;
use App\ShipmentStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CargoController extends Controller
{
    protected OrderClass $order;
    public function __construct(OrderClass $orderClass)
    {
        $this->order = $orderClass;
    }
    public function index(Request $request)
    {
        $data = array();
        $data['ActiveModule'] = 'Cargo Management';
        $data['shipmentStatusArr'] = ShipmentStatus::ShipmentStatusArray();

        return view('cargo.index',$data);
    }

    public function CargoList(Request $request){
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

            $ordersList = ProcessedOrders::select("*");

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
            Log::error("ERROR IN GETTING CARGO LIST: ".$th);
        }
        
        return $response;
    }

    public function GetProcessedOrderDetails(Request $request){
        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to retrieve information.",
            "availabilityDate" => null,
            "pickupDate" => null,
            "status" => null,
            "total"=>0,
            "page"=>1,
            "data"=>null
        ];
        $isSuccess = false;

        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 10;
            $buyersCode = $request->buyersCode??"";
            $soNo = $request->soNo??"";
            if (!empty($buyersCode)) {
                $ordersList = Order::with(['OrderItemList'])->where("CardCode",$buyersCode);
                if (!empty($soNo)) {
                    $ordersList = $ordersList->where("DocNum",$soNo);
                }
                $totalCount = (clone $ordersList)->count();
                $ordersList = $ordersList->orderBy("id","desc")
                    ->skip(($page - 1) * $limit)
                    ->take($limit)
                    ->get();

                $processedOrderData = ProcessedOrders::where("CardCode",$buyersCode)->first();
                if (!empty($processedOrderData)) {
                    $response["availabilityDate"] = $processedOrderData->AvailabilityDate;
                    $response["pickupDate"] = $processedOrderData->PickupDate;
                    $response["status"] = $processedOrderData->Status;
                    // $coloadList = ProcessedOrders::with(['OrderData.OrderItemList'])->where("coloaded_by",$processedOrderData->id)->where("is_coload",1)->get();
                    // dd($coloadList);
                }

                $isSuccess = true;
                $response["isSuccess"] = $isSuccess;
                $response["message"] = "Successfully retrieved information.";
                $response["total"] = $totalCount;
                $response["data"] = $ordersList;
            }
        } catch (\Throwable $th) {
            Log::error("ERROR IN GETTING ORDER DETAILS: ".$th->getMessage());
        }

        if ($isSuccess) {
            return response()->json($response,200);
        }else{
            return response()->json($response,400);
        }
    }

    public function UpdateProcessedOrderDetails(Request $request){

        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to update information."
        ];
        $isSuccess = false;

        try {
            $buyersCode = $request->buyersCode??"";
            $availabilityDate = $request->availabilityDate??null;
            $pickupDate = $request->pickupDate??null;
            $status = $request->status??null;
            $coloads = json_decode($request->coloads);

            if (!empty($buyersCode)) {
                $processedOrderData = ProcessedOrders::where("CardCode",$buyersCode)->first();
                if (!empty($processedOrderData)) {
                    $sapServer = $processedOrderData->SapServer;
                    $processedOrderId = $processedOrderData->id;
                    $processedOrderData = $processedOrderData->update(["AvailabilityDate"=>$availabilityDate,"PickupDate"=>$pickupDate,"Status"=>$status]);
                    foreach ($coloads as $key => $value) {
                        $processedOrderColoadData = ProcessedOrders::where("CardCode",$key)->first();
                        if (!empty($processedOrderColoadData)) {
                            $processedOrderColoadData = $processedOrderColoadData->where()->update(["is_coload"=>1,"coloaded_by"=>$processedOrderData->id]);
                        }else{
                            $this->order->SaveCoload($key,$sapServer,$processedOrderId);
                        }
                    }
                }

                $isSuccess = true;
                $response = [
                    "isSuccess"=>true,
                    "message"=>"Information updated successfully."
                ];
            }
        } catch (\Exception $th) {
            Log::error("ERROR IN UPDATING CARGO DETAILS: ".$th->getMessage());
            dd("CONTROLLER: ".$th->getMessage());
        }

        if ($isSuccess) {
            return response()->json($response,200);
        }else{
            return response()->json($response,400);
        }
    }

    public function GetBuyersCodeDetails(Request $request){
        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to retrieve information.",
            "data"=>[]
        ];
        $isSuccess = false;
        $data = [];

        $cardCode = $request->buyersCode??"";
        $sapServer = $request->sapServer??"";

        try {
            $data = [$cardCode=>[]];
            $orderController = new OrderController();
            $buyersCodeDetails = $orderController->SapOrderList($cardCode,$sapServer,"");
            if($buyersCodeDetails["isSuccess"]){
                $details = $buyersCodeDetails["data"];                
                foreach ($details as $key => $value) {
                    $data[$cardCode][] = $value->DocNum;
                }
                $isSuccess = true;
                $response["isSuccess"] = $isSuccess;
                $response["message"] = "Successfully retrieved information.";
                $response["data"] = $data;
            }

        } catch (\Throwable $th) {
            Log::error("ERROR IN GETTING BUYERS CODE DETAILS: ".$th->getMessage());
        }

        if ($isSuccess) {
            return response()->json($response,200);
        }else{
            return response()->json($response,400);
        }
    }
}
