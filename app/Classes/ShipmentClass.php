<?php

namespace App\Classes;

use App\DelayedShipmentUpdate;
use App\Services\NotificationService;
use App\ShipmentDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ShipmentClass{

    protected NotificationService $notification;
    public function __construct(NotificationService $notif)
    {
        $this->notification = $notif;
    }

    public function SendDelayedNotification($id){
        
        $isSuccess = false;
        try {
            $shipmentData = ShipmentDetails::with(['DelayedShipmentUpdate','ProcessedOrder.OrderData','ProcessedOrder.ShipmentFiles'])->where('id',$id)->first();

            $params = [
                'shipmentDetailsId'=>$id,
                'customerName'=>isset($shipmentData->ProcessedOrder->OrderData[0]->ContactName)?$shipmentData->ProcessedOrder->OrderData[0]->ContactName:'',
                'delayReason'=>$shipmentData->remarks,
                'poNumber'=>isset($shipmentData->ProcessedOrder->OrderData[0]->BuyersPO)?$shipmentData->ProcessedOrder->OrderData[0]->BuyersPO:'',
                'buyerInvoice'=>$shipmentData->ProcessedOrder->CardCode."/".$shipmentData->invoice_number,
                'containerNumber'=>$shipmentData->container_number,
                'vesselVoyage'=>$shipmentData->vessel_name,
                'previousEta'=>isset($shipmentData->DelayedShipmentUpdate->prev_eta)?Carbon::parse($shipmentData->DelayedShipmentUpdate->prev_eta)->format('M d, Y'):'',
                'revisedEta'=>!empty($shipmentData->DelayedShipmentUpdate->updated_eta)?Carbon::parse($shipmentData->DelayedShipmentUpdate->updated_eta)->format('M d, Y'):''
            ];

            $attachments = [];

            if (isset($shipmentData->ProcessedOrder->ShipmentFiles) && !empty($shipmentData->ProcessedOrder->ShipmentFiles)) {
                $fileList = $shipmentData->ProcessedOrder->ShipmentFiles;
                foreach ($fileList as $file) {
                    if (Storage::disk('public')->exists($file->file_path)) {
                        $attachments[] = storage_path('app/public/'. $file->file_path);
                    }
                }
            }
            $receivers = !empty($shipmentData->email_recipients) ? explode(",",$shipmentData->email_recipients):[];
            $ccRecipients = !empty($shipmentData->cc_recipients) ? explode(",",$shipmentData->cc_recipients):[];

            // dd($attachments);
            if (count($receivers) > 0) {
                $this->notification->SendEmail("DLYD",$params,$receivers,$ccRecipients,$attachments);
                $isSuccess = true;
            }
                
        } catch (\Throwable $th) {
            Log::error("ERROR IN SENDING CARGO DELAYED NOTIFICATION: ".$th->getMessage());
        }
        
        return $isSuccess;
    }

    public function SendCargoDepartedNotification($id){
        
        $isSuccess = false;
        try {
            $shipmentData = ShipmentDetails::with(['ProcessedOrder.OrderData'])->where('id',$id)->first();
            
            $params = [
                'shipmentDetailsId'=>$id,
                'customerName'=>isset($shipmentData->ProcessedOrder->OrderData[0]->ContactName)?$shipmentData->ProcessedOrder->OrderData[0]->ContactName:'',
                'portOfLoading'=>isset($shipmentData->ProcessedOrder->OrderData[0]->LoadingPort)?$shipmentData->ProcessedOrder->OrderData[0]->LoadingPort:'',
                'poNumber'=>isset($shipmentData->ProcessedOrder->OrderData[0]->BuyersPO)?$shipmentData->ProcessedOrder->OrderData[0]->BuyersPO:'',
                'buyerInvoice'=>$shipmentData->invoice_number,
                'containerNumber'=>$shipmentData->container_number,
                'vesselVoyage'=>$shipmentData->vessel_name,
                'portOfDischarge'=>isset($shipmentData->ProcessedOrder->OrderData[0]->PortOfDestination)?$shipmentData->ProcessedOrder->OrderData[0]->PortOfDestination:'',
                'atd'=>Carbon::parse($shipmentData->atd_origin)->format('M d, Y'),
                'eta'=>Carbon::parse($shipmentData->eta_destination)->format('M d, Y')
            ];

            $receivers = !empty($shipmentData->email_recipients) ? explode(",",$shipmentData->email_recipients):[];
            $ccRecipients = !empty($shipmentData->cc_recipients) ? explode(",",$shipmentData->cc_recipients):[];

            if (count($receivers) > 0) {
                $this->notification->SendEmail("DPTD",$params,$receivers,$ccRecipients);
                $isSuccess = true;
            }
                
        } catch (\Throwable $th) {
            Log::error("ERROR IN SENDING CARGO DEPARTED NOTIFICATION: ".$th->getMessage());
        }
        
        return $isSuccess;
    }

    public function SendCargoTranshipmentArrivalNotification($id){
        
        $isSuccess = false;
        try {
            $shipmentData = ShipmentDetails::with(['ProcessedOrder.OrderData'])->where('id',$id)->first();
            
            $params = [
                'shipmentDetailsId'=>$id,
                'customerName'=>isset($shipmentData->ProcessedOrder->OrderData[0]->ContactName)?$shipmentData->ProcessedOrder->OrderData[0]->ContactName:'',
                'portOfLoading'=>isset($shipmentData->ProcessedOrder->OrderData[0]->LoadingPort)?$shipmentData->ProcessedOrder->OrderData[0]->LoadingPort:'',
                'poNumber'=>isset($shipmentData->ProcessedOrder->OrderData[0]->BuyersPO)?$shipmentData->ProcessedOrder->OrderData[0]->BuyersPO:'',
                'buyerInvoice'=>$shipmentData->invoice_number
            ];

            $receivers = !empty($shipmentData->email_recipients) ? explode(",",$shipmentData->email_recipients):[];
            $ccRecipients = !empty($shipmentData->cc_recipients) ? explode(",",$shipmentData->cc_recipients):[];

            if (count($receivers) > 0) {
                $this->notification->SendEmail("ARVTP",$params,$receivers,$ccRecipients);
                $isSuccess = true;
            }
                
        } catch (\Throwable $th) {
            Log::error("ERROR IN SENDING CARGO TRANSHIPMENT ARRIVAL: ".$th->getMessage());
        }
        
        return $isSuccess;
    }

    public function SendCargoLoadedInConnectingVesselNotification($id){
        
        $isSuccess = false;
        try {
            $shipmentData = ShipmentDetails::with(['ProcessedOrder.OrderData'])->where('id',$id)->first();
            
            $params = [
                'shipmentDetailsId'=>$id,
                'customerName'=>isset($shipmentData->ProcessedOrder->OrderData[0]->ContactName)?$shipmentData->ProcessedOrder->OrderData[0]->ContactName:'',
                'poNumber'=>isset($shipmentData->ProcessedOrder->OrderData[0]->BuyersPO)?$shipmentData->ProcessedOrder->OrderData[0]->BuyersPO:'',
                'buyerInvoice'=>$shipmentData->invoice_number,
                'containerNumber'=>$shipmentData->container_number,
                'vesselVoyage'=>$shipmentData->vessel_name,
                'portOfLoading'=>isset($shipmentData->ProcessedOrder->OrderData[0]->LoadingPort)?$shipmentData->ProcessedOrder->OrderData[0]->LoadingPort:'',
                'portOfDischarge'=>isset($shipmentData->ProcessedOrder->OrderData[0]->PortOfDestination)?$shipmentData->ProcessedOrder->OrderData[0]->PortOfDestination:'',
                'atd'=>Carbon::parse($shipmentData->atd_origin)->format('M d, Y'),
                'eta'=>Carbon::parse($shipmentData->eta_destination)->format('M d, Y')
            ];

            $receivers = !empty($shipmentData->email_recipients) ? explode(",",$shipmentData->email_recipients):[];
            $ccRecipients = !empty($shipmentData->cc_recipients) ? explode(",",$shipmentData->cc_recipients):[];

            if (count($receivers) > 0) {
                $this->notification->SendEmail("LCV",$params,$receivers,$ccRecipients);
                $isSuccess = true;
            }
                
        } catch (\Throwable $th) {
            Log::error("ERROR IN SENDING CARGO TRANSHIPMENT ARRIVAL: ".$th->getMessage());
        }
        
        return $isSuccess;
    }

    public function SendCargoArrivedAtDestinationPortNotification($id){
        
        $isSuccess = false;
        try {
            $shipmentData = ShipmentDetails::with(['ProcessedOrder.OrderData'])->where('id',$id)->first();
            
            $params = [
                'shipmentDetailsId'=>$id,
                'customerName'=>isset($shipmentData->ProcessedOrder->OrderData[0]->ContactName)?$shipmentData->ProcessedOrder->OrderData[0]->ContactName:'',
                'poNumber'=>isset($shipmentData->ProcessedOrder->OrderData[0]->BuyersPO)?$shipmentData->ProcessedOrder->OrderData[0]->BuyersPO:'',
                'buyerInvoice'=>$shipmentData->invoice_number,
                'containerNumber'=>$shipmentData->container_number,
                'vesselVoyage'=>$shipmentData->vessel_name,
                'destinationPort'=>isset($shipmentData->ProcessedOrder->OrderData[0]->PortOfDestination)?$shipmentData->ProcessedOrder->OrderData[0]->PortOfDestination:'',
                'ata'=>!empty($shipmentData->ata_destination)?Carbon::parse($shipmentData->ata_destination)->format('M d, Y'):''
            ];

            $receivers = !empty($shipmentData->email_recipients) ? explode(",",$shipmentData->email_recipients):[];
            $ccRecipients = !empty($shipmentData->cc_recipients) ? explode(",",$shipmentData->cc_recipients):[];

            if (count($receivers) > 0) {
                $this->notification->SendEmail("ARVDP",$params,$receivers,$ccRecipients);
                $isSuccess = true;
            }
                
        } catch (\Throwable $th) {
            Log::error("ERROR IN SENDING CARGO DESTINATION PORT ARRIVAL NOTIFICATION: ".$th->getMessage());
        }
        
        return $isSuccess;
    }

}