<?php

namespace App\Http\Controllers;

use App\DeliveryStatus;
use App\Mail\ShipmentNotification;
use App\Order;
use App\ProcessedOrders;
use App\Regions;
use App\ShipmentDetails;
use App\ShipmentTracking;
use App\TrackingPoints;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $data = array();
        $data['ActiveModule'] = 'Shipments';
        $data['trackingPoints'] = TrackingPoints::where('status','A')->pluck('description','code');
        $data['deliveryStatus'] = DeliveryStatus::select('description','code','disabled')->where('status','A')->get();
        $data['regions'] = Regions::pluck('region','id');
        $data['users'] = User::pluck('email','email');
        
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

            $ordersList = ProcessedOrders::with(['ShipmentDetails.DeliveryStatus'])->select(
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
                $ordersList = $ordersList->with(['ShipmentDetails.ShipmentTracking','OrderData.OrderItemList'])->where("id",$request->id);
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

    public function SaveShipmentUpdate(Request $request){
        $response = [
            "message" => "Failed to update shipment information.",
            "isSuccess" => false
        ];
        $isSuccess = false;
        // dd($request->all());
        try {
            $receivers = $request->receiver??null;
            $cc = $request->cc??null;

            if ($receivers != null) {
                $receivers = implode(",",$receivers);
            }
            if ($cc != null) {
                $cc = implode(",",$cc);
            }
            $shipmentDetailsData = ShipmentDetails::where(['process_order_id'=>$request->id])->first();
            
            if (!empty($shipmentDetailsData)) {
                $currentTrackingPoint = $shipmentDetailsData->tracking_points;
                $currentId = $shipmentDetailsData->id;
                $save = $shipmentDetailsData->update([
                    'delivery_status' => $request->deliveryStatus,
                    'tracking_points' => $request->trackPoints,
                    'invoice_number' => $request->invoiceNo,
                    'cbw_doc_status' => $request->cbwDocStatus,
                    'region' => $request->region,
                    'shipping_line' => $request->shippingLine,
                    'ed_bl_number' => $request->blNumber,
                    'container_number' => $request->containerNumber,
                    'courier_tracking' => $request->courierTracking,
                    'etd_origin' => $request->etdOrigin,
                    'atd_origin' => $request->atdOrigin,
                    'eta_destination' => $request->etaDestination,
                    'ata_destination' => $request->ataDestination,
                    'delivery_date' => $request->deliveryDate,
                    'date_docs_completed' => $request->dateDocsCompleted,
                    'remarks' => $request->remarks,
                    'email_recipients' => $receivers,
                    'cc_recipients' => $cc
                ]);

                if ($save) {
                    if (!empty($request->trackPoints) && $currentTrackingPoint !== $request->trackPoints) {
                        ShipmentTracking::create([
                            'shipment_details_id' => $currentId,
                            'tracking_point' => $request->trackPoints,
                            'arrival_date' => Carbon::now(),
                            'status' => $request->deliveryStatus
                        ]);
                    }
                }
            }else{
                $save = ShipmentDetails::create([
                    'process_order_id'=>$request->id,
                    'delivery_status' => $request->deliveryStatus,
                    'tracking_points' => $request->trackPoints,
                    'invoice_number' => $request->invoiceNo,
                    'cbw_doc_status' => $request->cbwDocStatus,
                    'region' => $request->region,
                    'shipping_line' => $request->shippingLine,
                    'ed_bl_number' => $request->blNumber,
                    'container_number' => $request->containerNumber,
                    'courier_tracking' => $request->courierTracking,
                    'etd_origin' => $request->etdOrigin,
                    'atd_origin' => $request->atdOrigin,
                    'eta_destination' => $request->etaDestination,
                    'ata_destination' => $request->ataDestination,
                    'delivery_date' => $request->deliveryDate,
                    'date_docs_completed' => $request->dateDocsCompleted,
                    'remarks' => $request->remarks,
                    'email_recipients' => $receivers,
                    'cc_recipients' => $cc
                ]);

                if ($save) {
                    if (!empty($request->trackPoints)) {
                        ShipmentTracking::create([
                            'shipment_details_id' => $save->id,
                            'tracking_point' => $request->trackPoints,
                            'arrival_date' => Carbon::now(),
                            'status' => $request->deliveryStatus
                        ]);
                    }
                }
            }
            
            $isSuccess = true;
            $response = [
                "message" => "Shipment information updated successfully.",
                "isSuccess" => $isSuccess
            ];

            if ($request->deliveryStatus == "DLY") {
                // dd($receivers);
                if ($receivers != null) {
                    Log::info($receivers);
                    $explodeEmail = explode(",",$receivers);
                    foreach ($explodeEmail as $email) {
                        $mail = new Mail();
                        $params = [
                            "data"=>[
                                "vesselName"=>$request->containerNumber??"",
                                "delayReason"=>$request->remarks??"",
                                "etd"=>$request->etdOrigin??"",
                                "eta"=>$request->etaDestination??""
                            ]
                        ];

                        $mailService = new ShipmentNotification($params);
                        $mailService->templateCode = "DLYD";
                        $mail::to($email)
                        ->send($mailService);
                    }
                    Log::info("DONE SENDING EMAIL");
                }   
            }
            
        } catch (\Exception $th) {
            Log::error("FAILED IN UPDATING SHIPMENT DETAILS :".$th->getMessage());
        }

        if ($isSuccess) {
            return response()->json($response,200);
        }else{
            return response()->json($response,400);
        }
    }
}
