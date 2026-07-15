<?php

namespace App\Http\Controllers;

use App\Classes\ShipmentClass;
use App\DelayedShipmentUpdate;
use App\DeliveryStatus;
use App\Order;
use App\ProcessedOrders;
use App\Regions;
use App\Services\NotificationService;
use App\ShipmentDetails;
use App\ShipmentFiles;
use App\ShipmentTracking;
use App\SuggestionVault;
use App\TrackingPoints;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ShipmentController extends Controller
{
    protected NotificationService $notification;
    protected ShipmentClass $shipment;
    public function __construct(NotificationService $notif, ShipmentClass $shipment)
    {
        $this->middleware('auth');
        $this->notification = $notif;
        $this->shipment = $shipment;
    }
    public function index(Request $request)
    {
        $data = array();
        $data['ActiveModule'] = 'Shipments';
        $data['canCreate'] = $request->create;
        $data['canUpdate'] = $request->update;
        $data['canDelete'] = $request->delete;
        
        $data['trackingPoints'] = TrackingPoints::where('status','A')->pluck('description','code');
        $data['deliveryStatus'] = DeliveryStatus::select('description','code','disabled')->where('status','A')->get();
        $data['regions'] = Regions::pluck('region','id');
        $data['users'] = SuggestionVault::where("type","EMAIL")->pluck('suggestion','suggestion');
        
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
            $module = $request->module ?? "";

            $ordersList = ProcessedOrders::from("processed_orders as po")->with(['ShipmentDetails.DeliveryStatus'])->select(
                "po.id",
                "po.SapServer",
                "po.CardCode",
                "po.CardName",
                "po.MinDocDate",
                "po.PickupDate",
                "po.CargoStatus",
                "po.OrderStatus",
                "po.ShipmentStatus",
                "po.is_coload",
                "po.coloaded_by",
                "po.coload_order",
                "sd.cbw_doc_status",
                DB::raw("DATE_FORMAT(po.created_at, '%Y-%m-%d') as formatted_created_at"),
                DB::raw("DATE_FORMAT(po.cargo_posting_date, '%Y-%m-%d') as cargo_posting_date"),
                DB::raw("DATE_FORMAT(po.AvailabilityDate, '%Y-%m-%d') as AvailabilityDate"),
                DB::raw(
                    "CASE 
                        WHEN COALESCE(sd.eta_destination, '') = '' AND po.CargoStatus = 'L' AND COALESCE(sd.delivery_status, '') <> 'IT' AND COALESCE(sd.delivery_status, '') <> 'DLV'
                            THEN 'Pending' 
                        WHEN COALESCE(sd.delivery_status, '') = 'IT'
                            THEN 'In Transit' 
                        WHEN COALESCE(sd.ata_destination, '') <> '' 
                            THEN 'Shipped' ELSE '' 
                    END AS shipmentStatus"
                )
            )
            ->leftJoin("shipment_details as sd","sd.process_order_id","po.id")
            ->where(function($q){
                $q->whereRaw("COALESCE(po.AvailabilityDate,'') <> ''")->whereRaw("COALESCE(po.PickupDate,'') <> ''")
                ->where("po.CargoStatus","L");
            });

            if (isset($request->id) && !empty($request->id)) {
                $ordersList = $ordersList->with(['ShipmentDetails.ShipmentTracking','OrderData.OrderItemList'])->where("po.id",$request->id);
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
                    $query->where('po.CardCode', 'LIKE', "%{$search}%")
                        ->orWhere('po.CardName', 'LIKE', "%{$search}%")
                        ->orWhere('po.SapServer', 'LIKE', "%{$search}%")
                        ->orWhere('sd.cbw_doc_status', 'LIKE', "%{$search}%")
                        ->orWhere('po.cargo_posting_date', 'LIKE', "%{$search}%");
                });
            }

            if (isset($request->warehouse) && !empty(isset($request->warehouse))) {
                $warehouse = $request->warehouse;
                $ordersList = $ordersList->where("po.SapServer", $warehouse);
            }

            if (isset($request->status) && !empty(isset($request->status))) {
                $status = $request->status;
                if ($status == "PENDING") {
                    $ordersList = $ordersList->where(function($q){
                        $q->whereNotNull('po.AvailabilityDate')
                        ->whereRaw('COALESCE(po.AvailabilityDate, "") <> ""')
                        ->whereRaw('COALESCE(po.PickupDate, "") <> ""')
                        ->whereRaw('COALESCE(po.PickupDate, "") <> ""')
                        ->where('po.CargoStatus', 'L')
                        ->whereRaw('COALESCE(sd.eta_destination, "") = ""');
                    });
                }elseif ($status == "IN-TRANSIT") {
                    $ordersList = $ordersList->where(function($q){
                        $q->whereRaw('COALESCE(sd.eta_destination, "") <> ""')
                          ->whereRaw('COALESCE(sd.ata_destination, "") = ""');
                    });
                }elseif ($status == "SHIPPED" || $status == "DELIVERED") {
                    $ordersList = $ordersList->where(function($q){
                        $q->whereRaw('COALESCE(sd.ata_destination, "") <> ""');
                    });
                }
                elseif ($status == "IRREGULARITIES") {
                    $ordersList = $ordersList->where(function($q){
                        $q->where('sd.delivery_status',"DLY");
                    });
                }
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = Carbon::parse($request->start_date)->startOfDay();
                $end   = Carbon::parse($request->end_date)->endOfDay();

                // if ($module == "Shipment") {
                //     $ordersList->whereBetween('po.cargo_posting_date', [$start, $end]);
                // }else{
                    $ordersList->whereBetween('po.AvailabilityDate', [$start, $end]);
                // }
                
            }
            $ordersList = $ordersList->where("po.is_coload",null);

            $totalCount = (clone $ordersList)->count();

            $ordersList = $ordersList->orderBy("po.AvailabilityDate","desc")
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
            $currentEta = null;

            if (count($receivers) > 0) {
                $receiversToSave = implode(",",$receivers);
            }
            if (count($ccRecipients) > 0) {
                $ccRecipientsToSave = implode(",",$ccRecipients);
            }

            $mergeEmails = array_merge($receivers,$ccRecipients);
            if (count($mergeEmails)>0) {
                foreach ($mergeEmails as $value) {
                    SuggestionVault::updateOrCreate(["suggestion"=>$value,"type"=>"EMAIL"],["suggestion"=>$value,"type"=>"EMAIL"]);
                }
            }

            $shipmentDetailsData = ShipmentDetails::where(['process_order_id'=>$request->id])->first();
            
            if (!empty($shipmentDetailsData)) {
                $currentTrackingPoint = $shipmentDetailsData->tracking_points;
                $savedId = $shipmentDetailsData->id;
                $currentEta = !empty($shipmentDetailsData->eta_destination)?Carbon::parse($shipmentDetailsData->eta_destination):null;
                $currentDeliveryStatus = $shipmentDetailsData->delivery_status;
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

                    if ($currentDeliveryStatus !== $request->deliveryStatus) {
                        if ($request->deliveryStatus == "DLY") {
                            DelayedShipmentUpdate::updateOrCreate(['shipment_details_id' => $savedId],['shipment_details_id' => $savedId,'prev_eta' => $currentEta,'updated_eta' => $request->etaDestination??null,'is_notif_sent' => 0]);    
                            $this->shipment->SendDelayedNotification($savedId);
                        }
                        if ($request->deliveryStatus == "DPT") {
                            $this->shipment->SendCargoDepartedNotification($savedId);
                        }
                        if ($request->deliveryStatus == "ARVTP") {
                            $this->shipment->SendCargoTranshipmentArrivalNotification($savedId);
                        }
                        if ($request->deliveryStatus == "LCV") {
                            $this->shipment->SendCargoLoadedInConnectingVesselNotification($savedId);
                        }
                        if ($request->deliveryStatus == "AD") {
                            $this->shipment->SendCargoArrivedAtDestinationPortNotification($savedId);
                        }
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

            $fileList = ShipmentFiles::select(
                'id',
                'file_name',
                'file_path',
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as formatted_created_at")
            )
            ->where("processed_order_id",$request->shipmentId);

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

    public function DeleteFile(Request $request){
        
        $response = [
            "message"=>"Failed to delete file.",
            "isSuccess"=>false
        ];
        try {
            $fileData = ShipmentFiles::where("id",$request->id)->first();
            if ($fileData && Storage::disk('public')->exists($fileData->file_path)) {
                Storage::disk('public')->delete($fileData->file_path);
            }

            $fileData->delete(); // Delete the database record

            $response = [
                "message"=>"File deleted successfully.",
                "isSuccess"=>true
            ];
        } catch (\Throwable $th) {
            Log::error("FAILED TO DELETE FILE: ".$th->getMessage());
        }

        if($response["isSuccess"]){
            return response()->json($response,200);
        }else{
            return response()->json($response,400);
        }
    }
}
