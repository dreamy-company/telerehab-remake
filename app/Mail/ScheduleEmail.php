<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $scheduleDetails
    ) {}

    public function build()
    {
        return $this
            ->subject('Your Consultation Schedule Details')
            ->markdown('livewire.doctor.schedule-email', [
                'scheduleDetails' => $this->scheduleDetails
            ]);
    }
}
