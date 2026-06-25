<?php

namespace App\Mail;

use App\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShipmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subject = "";
    public $templateCode = "";
    public $data = [];
    public function __construct($param)
    {
        $this->data = $param;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

    $templateContent = "";
        $html = ["templateContent" => $templateContent];
        $templateData = EmailTemplate::where("code",$this->templateCode)->first();
        if(!empty($templateData)){
            $this->subject = $templateData->subject;
            foreach ($this->data["data"] as $key => $value) {
                if (empty($templateContent)) {
                    $templateContent = str_replace("{".$key."}",$value,$templateData->content);
                }else{
                    $templateContent = str_replace("{".$key."}",$value,$templateContent);
                }
            }
            $html["templateContent"] = $templateContent;
        }

        return $this->view('email.email_notification',$html)
        ->subject($this->subject);
    }
}
