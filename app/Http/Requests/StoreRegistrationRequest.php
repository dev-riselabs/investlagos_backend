<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    private const TITLES = ['Mr', 'Mrs', 'Ms', 'Miss', 'Dr', 'Prof', 'Engr', 'Chief'];
    private const GENDERS = ['Male', 'Female', 'Prefer not to say'];
    private const INDUSTRIES = [
        'Infrastructure', 'Technology & Innovation', 'Financial Services', 'Energy',
        'Manufacturing', 'Real Estate', 'Healthcare', 'Education', 'Logistics',
        'Creative Economy', 'Agriculture', 'Sports', 'Tourism & Hospitality',
    ];
    private const ORG_TYPES = [
        'Private Sector', 'Public Sector', 'Non-Profit / NGO', 'Government Agency',
        'Development Partner', 'Startup', 'Academic / Research', 'Other',
    ];
    private const ATTENDING_AS = [
        'Investor', 'Business Executive', 'Government Official', 'Diplomat',
        'Development Partner', 'Entrepreneur', 'Startup Founder', 'Student',
        'Media', 'Sponsor', 'Exhibitor', 'Speaker', 'General Delegate',
    ];
    private const DEAL_ROOM_ROLES = [
        'Investor', 'Project Sponsor', 'Business Seeking Investment',
        'Government Agency', 'Development Partner',
    ];
    private const ATTENDANCE_MODES = ['Attend In-Person', 'Attend Virtually'];
    private const HEARD_ABOUT = [
        'Government Invitation', 'Email', 'Social Media', 'Energy',
        'Friend/Colleague', 'Website', 'Media', 'Other',
    ];
    private const OBJECTIVES = [
        'Explore Investment Opportunities', 'Meet Government Officials',
        'Business Exposure', 'Strategic Partnerships', 'Project Financing',
        'Market Research', 'Policy Engagement', 'Networking', 'Media Coverage',
    ];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize incoming camelCase keys + nested consent object to snake_case
     * before validation runs.
     */
    protected function prepareForValidation(): void
    {
        $consent = $this->input('consent', []);

        $this->merge([
            'first_name'      => $this->input('first_name', $this->input('firstName')),
            'last_name'       => $this->input('last_name', $this->input('lastName')),
            'org_type'        => $this->input('org_type', $this->input('orgType')),
            'attending_as'    => $this->input('attending_as', $this->input('attendingAs')),
            'deal_room'       => $this->input('deal_room', $this->input('dealRoom')),
            'deal_room_role'  => $this->input('deal_room_role', $this->input('dealRoomRole')),
            'attendance_mode' => $this->input('attendance_mode', $this->input('attendanceMode')),
            'other_requests'  => $this->input('other_requests', $this->input('otherRequests')),
            'heard_about'     => $this->input('heard_about', $this->input('heardAbout')),
            'consent_updates' => $this->input('consent_updates', $consent['updates'] ?? false),
            'consent_media'   => $this->input('consent_media', $consent['media'] ?? false),
        ]);
    }

    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'in:' . implode(',', self::TITLES)],
            'first_name'      => ['required', 'string', 'max:100'],
            'last_name'       => ['required', 'string', 'max:100'],
            'gender'          => ['nullable', 'string', 'in:' . implode(',', self::GENDERS)],
            'email'           => ['required', 'email:rfc', 'max:255', 'unique:registrations,email'],
            'phone'           => ['required', 'string', 'max:30'],
            'nationality'     => ['required', 'string', 'max:100'],
            'country'         => ['required', 'string', 'max:100'],
            'organization'    => ['required', 'string', 'max:255'],
            'industry'        => ['required', 'string', 'in:' . implode(',', self::INDUSTRIES)],
            'org_type'        => ['required', 'string', 'in:' . implode(',', self::ORG_TYPES)],
            'attending_as'    => ['required', 'string', 'in:' . implode(',', self::ATTENDING_AS)],
            'sector'          => ['required', 'string', 'in:' . implode(',', self::INDUSTRIES)],
            'deal_room'       => ['required', 'in:Yes,No'],
            'deal_room_role'  => ['nullable', 'required_if:deal_room,Yes', 'string', 'in:' . implode(',', self::DEAL_ROOM_ROLES)],
            'attendance_mode' => ['required', 'string', 'in:' . implode(',', self::ATTENDANCE_MODES)],
            'dietary'         => ['nullable', 'string', 'max:1000'],
            'accessibility'   => ['nullable', 'string', 'max:1000'],
            'other_requests'  => ['nullable', 'string', 'max:1000'],
            'consent_updates' => ['boolean'],
            'consent_media'   => ['boolean'],
            'heard_about'     => ['required', 'string', 'in:' . implode(',', self::HEARD_ABOUT)],
            'objective'       => ['required', 'string', 'in:' . implode(',', self::OBJECTIVES)],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'A registration already exists for this email address.',
        ];
    }
}
