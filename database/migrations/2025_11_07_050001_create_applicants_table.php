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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_period_id')->constrained()->cascadeOnDelete();

            // Registration number (auto-generated)
            $table->string('registration_number')->unique();

            // Personal Information
            $table->string('full_name');
            $table->string('email')->index();
            $table->string('phone');
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable(); // Laki-laki, Perempuan
            $table->text('address')->nullable();

            // Academic Information
            $table->string('nim');
            $table->string('major');
            $table->string('faculty');
            $table->integer('enrollment_year')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();

            // Health Information
            $table->string('blood_type')->nullable();
            $table->text('medical_history')->nullable();
            $table->json('emergency_contact')->nullable(); // {name, relationship, phone}

            // Experience and Skills
            $table->text('organization_experience')->nullable();
            $table->text('outdoor_experience')->nullable();
            $table->json('skills')->nullable(); // Array of skills
            $table->text('motivation')->nullable(); // Why join Mapala

            // Documents and Files (paths to uploaded files)
            $table->string('photo_path')->nullable();
            $table->string('ktp_path')->nullable(); // ID card
            $table->string('ktm_path')->nullable(); // Student card
            $table->string('form_path')->nullable(); // Registration form
            $table->string('payment_proof_path')->nullable();

            // Application Status
            $table->enum('status', [
                'registered', // Just registered
                'verified',   // Documents verified
                'in_selection', // Currently in selection process
                'passed',     // Passed all stages
                'failed',     // Failed selection
                'accepted',   // Accepted as member
                'rejected',   // Rejected
                'withdrawn'   // Withdrew application
            ])->default('registered');

            $table->text('status_notes')->nullable(); // Admin notes about status
            $table->integer('total_score')->default(0); // Accumulated score from all stages

            // Selection Progress
            $table->string('current_stage')->nullable(); // Current stage in selection
            $table->datetime('last_stage_update')->nullable();

            // Additional data
            $table->json('form_data')->nullable(); // Custom form responses
            $table->json('metadata')->nullable();

            // Tracking
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // BKP member assigned
            $table->datetime('verified_at')->nullable();
            $table->datetime('accepted_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('registration_number');
            $table->index('status');
            $table->index('current_stage');
            $table->index(['recruitment_period_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
