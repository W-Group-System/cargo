<?php

namespace App\Classes;

use App\Order;
use App\OrderItem;
use App\ProcessedOrders;
use App\ThirdrdPartyEndpoint;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderClass {

    public function SaveCoload($cardCode,$sapServer,$coloadedBy,$coloadOrder=null){
        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to save order."
        ];

        DB::beginTransaction();
        try {
    
            $getOrderListByCode = $this->SapOrderList($cardCode,$sapServer,"");
            if ($getOrderListByCode["isSuccess"]) {
                $data = $getOrderListByCode["data"];
                $processOrder = ProcessedOrders::create([
                    'SapServer' => $sapServer,
                    'CardCode'   => $cardCode,
                    'CardName'   => $data[0]->CardName,
                    'MinDocDate' => $data[0]->DocDate,
                    'is_coload' => 1,
                    'coloaded_by' => $coloadedBy,
                    'coload_order' => $coloadOrder
                ]);
                
                if (isset($processOrder->id)) {
                    $processOrderId = $processOrder->id;
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
                }
            }
            DB::commit();

            $response = [
                "isSuccess"=>true,
                "message"=>"Order and items successfully saved."
            ];
        } catch(\Exception $e){
            DB::rollBack();
            Log::error('ERROR IN SAVING ORDER ITEMS: '.$e->getMessage());
            // dd("CLASS: ".$e->getMessage());
        }

        
        return $response;
        
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