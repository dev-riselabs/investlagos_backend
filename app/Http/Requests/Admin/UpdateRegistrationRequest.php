<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $registrationId = $this->route('registration')?->id;

        return [
            'title'           => ['sometimes', 'string', 'max:20'],
            'first_name'      => ['sometimes', 'string', 'max:100'],
            'last_name'       => ['sometimes', 'string', 'max:100'],
            'gender'          => ['sometimes', 'nullable', 'string', 'max:30'],
            'email'           => ['sometimes', 'email:rfc', 'max:255', Rule::unique('registrations', 'email')->ignore($registrationId)],
            'phone'           => ['sometimes', 'string', 'max:30'],
            'nationality'     => ['sometimes', 'string', 'max:100'],
            'country'         => ['sometimes', 'string', 'max:100'],
            'organization'    => ['sometimes', 'string', 'max:255'],
            'industry'        => ['sometimes', 'string', 'max:100'],
            'org_type'        => ['sometimes', 'string', 'max:100'],
            'attending_as'    => ['sometimes', 'string', 'max:100'],
            'sector'          => ['sometimes', 'string', 'max:100'],
            'deal_room'       => ['sometimes', 'in:Yes,No'],
            'deal_room_role'  => ['sometimes', 'nullable', 'string', 'max:100'],
            'attendance_mode' => ['sometimes', 'string', 'max:50'],
            'dietary'         => ['sometimes', 'nullable', 'string', 'max:1000'],
            'accessibility'   => ['sometimes', 'nullable', 'string', 'max:1000'],
            'other_requests'  => ['sometimes', 'nullable', 'string', 'max:1000'],
            'consent_updates' => ['sometimes', 'boolean'],
            'consent_media'   => ['sometimes', 'boolean'],
            'heard_about'     => ['sometimes', 'string', 'max:100'],
            'objective'       => ['sometimes', 'string', 'max:100'],
        ];
    }
}
