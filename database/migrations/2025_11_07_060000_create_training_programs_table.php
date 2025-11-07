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
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Diklatsar Kader XXIV"
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Program details
            $table->foreignId('cohort_id')->nullable()->constrained()->nullOnDelete();
            $table->string('program_type')->default('basic'); // basic, advanced, specialized
            $table->string('level')->nullable(); // beginner, intermediate, advanced

            // Schedule
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('location')->nullable();
            $table->text('location_details')->nullable();

            // Participants
            $table->integer('max_participants')->nullable();
            $table->integer('min_participants')->default(1);
            $table->enum('registration_status', ['open', 'closed', 'full'])->default('open');

            // Instructors & Organizers
            $table->json('instructors')->nullable(); // Array of user IDs or names
            $table->foreignId('coordinator_id')->nullable()->constrained('users')->nullOnDelete();

            // Requirements & Materials
            $table->json('requirements')->nullable(); // Prerequisites
            $table->json('learning_objectives')->nullable(); // Tujuan pembelajaran
            $table->json('materials_needed')->nullable(); // Peralatan yang dibutuhkan

            // Status
            $table->enum('status', ['draft', 'scheduled', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->boolean('is_mandatory')->default(false); // Wajib untuk calon anggota

            // Evaluation
            $table->boolean('has_evaluation')->default(true);
            $table->integer('passing_score')->default(70);
            $table->json('evaluation_criteria')->nullable();

            // Additional info
            $table->decimal('training_fee', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('registration_status');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_programs');
    }
};
