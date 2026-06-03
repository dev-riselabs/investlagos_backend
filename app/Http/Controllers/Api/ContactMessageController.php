<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactMessageController extends Controller
{
    public function store(StoreContactMessageRequest $request): JsonResponse
    {
        $contactMessage = ContactMessage::create($request->validated());

        // Optionally notify the admin team — swallowed so the form always succeeds.
        try {
            $adminEmail = config('mail.admin_address');
            if ($adminEmail) {
                Mail::raw(
                    "New contact message from {$contactMessage->full_name} <{$contactMessage->email}>.\n\n"
                    . "Subject: {$contactMessage->subject}\n\n"
                    . "Message:\n{$contactMessage->message}",
                    function ($m) use ($adminEmail, $contactMessage) {
                        $m->to($adminEmail)
                          ->subject("New Contact Message: {$contactMessage->subject}");
                    }
                );
            }
        } catch (Throwable $e) {
            Log::warning('Admin notification for contact message failed to send', [
                'contact_message_id' => $contactMessage->id,
                'exception'          => $e->getMessage(),
            ]);
        }

        return response()->json([
            'message' => 'Your message has been received. We will get back to you shortly.',
            'data'    => ['id' => $contactMessage->id],
        ], 201);
    }
}
