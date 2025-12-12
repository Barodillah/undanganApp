<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $type)
    {
        $this->otp = $otp;
        $this->type = $type;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Judul dinamis berdasarkan type
        $title = $this->type === 'forgot'
            ? 'Reset Password OTP Code'
            : 'Registration OTP Code';

        return $this->subject($title)
                    ->view('emails.otp');
    }
}
