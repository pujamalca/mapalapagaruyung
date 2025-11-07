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
        Schema::create('training_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Registration
            $table->datetime('registered_at')->default(now());
            $table->enum('status', [
                'registered',  // Terdaftar
                'confirmed',   // Dikonfirmasi
                'attending',   // Sedang mengikuti
                'completed',   // Selesai
                'passed',      // Lulus
                'failed',      // Tidak lulus
                'dropped',     // Mengundurkan diri
                'absent'       // Tidak hadir
            ])->default('registered');

            // Scores
            $table->integer('total_score')->default(0);
            $table->decimal('average_score', 5, 2)->nullable();
            $table->integer('attendance_count')->default(0);
            $table->integer('total_sessions')->default(0);

            // Evaluation
            $table->text('evaluation_notes')->nullable(); // Catatan BKP
            $table->text('participant_feedback')->nullable(); // Feedback peserta
            $table->integer('participant_rating')->nullable(); // Rating 1-5

            // Completion
            $table->datetime('completed_at')->nullable();
            $table->boolean('certificate_issued')->default(false);
            $table->datetime('certificate_issued_at')->nullable();

            // Additional
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index(['training_program_id', 'status']);
            $table->unique(['training_program_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_participants');
    }
};
