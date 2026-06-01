<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentProposal extends Model
{
    protected $fillable = [
        // Step 1
        'project_title',
        'project_location',
        'sector',
        'project_partners',
        'project_owner',
        'borrower_fullname',
        'project_owner_title',
        'project_owner_email',
        'project_owner_phone',
        'organization',
        'organisation_summary',
        'operating_market',
        'project_type',
        // Step 2
        'project_description',
        'investment_estimate_usd',
        'amount_invested_usd',
        'finance_needs',
        'project_expected_outcome',
        'project_status',
        'partnership_openness',
        'documentation_available',
        'project_time_frame',
        'finance_deal_type',
        'non_finance_deal_type',
        'additional_information',
        // Workflow
        'disclaimer_accepted',
        'status',
        'admin_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'investment_estimate_usd' => 'decimal:2',
        'amount_invested_usd'     => 'decimal:2',
        'disclaimer_accepted'     => 'boolean',
        'reviewed_at'             => 'datetime',
    ];
}
