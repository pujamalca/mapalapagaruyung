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
        Schema::create('applicant_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('selection_stage_id')->constrained()->cascadeOnDelete();

            // Progress status
            $table->enum('status', [
                'pending',      // Not yet reached this stage
                'scheduled',    // Scheduled for this stage
                'in_progress',  // Currently being evaluated
                'passed',       // Passed this stage
                'failed',       // Failed this stage
                'skipped'       // Skipped this stage
            ])->default('pending');

            // Scoring
            $table->integer('score')->nullable();
            $table->json('detailed_scores')->nullable(); // Scores per criterion

            // Evaluation
            $table->text('notes')->nullable();
            $table->text('feedback')->nullable(); // Feedback for applicant
            $table->json('evaluation_data')->nullable(); // Additional evaluation data

            // Attendance
            $table->boolean('attended')->default(false);
            $table->datetime('attended_at')->nullable();
            $table->text('absence_reason')->nullable();

            // Evaluator
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('evaluated_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['applicant_id', 'selection_stage_id']);
            $table->index('status');
            $table->unique(['applicant_id', 'selection_stage_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_progress');
    }
};
