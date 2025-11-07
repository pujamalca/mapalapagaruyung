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
        Schema::create('expedition_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expedition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Registration
            $table->dateTime('registered_at')->default(now());
            $table->enum('status', [
                'registered',
                'approved',
                'confirmed',
                'participating',
                'completed',
                'cancelled',
                'rejected'
            ])->default('registered');

            // Role in expedition
            $table->string('role')->nullable(); // e.g., Navigator, Medic, Cook, Documentation
            $table->boolean('is_leader')->default(false);

            // Health & Fitness
            $table->text('health_declaration')->nullable();
            $table->boolean('fitness_verified')->default(false);
            $table->text('medical_notes')->nullable();

            // Equipment Check
            $table->boolean('equipment_verified')->default(false);
            $table->dateTime('equipment_checked_at')->nullable();
            $table->text('equipment_notes')->nullable();

            // Performance & Evaluation
            $table->integer('performance_rating')->nullable(); // 1-5
            $table->text('performance_notes')->nullable();
            $table->text('participant_feedback')->nullable(); // Feedback about the expedition

            // Contribution
            $table->json('tasks_assigned')->nullable(); // Tasks assigned during expedition
            $table->json('tasks_completed')->nullable();
            $table->text('contribution_notes')->nullable();

            // Financial
            $table->decimal('payment_amount', 10, 2)->default(0);
            $table->boolean('payment_verified')->default(false);
            $table->dateTime('payment_date')->nullable();

            // Metadata
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Unique constraint
            $table->unique(['expedition_id', 'user_id']);

            // Indexes
            $table->index('status');
            $table->index('registered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedition_participants');
    }
};
