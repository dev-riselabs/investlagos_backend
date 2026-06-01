<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investment_proposals', function (Blueprint $table) {
            $table->id();

            // Step 1 — Project & promoter information
            $table->string('project_title');
            $table->string('project_location');
            $table->string('sector', 100);
            $table->string('project_partners');
            $table->string('project_owner');
            $table->string('borrower_fullname');
            $table->string('project_owner_title');
            $table->string('project_owner_email');
            $table->string('project_owner_phone', 40);
            $table->string('organization');
            $table->text('organisation_summary');
            $table->string('operating_market', 60);
            $table->string('project_type', 60); // Current turnover bucket

            // Step 2 — Project & deal details (all optional)
            $table->longText('project_description')->nullable();
            $table->decimal('investment_estimate_usd', 18, 2)->nullable();
            $table->decimal('amount_invested_usd', 18, 2)->nullable();
            $table->string('finance_needs', 60)->nullable();
            $table->text('project_expected_outcome')->nullable();
            $table->string('project_status', 60)->nullable();
            $table->string('partnership_openness', 30)->nullable();   // Type of Investment Required
            $table->string('documentation_available', 60)->nullable();
            $table->string('project_time_frame', 80)->nullable();
            $table->string('finance_deal_type', 80)->nullable();
            $table->string('non_finance_deal_type', 80)->nullable();
            $table->text('additional_information')->nullable();

            // Consent + review workflow
            $table->boolean('disclaimer_accepted')->default(false);
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])
                ->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['sector', 'status']);
            $table->index('project_owner_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_proposals');
    }
};
