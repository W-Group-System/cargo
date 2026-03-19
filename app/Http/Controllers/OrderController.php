<?php

namespace App\Http\Controllers;

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
        return view('orders.indexV2');
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

                    if (!empty($start) && !empty($end)) {
                        $endpoint = $endpoint."&startDate={$start}&endDate={$end}";
                    }
                    
                    $sapResponse = $client->request('GET', $endpoint);

                    if ($sapResponse && $sapResponse->getStatusCode() === 200) {
                        $body = $sapResponse->getBody()->getContents();
                        $allData = collect(json_decode($body));
                        $response["isSuccess"] = true;
                        $response["message"] = "Successfully retrieved information.";
                        $response["total"] = $allData["total"];
                        $response["data"] = $allData["data"];
                    }
                    $isSuccess = true;
                }
            }
            
        } catch (\Throwable $th) {
            Log::error("ERROR IN GETTING SAP ORDER LIST: ".$th);
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
                $getOrderListByCode = $this->SapOrderList($cardCode,$sapServer);
                if ($getOrderListByCode["isSuccess"]) {
                    $data = $getOrderListByCode["data"];
                    if (count($data) > 0) {
                        foreach ($data as $key => $value) {
                            $collectedData = collect($value);
                            $cardCode = $collectedData["BuyersCode"];
                            $order = Order::create([
                                'process_order_id' => $processOrderId,
                                'sap_server' => $sapServer,
                                'DocNum'     => $collectedData["DocNum"],
                                'CardCode'   => $collectedData["BuyersCode"],
                                'CardName'   => $collectedData["CardName"],
                                'Label'      => $collectedData["U_Label"],
                                'Packaging'  => $collectedData["U_Packaging"]
                            ]);

                            if (isset($order->id)) {
                                $orderId = $order->id;
                                $itemsList = collect($collectedData["items"]);
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
                }
            }
            DB::commit();
            $isSuccess = true;
            $response = [
                "isSuccess"=>$isSuccess,
                "message"=>"Order and items successfully saved."
            ];
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

    public function SapOrderList($cardCode,$sapServer){
        
        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to retrieve information.",
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

                    $endpoint = $endpointData->Endpoint."?buyersCode={$cardCode}&page={$page}&limit={$limit}";
                    $sapResponse = $client->request('GET', $endpoint);

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
