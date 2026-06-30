<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Order;
use App\OrderItem;
use App\ProcessedOrders;
use App\ThirdrdPartyEndpoint;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $data['ActiveModule'] = 'Orders';
        $data['canCreate'] = $request->create;
        $data['canUpdate'] = $request->update;
        $data['canDelete'] = $request->delete;
        return view('orders.indexV2', $data);
    }

    public function SapOrderListDictinct(Request $request){
        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to retrieve information.",
            "total"=>0,
            "page"=>1,
            "data"=>null
        ];

        $isSuccess = false;

        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 10;
            $search = $request->search;
            $start = $request->start_date??"";
            $end = $request->end_date??"";
            $buyersCode = $request->buyersCode??"";
            $buyersName = $request->buyersName??"";
            $dateCreated = $request->dateCreated??"";

            $endpoint = null;

            $data = collect();  
            if ($request->has('sap_server')) {
                $sapServer = $request->sap_server;

                $client = new Client();
                switch ($sapServer) {
                    case 'whi':
                        $endpointData = ThirdrdPartyEndpoint::where("Code","WHI-DIST")->first();
                        break;
                    case 'pbi':
                        $endpointData = ThirdrdPartyEndpoint::where("Code","PBI-DIST")->first();
                        break;
                    case 'ccc':
                        $endpointData = ThirdrdPartyEndpoint::where("Code","CCC-DIST")->first();
                        break;
                    default:
                        $endpointData = null;
                }
                
                if (!empty($endpointData) > 0) {

                    $endpoint = $endpointData->Endpoint."?page={$page}&limit={$limit}";

                    if (!empty($start)) {
                        if(empty($end)){
                            $end = $start;
                        }
                        $endpoint = $endpoint."&startDate={$start}&endDate={$end}";
                    }

                    if (!empty($buyersCode)) {
                        $endpoint = $endpoint."&buyersCode={$buyersCode}";
                    }

                    if (!empty($buyersName)) {
                        $endpoint = $endpoint."&buyersName={$buyersName}";
                    }

                    if (!empty($dateCreated)) {
                        $endpoint = $endpoint."&dateCreated={$dateCreated}";
                    }

                    $sapResponse = $client->request('GET', $endpoint, [
                        'headers' => [
                            'Accept' => 'application/json', 
                            'apiKey' => $endpointData->ApiKey
                        ]
                    ]);

                    if ($sapResponse && $sapResponse->getStatusCode() === 200) {
                        $processedList = ProcessedOrders::with(['ProcessedOrderStatus'])->select('OrderStatus','CardCode')->where('SapServer', $sapServer)->get()->pluck("ProcessedOrderStatus.description","CardCode");
                        // dd($processedList);
                        $body = $sapResponse->getBody()->getContents();
                        $allData = collect(json_decode($body));
                        $dataList = array();
                        $response["isSuccess"] = true;
                        $response["message"] = "Successfully retrieved information.";
                        $response["total"] = $allData["total"];
                        
                        foreach ($allData["data"] as $key => $value) {
                            $dataList[] = [
                                "DocDate"=> Carbon::parse($value->DocDate)->format('Y-m-d'),
                                "BuyersCode"=>$value->BuyersCode,
                                "CardName"=>$value->CardName,
                                "Count"=>$value->Count,
                                "OrderStatus"=>(isset($processedList[$value->BuyersCode])?$processedList[$value->BuyersCode]:''),
                                "Remarks"=> (isset($processedList[$value->BuyersCode])?$processedList[$value->BuyersCode]:'')
                            ];
                        }
                        $response["data"] = $dataList;
                    }
                    $isSuccess = true;
                }
            }
            
        } catch (\Throwable $th) {
            Log::error("ERROR IN GETTING SAP ORDER LIST: ".$th);
            dd("ERROR IN GETTING SAP ORDER LIST: ".$th);
        }
        
        if ($isSuccess) {
            return response()->json($response, 200);
        }else{
            return response()->json($response, 400);
        }
    }

    public function store(Request $request)
    {
        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to save order."
        ];
        $isSuccess = false;
        DB::beginTransaction();
        try {
            $sapServer  = $request->input('sapServer');
            $cardCode   = $request->input('cardCode');
            $cardName   = $request->input('cardName');
            $docDate   = $request->input('docDate');

            if(ProcessedOrders::where('CardCode',$cardCode)->exists()){
                $response = [
                    "isSuccess"=>false,
                    "message"=>"Order already exists."
                ];
                return response()->json($response,400);
            }

            $processOrder = ProcessedOrders::create([
                'SapServer' => $sapServer,
                'CardCode'   => $cardCode,
                'CardName'   => $cardName,
                'MinDocDate' => $docDate
            ]);
            
            if (isset($processOrder->id)) {
                $processOrderId = $processOrder->id;
                $getOrderListByCode = $this->SapOrderList($cardCode,$sapServer,"");
                if ($getOrderListByCode["isSuccess"]) {
                    $data = $getOrderListByCode["data"];
                    if (count($data) > 0) {
                        foreach ($data as $key => $value) {
                            $collectedData = collect($value);
                            $cardCode = $collectedData["BuyersCode"];
                            $contactData = collect($collectedData["contact_name"]);
                            $order = Order::create([
                                'process_order_id' => $processOrderId,
                                'sap_server' => $sapServer,
                                'DocNum'     => $collectedData["DocNum"],
                                'CardCode'   => $collectedData["BuyersCode"],
                                'CardName'   => $collectedData["CardName"],
                                'Label'      => $collectedData["U_Label"],
                                'Packaging'  => $collectedData["U_Packaging"],
                                'BuyersPO'  => $collectedData["U_BuyersPO"],
                                'ContactName'  => $contactData["Name"]??"",
                                'LoadingPort'  => $collectedData["LoadingPort"]??"",
                                'PortOfDestination'  => $collectedData["PortOfDestination"]??""
                            ]);

                            if (isset($order->id)) {
                                $orderId = $order->id;
                                $itemsList = collect($collectedData["items"]);
                                if (count($itemsList)) {
                                    foreach($itemsList as $key => $value){
                                        $itemData = collect($value);
                                        OrderItem::create([
                                            'order_id'   => $orderId,
                                            'CardCode'   => $cardCode,
                                            'ItemCode'   => $itemData['ItemCode'] ?? null,
                                            'Dscription' => $itemData['Dscription'] ?? null,
                                            'Quantity'   => isset($itemData['Quantity']) ? (int)$itemData['Quantity'] : 0
                                        ]);
                                    }
                                }
                            }
                        }
                        DB::commit();
                        $isSuccess = true;
                        $response = [
                            "isSuccess"=>$isSuccess,
                            "message"=>"Order and items successfully saved."
                        ];
                    }else{
                        DB::rollBack();
                        Log::error('ERROR IN SAVING ORDER ITEMS - NO BUYERS CODE DATA');
                    }
                }
            }
        } catch(\Exception $e){
            DB::rollBack();
            Log::error('ERROR IN SAVING ORDER ITEMS: '.$e->getMessage());
        }

        if ($isSuccess) {
            return response()->json($response,200);
        }else{
            return response()->json($response,400);
        }
    }

    public function SoNumberDetails(Request $request){
        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to retrieve information.",
            "data"=>[]
        ];
        $isSuccess = false;
        try {
            $cardCode = $request->buyersCpde??"";
            $sapServer = $request->sapServer??"";
            $soNumber = $request->soNo??"";
            $page = $request->page??"1";
            $limit = $request->limit??"1";
            $soNumberDetails = $this->SapOrderList($cardCode,$sapServer,$soNumber);
            if ($soNumberDetails["isSuccess"]) {
                $incoTerms = $soNumberDetails["data"][0]->IncoTerms??"";
                $PortOfOrigin = $soNumberDetails["data"][0]->LoadingPort??"";
                $PortOfDestination = $soNumberDetails["data"][0]->PortOfDestination??"";
                $trackingPoints = ["tracking_points" => Helper::LoadTrackingPointsPerIncoTerms($incoTerms,["PortOfOrigin"=>$PortOfOrigin,"PortOfDestination"=>$PortOfDestination])];

                $response = [
                    "isSuccess"=>true,
                    "message"=>"Successfully retrieved information.",
                    "data"=> array_merge($soNumberDetails["data"],$trackingPoints)
                ];
                $isSuccess = true;
            }
        } catch (\Throwable $th) {
            Log::error("ERROR IN GETTING SO NUMBER DETAILS: ".$th->getMessage());
        }

        if ($isSuccess) {
            return response()->json($response,200);
        }else{
            return response()->json($response,400);
        }
    }
    public function SapOrderList($cardCode,$sapServer,$soNumber){
        
        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to retrieve information.",
            "data"=>[]
        ];

        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 100;

            $endpoint = null;

            $data = collect();  
            if (!empty($sapServer)) {

                $client = new Client();
                switch ($sapServer) {
                    case 'whi':
                        $endpointData = ThirdrdPartyEndpoint::where("Code","WHI")->first();
                        break;
                    case 'pbi':
                        $endpointData = ThirdrdPartyEndpoint::where("Code","PBI")->first();
                        break;
                    case 'ccc':
                        $endpointData = ThirdrdPartyEndpoint::where("Code","CCC")->first();
                        break;
                    default:
                        $endpointData = null;
                }
                
                if (!empty($endpointData) > 0) {

                    $endpoint = $endpointData->Endpoint."?buyersCode={$cardCode}&page={$page}&soNumber={$soNumber}&limit={$limit}";
                    $sapResponse = $client->request('GET', $endpoint, [
                        'headers' => [
                            'Accept' => 'application/json', 
                            'apiKey' => $endpointData->ApiKey
                        ]
                    ]);

                    if ($sapResponse && $sapResponse->getStatusCode() === 200) {
                        $body = $sapResponse->getBody()->getContents();
                        $allData = collect(json_decode($body));
                        $response["isSuccess"] = true;
                        $response["message"] = "Successfully retrieved information.";
                        $response["data"] = $allData["data"];
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error("ERROR IN GETTING SAP ORDER LIST BY CODE - {$cardCode}: ".$th);
        }
        
        return $response;
    }
}
