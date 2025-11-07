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
        Schema::create('competition_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Registration
            $table->dateTime('registered_at')->default(now());
            $table->enum('status', [
                'registered',
                'confirmed',
                'participating',
                'completed',
                'withdrawn',
                'disqualified'
            ])->default('registered');

            // Participation Details
            $table->string('category')->nullable(); // Which category/class they compete in
            $table->string('team_name')->nullable(); // If team competition
            $table->boolean('is_team_leader')->default(false);
            $table->string('bib_number')->nullable(); // Race number
            $table->json('team_members')->nullable(); // Other team members (external or internal)

            // Financial
            $table->decimal('registration_fee_paid', 10, 2)->default(0);
            $table->boolean('fee_verified')->default(false);
            $table->dateTime('payment_date')->nullable();

            // Performance & Results
            $table->string('rank')->nullable(); // e.g., "1st", "2nd", "Juara 1"
            $table->integer('position')->nullable(); // Numeric position
            $table->decimal('score', 10, 2)->nullable(); // Score if applicable
            $table->string('time_record')->nullable(); // Time for racing events (e.g., "02:45:30")
            $table->json('achievements')->nullable(); // Array of achievements/awards

            // Awards & Recognition
            $table->string('medal_type')->nullable(); // gold, silver, bronze
            $table->string('certificate_number')->nullable();
            $table->boolean('certificate_issued')->default(false);
            $table->dateTime('certificate_issued_at')->nullable();
            $table->json('awards')->nullable(); // Other awards/prizes received

            // Equipment & Preparation
            $table->text('equipment_list')->nullable();
            $table->boolean('equipment_verified')->default(false);

            // Feedback & Notes
            $table->text('participant_notes')->nullable(); // Notes from participant
            $table->text('coordinator_notes')->nullable(); // Notes from coordinator/coach
            $table->integer('performance_rating')->nullable(); // 1-5 rating
            $table->text('experience_feedback')->nullable();

            // Metadata
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Unique constraint
            $table->unique(['competition_id', 'user_id']);

            // Indexes
            $table->index('status');
            $table->index('category');
            $table->index('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_participants');
    }
};
