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
        Schema::create('training_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Attendance
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('absent');
            $table->datetime('checked_in_at')->nullable();
            $table->datetime('checked_out_at')->nullable();
            $table->text('notes')->nullable(); // Catatan kehadiran

            // Evaluation for this session
            $table->integer('score')->nullable();
            $table->integer('quiz_score')->nullable();
            $table->integer('practical_score')->nullable();
            $table->text('feedback')->nullable();

            // Recorded by
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index(['training_session_id', 'user_id']);
            $table->unique(['training_session_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_attendance');
    }
};
