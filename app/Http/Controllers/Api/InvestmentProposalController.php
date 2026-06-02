<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvestmentProposalRequest;
use App\Mail\InvestmentProposalNotificationMail;
use App\Models\InvestmentProposal;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class InvestmentProposalController extends Controller
{
    public function store(StoreInvestmentProposalRequest $request): JsonResponse
    {
        $proposal = InvestmentProposal::create($request->validated());

        // Queue the mail so SMTP latency cannot delay the HTTP response.
        $mailSent = true;
        try {
            Mail::to($proposal->project_owner_email)
                ->queue(new InvestmentProposalNotificationMail($proposal));
        } catch (Throwable $e) {
            $mailSent = false;
            Log::error('Investment proposal notification email failed to queue', [
                'proposal_id' => $proposal->id,
                'email'       => $proposal->project_owner_email,
                'exception'   => $e->getMessage(),
            ]);
        }

        return response()->json([
            'message'   => 'Investment proposal submitted successfully.',
            'mail_sent' => $mailSent,
            'data'      => $proposal,
        ], 201);
    }
}
