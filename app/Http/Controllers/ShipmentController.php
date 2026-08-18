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
            $orderColumn = $request->input('order_column');
            $orderDir = $request->input('order_dir', 'asc');

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
                "po.cbw_doc_status",
                "sd.invoice_number",
                DB::raw("DATE_FORMAT(sd.atd_origin, '%Y-%m-%d') as atdOrigin"),
                DB::raw("DATE_FORMAT(sd.eta_destination, '%Y-%m-%d') as etaDestination"),
                DB::raw("DATE_FORMAT(sd.ata_destination, '%Y-%m-%d') as ataDestination"),
                DB::raw("DATE_FORMAT(po.created_at, '%Y-%m-%d') as formatted_created_at"),
                DB::raw("DATE_FORMAT(po.cargo_posting_date, '%Y-%m-%d') as cargo_posting_date"),
                DB::raw("DATE_FORMAT(po.AvailabilityDate, '%Y-%m-%d') as AvailabilityDate"),
                DB::raw("COALESCE(ds.description,'Pending') as shipmentStatus"),
                "sd.atp_date",
                "sd.dt_date",
                "sd.notification_enabled"
            )
            ->leftJoin("shipment_details as sd","sd.process_order_id","po.id")
            ->leftJoin("delivery_status as ds","ds.code","=","sd.delivery_status")
            ->where("po.CargoStatus","L")
            ->whereNull("po.is_coload");

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
                        ->orWhere('po.cbw_doc_status', 'LIKE', "%{$search}%")
                        ->orWhere('po.cargo_posting_date', 'LIKE', "%{$search}%")
                        ->orWhere('sd.invoice_number', 'LIKE', "%{$search}%")
                        ->orWhere('sd.atd_origin', 'LIKE', "%{$search}%")
                        ->orWhere('sd.eta_destination', 'LIKE', "%{$search}%")
                        ->orWhere('sd.ata_destination', 'LIKE', "%{$search}%")
                        ->orWhere('ds.description', 'LIKE', "%{$search}%");
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
                        $q->whereRaw("COALESCE(sd.delivery_status, '') IN ('P', '')");
                    });
                }elseif ($status == "IN-TRANSIT") {
                    $ordersList = $ordersList->where(function($q){
                        $q->whereRaw('COALESCE(sd.delivery_status, "") = "IT"');
                    });
                }elseif ($status == "SHIPPED") {
                    $ordersList = $ordersList->where(function($q){
                        $q->whereRaw('COALESCE(sd.delivery_status, "") <> "P"');
                    });
                }
                elseif ($status == "DELIVERED") {
                    $ordersList = $ordersList->where(function($q){
                        $q->whereRaw('COALESCE(sd.delivery_status, "") = "DLV"');
                    });
                }
                elseif ($status == "IRREGULARITIES") {
                    $ordersList = $ordersList->where(function($q){
                        $q->whereRaw('COALESCE(sd.delivery_status, "") = "DLY"');
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

            $totalCount = (clone $ordersList)->count();

            if (isset($orderColumn) && !empty($orderColumn)) {
                if($orderColumn == "shipmentStatus"){
                    $ordersList->orderBy("ds.description",$orderDir === 'desc' ? 'desc' : 'asc');
                }
                if($orderColumn == "AvailabilityDate"){
                    $ordersList->orderBy("po.AvailabilityDate",$orderDir === 'desc' ? 'desc' : 'asc');
                }
                if($orderColumn == "CardCode"){
                    $ordersList->orderBy("po.CardCode",$orderDir === 'desc' ? 'desc' : 'asc');
                }
                if($orderColumn == "CardName"){
                    $ordersList->orderBy("po.CardName",$orderDir === 'desc' ? 'desc' : 'asc');
                }
                if($orderColumn == "cbw_doc_status"){
                    $ordersList->orderBy("po.cbw_doc_status",$orderDir === 'desc' ? 'desc' : 'asc');
                }
                if($orderColumn == "SapServer"){
                    $ordersList->orderBy("po.SapServer",$orderDir === 'desc' ? 'desc' : 'asc');
                }
                if($orderColumn == "invoice_number"){
                    $ordersList->orderBy("sd.invoice_number",$orderDir === 'desc' ? 'desc' : 'asc');
                }
                if($orderColumn == "atdOrigin"){
                    $ordersList->orderBy("sd.atd_origin",$orderDir === 'desc' ? 'desc' : 'asc');
                }
                if($orderColumn == "etaDestination"){
                    $ordersList->orderBy("sd.eta_destination",$orderDir === 'desc' ? 'desc' : 'asc');
                }
            } else {
                // Default sorting
                $ordersList->orderBy('po.date_loaded', 'desc');
            }

            $ordersList = $ordersList
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
            $sendEmail = isset($request->sendEmail) && $request->sendEmail == "1" ? true:false;

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
                    // 'cbw_doc_status' => $request->cbwDocStatus,
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
                    'vessel_name' => $request->vesselName,
                    'atp_date' => $request->atpd,
                    'dt_date' => $request->dtd,
                    'notification_enabled' => $sendEmail?'1':'0'
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

                    if ($request->deliveryStatus == "DLY") {
                        if (!Carbon::parse($currentEta)->isSameDay(Carbon::parse($request->etaDestination))) 
                        {
                            $updateDelayedShipment = DelayedShipmentUpdate::updateOrCreate(['shipment_details_id' => $savedId],['shipment_details_id' => $savedId,'prev_eta' => $currentEta,'updated_eta' => $request->etaDestination??null,'is_notif_sent' => 0]);   
                        }
                        if ($sendEmail){
                            $this->shipment->SendDelayedNotification($savedId); 
                        }
                    }
                    if ($request->deliveryStatus == "DPT") {
                        if ($sendEmail){
                            $this->shipment->SendCargoDepartedNotification($savedId);
                        }
                    }
                    if ($request->deliveryStatus == "ARVTP") {
                        if ($sendEmail){
                            $this->shipment->SendCargoTranshipmentArrivalNotification($savedId);
                        }
                    }
                    if ($request->deliveryStatus == "LCV") {
                        if ($sendEmail){
                            $this->shipment->SendCargoLoadedInConnectingVesselNotification($savedId);
                        }
                    }
                    if ($request->deliveryStatus == "AD") {
                        if ($sendEmail){
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
