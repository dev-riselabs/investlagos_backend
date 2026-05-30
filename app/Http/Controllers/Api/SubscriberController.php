<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriberRequest;
use App\Mail\SubscriberWelcomeMail;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SubscriberController extends Controller
{
    public function store(StoreSubscriberRequest $request): JsonResponse
    {
        $subscriber = Subscriber::create($request->validated());

        // A mail failure must not break the subscribe response.
        $mailSent = true;
        try {
            Mail::to($subscriber->email)
                ->send(new SubscriberWelcomeMail($subscriber));
        } catch (Throwable $e) {
            $mailSent = false;
            Log::error('Subscriber welcome email failed', [
                'subscriber_id' => $subscriber->id,
                'email'         => $subscriber->email,
                'exception'     => $e->getMessage(),
            ]);
        }

        return response()->json([
            'message'   => 'Subscription successful.',
            'mail_sent' => $mailSent,
            'data'      => $subscriber,
        ], 201);
    }
}
