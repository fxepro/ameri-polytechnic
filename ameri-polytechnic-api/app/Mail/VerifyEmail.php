<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $frontendUrl = env('FRONTEND_URL', config('app.frontend_url', 'http://localhost:4200'));
        $this->verificationUrl = rtrim($frontendUrl, '/') . '/verify-email?token=' . $token;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Verify Your Email Address - American Polytechnic')
                    ->view('emails.verify-email')
                    ->with([
                        'user' => $this->user,
                        'verificationUrl' => $this->verificationUrl,
                    ]);
    }
}

