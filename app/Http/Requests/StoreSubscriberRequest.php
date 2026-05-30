<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriberRequest extends FormRequest
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
            'first_name'   => $this->input('first_name', $this->input('firstName')),
            'last_name'    => $this->input('last_name', $this->input('lastName')),
            'job_title'    => $this->input('job_title', $this->input('jobTitle')),
            'company_name' => $this->input('company_name', $this->input('companyName')),
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name'   => ['required', 'string', 'max:100'],
            'last_name'    => ['required', 'string', 'max:100'],
            'gender'       => ['nullable', 'string', 'in:Male,Female,Prefer not to say'],
            'country'      => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email:rfc', 'max:255', Rule::unique('subscribers', 'email')],
            'industry'     => ['nullable', 'string', 'max:100'],
            'job_title'    => ['nullable', 'string', 'max:150'],
            'company_name' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already subscribed.',
        ];
    }
}
