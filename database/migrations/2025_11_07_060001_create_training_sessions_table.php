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
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_program_id')->constrained()->cascadeOnDelete();

            $table->string('title'); // e.g., "Dasar-dasar Navigasi"
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('order')->default(0); // Urutan sesi

            // Schedule
            $table->datetime('scheduled_date')->nullable();
            $table->integer('duration_minutes')->default(60); // Durasi dalam menit
            $table->string('location')->nullable();

            // Content
            $table->text('learning_objectives')->nullable(); // Tujuan pembelajaran
            $table->text('content')->nullable(); // Materi
            $table->json('materials')->nullable(); // File materi/presentasi
            $table->json('equipment_needed')->nullable(); // Peralatan

            // Instructor
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('instructor_name')->nullable(); // Jika instruktur eksternal

            // Evaluation
            $table->boolean('has_quiz')->default(false);
            $table->boolean('has_practical')->default(false);
            $table->integer('max_score')->default(100);

            // Status
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['training_program_id', 'order']);
            $table->unique(['training_program_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
