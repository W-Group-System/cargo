<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Order;
use App\OrderItem;
use App\ThirdrdPartyEndpoint;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view('orders.indexV2');
    }

    public function SapOrderList(Request $request){
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
        $this->validate($request, [
            'sap_server' => 'required|string',
            'docnum'     => 'required',
            'cardcode'   => 'nullable|string',
            'cardname'   => 'nullable|string',
            'label'      => 'nullable|string',
            'packaging'  => 'nullable|string',
            'items'      => 'required|array|min:1'
        ]);

        DB::beginTransaction();
        try {
            $sapServer  = $request->input('sap_server');
            $docnum     = $request->input('docnum');
            $cardcode   = $request->input('cardcode');
            $cardname   = $request->input('cardname');
            $label      = $request->input('label');
            $packaging  = $request->input('packaging');
            
            $items     = $request->input('items');

            if(Order::where('docnum',$docnum)->exists()){
                return response()->json(['message'=>'Order already exists.'],409);
            }

            $order = Order::create([
                'sap_server' => $sapServer,
                'DocNum'     => $docnum,
                'CardCode'   => $cardcode,
                'CardName'   => $cardname,
                'Label'      => $label,
                'Packaging'  => $packaging,
            ]);

            foreach($items as $item){
                OrderItem::create([
                    'order_id'   => $order->id,
                    'CardCode'   => $cardcode,
                    'ItemCode'   => $item['ItemCode'] ?? null,
                    'Dscription' => $item['Dscription'] ?? null,
                    'Quantity'   => isset($item['Quantity']) ? (int)$item['Quantity'] : 0,
                ]);
            }

            DB::commit();
            return response()->json(['message'=>'Order and items successfully saved.']);
        } catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message'=>'Failed to save order.',
                'error' => $e->getMessage()
            ],500);
        }
    }
}
