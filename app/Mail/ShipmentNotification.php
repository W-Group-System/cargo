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
    protected $files = [];
    public function __construct($html,$fileAttachements=[])
    {
        $this->htmlContent = $html;
        $this->files = $fileAttachements;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $mail = $this->view('email.email_notification', [
                'templateContent' => $this->htmlContent,
            ])
            ->subject($this->subject);

        foreach ($this->files as $file) {
            $mail->attach($file);
        }

        return $mail;
    }
}
