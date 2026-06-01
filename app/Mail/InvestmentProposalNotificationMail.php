<?php

namespace App\Mail;

use App\Models\InvestmentProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvestmentProposalNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public InvestmentProposal $proposal)
    {
    }

    public function build(): static
    {
        return $this
            ->subject('We have received your Invest Lagos 3.0 Project Proposal')
            ->view('emails.investment-proposal-notification')
            ->with(['proposal' => $this->proposal]);
    }
}
