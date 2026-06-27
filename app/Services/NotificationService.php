<?php 

namespace App\Services;

use App\EmailTemplate;
use App\Mail\ShipmentNotification;
use App\NotificationLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService{
    
    public function SendEmail($templateCode,$params,$to=[],$cc=[]){

        try {
            $templateData = EmailTemplate::where("code",$templateCode)->first();
            $html = ["templateContent"=>""];
            if(!empty($templateData)){
                $templateContent = $templateData->content;
                
                foreach ($params as $key => $value) {
                    $templateContent = str_replace("{".$key."}",$value,$templateContent);
                }

                $html = $templateContent;

                $mailService = new ShipmentNotification($html);
                $mailService->subject = $templateData->subject;
                $mail = Mail::to($to);
                if (count($cc)>0) {
                    $mail->cc($cc);
                }
                $mail->send($mailService);

                if (isset($params["shipmentDetailsId"])) {
                    NotificationLogs::create([
                        'shipment_details_id' => $params["shipmentDetailsId"],
                        'template_code' => $templateCode,
                        'user_id' => Auth::user()->id,
                        'subject' => $templateData->subject,
                        'content' => $html,
                        'receiver' => count($to)>0?implode(",",$to):null,
                        'cc' => count($cc)>0?implode(",",$cc):null
                    ]);
                }
            }
        } catch (\Throwable $th) {
            Log::error("FAILED IN SENDING EMAIL: ".$th->getMessage());
        }
    }
}
