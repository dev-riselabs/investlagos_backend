<?php

namespace App\Mail;

use App\Models\Publication;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPublicationNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscriber $subscriber,
        public Publication $publication,
    ) {
    }

    public function build(): static
    {
        return $this
            ->subject('New Publication: ' . $this->publication->title)
            ->view('emails.new-publication-notification')
            ->with([
                'subscriber'  => $this->subscriber,
                'publication' => $this->publication,
            ]);
    }
}
