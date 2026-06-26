<?php

namespace App\Http\Controllers;

use App\DeliveryStatus;
use App\Mail\ShipmentNotification;
use App\Order;
use App\ProcessedOrders;
use App\Regions;
use App\Services\NotificationService;
use App\ShipmentDetails;
use App\ShipmentFiles;
use App\ShipmentTracking;
use App\TrackingPoints;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ShipmentController extends Controller
{
    protected NotificationService $notification;
    public function __construct(NotificationService $notif)
    {
        $this->middleware('auth');
        $this->notification = $notif;
    }
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
                $q->where("AvailabilityDate","<>","")->where("PickupDate","<>","")->where("CargoStatus","L");
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
            $receivers = $request->receiver??[];
            $ccRecipients = $request->cc??[];
            $receiversToSave = "";
            $ccRecipientsToSave = "";
            $savedId = "";

            if (count($receivers) > 0) {
                $receiversToSave = implode(",",$receivers);
            }
            if (count($ccRecipients) > 0) {
                $ccRecipientsToSave = implode(",",$ccRecipients);
            }
            $shipmentDetailsData = ShipmentDetails::where(['process_order_id'=>$request->id])->first();
            
            if (!empty($shipmentDetailsData)) {
                $currentTrackingPoint = $shipmentDetailsData->tracking_points;
                $savedId = $shipmentDetailsData->id;
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
                    'email_recipients' => $receiversToSave,
                    'cc_recipients' => $ccRecipientsToSave,
                    'vessel_name' => $request->vesselName
                ]);

                if ($save) {
                    if (!empty($request->trackPoints) && $currentTrackingPoint !== $request->trackPoints) {
                        ShipmentTracking::create([
                            'shipment_details_id' => $savedId,
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
                    'email_recipients' => $receiversToSave,
                    'cc_recipients' => $ccRecipientsToSave,
                    'vessel_name' => $request->vesselName
                ]);

                if ($save) {
                    $savedId = $save->id;
                    if (!empty($request->trackPoints)) {
                        ShipmentTracking::create([
                            'shipment_details_id' => $savedId,
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

            // dd($receivers);
            if (count($receivers)>0 && !empty($savedId)) {
                if ($request->deliveryStatus == "DLY") {
                    Log::info("SENDING EMAIL");
                    $params = [
                        "shipmentDetailsId"=>$savedId,
                        "vesselName"=>$request->containerNumber??"",
                        "delayReason"=>$request->remarks??"",
                        "etd"=>$request->etdOrigin??"",
                        "eta"=>$request->etaDestination??""
                    ];
                    $this->notification->SendEmail("DLYD",$params,$receivers,$ccRecipients);
                    Log::info("DONE SENDING EMAIL");
                }
            }
            
        } catch (\Exception $th) {
            Log::error("FAILED IN UPDATING SHIPMENT DETAILS :".$th);
        }

        if ($isSuccess) {
            return response()->json($response,200);
        }else{
            return response()->json($response,400);
        }
    }

    public function ShipmentFileList(Request $request){
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

            $fileList = ShipmentFiles::where("processed_order_id",$request->shipmentId);

            $totalCount = (clone $fileList)->count();

            $fileList = $fileList->orderBy("id","desc")
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();
            $response["isSuccess"] = true;
            $response["message"] = "Successfully retrieved information.";
            $response["total"] = $totalCount;
            $response["data"] = $fileList;
        } catch (\Throwable $th) {
            Log::error("ERROR IN GETTING SHIPMENT FILES: ".$th);
        }
        
        return $response;
    }

    public function UploadFiles(Request $request)
    {
        $request->validate([
            'attachments.*' => 'required|file|max:10240', // 10MB each
        ]);
        
        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {

                $filename = time() . '_' . $file->getClientOriginalName();

                $path = $file->storeAs(
                    'shipment-files',
                    $filename,
                    'public'
                );

                ShipmentFiles::create([
                    'processed_order_id' => $request->shipment_id,
                    'file_name' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'user_id' => Auth::user()->id,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Files uploaded successfully.'
        ]);
    }
}
