<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Envelope;
use Illuminate\Mail\Markdown;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mail_details;

    /**
     * Create a new message instance.
     *
     * @param array $mail_details
     * @return void
     */
    public function __construct($mail_details)
    {
        $this->mail_details = $mail_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Send Email';

        return $this->subject($subject)
                    ->view('email')
                    ->with([
                        'mail_details' => $this->mail_details,
                    ]);
    }
}
