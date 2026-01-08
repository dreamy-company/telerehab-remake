<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
   use Queueable, SerializesModels;

    public function __construct(
        public string $url,
        public string $name
    ) {}

    public function build()
    {
        return $this
            ->subject('Verify Your Email Address')
            ->markdown('livewire.auth.email');
    }
}
