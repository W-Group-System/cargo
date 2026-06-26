<?php

namespace App\Mail;

use App\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ShipmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subject = "";
    public $htmlContent = "";
    public function __construct($html)
    {
        $this->htmlContent = $html;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = ["templateContent" => $this->htmlContent];
        return $this->view('email.email_notification',$data)
        ->subject($this->subject);
    }
}
