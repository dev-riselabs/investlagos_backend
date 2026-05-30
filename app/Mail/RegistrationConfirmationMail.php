<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration)
    {
    }

    public function build(): static
    {
        return $this
            ->subject('Your Invest Lagos 3.0 Registration is Confirmed')
            ->view('emails.registration-confirmation')
            ->with(['registration' => $this->registration]);
    }
}
