<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationRequest;
use App\Mail\RegistrationNotificationMail;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class RegistrationController extends Controller
{
    public function store(StoreRegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Clear deal_room_role when user opted out of the deal room.
        if (($data['deal_room'] ?? 'No') === 'No') {
            $data['deal_room_role'] = null;
        }

        $registration = Registration::create($data);

        // A mail failure must not break the registration response.
        $mailSent = true;
        try {
            Mail::to($registration->email)
                ->send(new RegistrationNotificationMail($registration));
        } catch (Throwable $e) {
            $mailSent = false;
            Log::error('Registration notification email failed', [
                'registration_id' => $registration->id,
                'email'           => $registration->email,
                'exception'       => $e->getMessage(),
            ]);
        }

        return response()->json([
            'message'   => 'Registration submitted successfully.',
            'mail_sent' => $mailSent,
            'data'      => $registration,
        ], 201);
    }
}
