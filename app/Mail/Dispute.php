<?php

namespace App\Mail;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Dispute extends Mailable
{
    use Queueable, SerializesModels;

    public $serviceId;
    public $title;
    public $cancelReason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($serviceId, $title, $cancelReason)
    {
        $this->serviceId = $serviceId;
        $this->title = $title;
        $this->cancelReason = $cancelReason;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.dispute');
    }
}
