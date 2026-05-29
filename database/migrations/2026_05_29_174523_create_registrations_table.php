<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            // Personal information
            $table->string('title', 20);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('gender', 30)->nullable();
            $table->string('email')->unique();
            $table->string('phone', 30);
            $table->string('nationality', 100);
            $table->string('country', 100);

            // Professional information
            $table->string('organization');
            $table->string('industry', 100);
            $table->string('org_type', 100);

            // Participation
            $table->string('attending_as', 100);
            $table->string('sector', 100);
            $table->enum('deal_room', ['Yes', 'No'])->default('No');
            $table->string('deal_room_role', 100)->nullable();
            $table->string('attendance_mode', 50);

            // Special requirements
            $table->text('dietary')->nullable();
            $table->text('accessibility')->nullable();
            $table->text('other_requests')->nullable();

            // Consent + media
            $table->boolean('consent_updates')->default(false);
            $table->boolean('consent_media')->default(false);
            $table->string('heard_about', 100);
            $table->string('objective', 100);

            $table->timestamps();

            $table->index(['attending_as', 'sector']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
