<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriberWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscriber $subscriber)
    {
    }

    public function build(): static
    {
        return $this
            ->subject('Welcome to Invest Lagos Updates')
            ->view('emails.subscriber-welcome')
            ->with(['subscriber' => $this->subscriber]);
    }
}
