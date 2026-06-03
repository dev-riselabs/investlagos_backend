<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize incoming camelCase keys to snake_case before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'full_name' => $this->input('full_name', $this->input('fullName')),
        ]);
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:200'],
            'email'     => ['required', 'email:rfc', 'max:255'],
            'subject'   => ['required', 'string', 'max:300'],
            'message'   => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Please enter your full name.',
            'email.required'     => 'Please enter your email address.',
            'email.email'        => 'Please enter a valid email address.',
            'subject.required'   => 'Please enter a subject.',
            'message.required'   => 'Please enter your message.',
        ];
    }
}
