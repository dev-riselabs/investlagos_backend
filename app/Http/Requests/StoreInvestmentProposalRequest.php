<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvestmentProposalRequest extends FormRequest
{
    public const SECTORS = [
        'Infrastructure', 'Technology & Innovation', 'Financial Services', 'Energy',
        'Manufacturing', 'Real Estate', 'Healthcare', 'Education', 'Logistics',
        'Creative Economy', 'Agriculture', 'Sports', 'Tourism & Hospitality',
    ];

    public const OPERATING_MARKET = ['Intra African', 'Extra African', 'Both'];

    public const CURRENT_TURNOVER = [
        'USD 50M - 100M', 'USD 100M - 500M', 'USD 500M - 1B', '1B ABOVE',
    ];

    public const FINANCING = [
        'USD 10M - 50M', 'USD 50M - 100M', 'USD 100M - 500M',
        'USD 500M - 1B', 'USD 1B - 2B', '2B ABOVE',
    ];

    public const PROJECT_TIMEFRAME = [
        'Short Term (0-5 years)',
        'Medium Term (5-10 years)',
        'Long Term (more than 10 years)',
    ];

    public const FINANCING_DEAL_TYPES = [
        'Corporate Finance', 'Investment Finance', 'Asset-Based Finance',
        'Project Finance', 'Trade Finance', 'SME Finance',
        'Project Preparation Finance (PPF)', 'Other (Please Specify):',
    ];

    public const NON_FINANCING_DEAL_TYPES = [
        'Trade Contract', 'Market Access', 'Partnership/MoU', 'Investment Contract',
        'Investment Promotion', 'Matchmaking Facilitation', 'Capacity Building',
        'Advisory Services ', 'Trade Facilitation', 'Advocacy / Policy Support',
        'Other (Please Specify):',
    ];

    public const PROJECT_STATUS       = ['Greenfield', 'Brownfield', 'None'];
    public const AVAILABLE_DOCUMENT   = ['Feasibility Study', 'Impact Study', 'Business Plan'];
    public const INVESTMENT_TYPE      = ['Debt', 'Equity', 'Hybrid'];

    public function authorize(): bool
    {
        return true;
    }

    /** Normalize camelCase keys from the React form to snake_case. */
    protected function prepareForValidation(): void
    {
        $map = [
            // Step 1
            'project_title'           => 'projectTitle',
            'project_location'        => 'projectLocation',
            'sector'                  => 'sector',
            'project_partners'        => 'projectPartners',
            'project_owner'           => 'projectOwner',
            'borrower_fullname'       => 'borrowerFullname',
            'project_owner_title'     => 'projectOwnerTitle',
            'project_owner_email'     => 'projectOwnerEmail',
            'project_owner_phone'     => 'projectOwnerPhone',
            'organization'            => 'organization',
            'organisation_summary'    => 'organisationSummary',
            'operating_market'        => 'operatingMarket',
            'project_type'            => 'projectType',
            // Step 2
            'project_description'     => 'projectDescription',
            'investment_estimate_usd' => 'investmentEstimateUsd',
            'amount_invested_usd'     => 'amountInvestedUsd',
            'finance_needs'           => 'financeNeeds',
            'project_expected_outcome'=> 'projectExpectedOutcome',
            'project_status'          => 'projectStatus',
            'partnership_openness'    => 'partnershipOpenness',
            'documentation_available' => 'documentationAvailable',
            'project_time_frame'      => 'projectTimeFrame',
            'finance_deal_type'       => 'financeDealType',
            'non_finance_deal_type'   => 'nonFinanceDealType',
            'additional_information'  => 'additionalInformation',
            'disclaimer_accepted'     => 'disclaimerAccepted',
        ];

        $patch = [];
        foreach ($map as $snake => $camel) {
            if ($this->has($snake) || $this->has($camel)) {
                $patch[$snake] = $this->input($snake, $this->input($camel));
            }
        }
        // Coerce numeric strings to numbers / blank to null for nullable decimals.
        foreach (['investment_estimate_usd', 'amount_invested_usd'] as $k) {
            if (array_key_exists($k, $patch) && ($patch[$k] === '' || $patch[$k] === null)) {
                $patch[$k] = null;
            }
        }
        $this->merge($patch);
    }

    public function rules(): array
    {
        return [
            // Step 1 — required
            'project_title'           => ['required', 'string', 'max:255'],
            'project_location'        => ['required', 'string', 'max:255'],
            'sector'                  => ['required', 'string', 'in:' . implode(',', self::SECTORS)],
            'project_partners'        => ['required', 'string', 'max:500'],
            'project_owner'           => ['required', 'string', 'max:255'],
            'borrower_fullname'       => ['required', 'string', 'max:255'],
            'project_owner_title'     => ['required', 'string', 'max:150'],
            'project_owner_email'     => ['required', 'email:rfc', 'max:255'],
            'project_owner_phone'     => ['required', 'string', 'max:40'],
            'organization'            => ['required', 'string', 'max:255'],
            'organisation_summary'    => ['required', 'string', 'max:4000'],
            'operating_market'        => ['required', 'string', 'in:' . implode(',', self::OPERATING_MARKET)],
            'project_type'            => ['required', 'string', 'in:' . implode(',', self::CURRENT_TURNOVER)],

            // Step 2 — optional
            'project_description'     => ['nullable', 'string', 'max:8000'],
            'investment_estimate_usd' => ['nullable', 'numeric', 'min:0'],
            'amount_invested_usd'     => ['nullable', 'numeric', 'min:0'],
            'finance_needs'           => ['nullable', 'string', 'in:' . implode(',', self::FINANCING)],
            'project_expected_outcome'=> ['nullable', 'string', 'max:4000'],
            'project_status'          => ['nullable', 'string', 'in:' . implode(',', self::PROJECT_STATUS)],
            'partnership_openness'    => ['nullable', 'string', 'in:' . implode(',', self::INVESTMENT_TYPE)],
            'documentation_available' => ['nullable', 'string', 'in:' . implode(',', self::AVAILABLE_DOCUMENT)],
            'project_time_frame'      => ['nullable', 'string', 'in:' . implode(',', self::PROJECT_TIMEFRAME)],
            'finance_deal_type'       => ['nullable', 'string', 'in:' . implode(',', self::FINANCING_DEAL_TYPES)],
            'non_finance_deal_type'   => ['nullable', 'string', 'in:' . implode(',', self::NON_FINANCING_DEAL_TYPES)],
            'additional_information'  => ['nullable', 'string', 'max:4000'],

            'disclaimer_accepted'     => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'disclaimer_accepted.accepted' =>
                'You must acknowledge the disclaimer before submitting your proposal.',
        ];
    }
}
