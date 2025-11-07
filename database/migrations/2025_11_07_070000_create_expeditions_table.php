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
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Destination & Route
            $table->string('destination'); // e.g., Gunung Kerinci
            $table->string('location'); // e.g., Jambi, Sumatera
            $table->text('route_description')->nullable();
            $table->json('checkpoints')->nullable(); // Array of checkpoint names/locations
            $table->decimal('distance_km', 8, 2)->nullable(); // Total distance
            $table->integer('elevation_gain_m')->nullable(); // Total elevation gain
            $table->string('difficulty_level')->default('moderate'); // easy, moderate, hard, extreme

            // Schedule
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('duration_days')->nullable(); // Calculated duration

            // Participants
            $table->foreignId('leader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('leader_name')->nullable(); // If external
            $table->integer('max_participants')->nullable();
            $table->integer('min_participants')->default(4);

            // Status & Registration
            $table->enum('status', ['planned', 'preparing', 'ongoing', 'completed', 'cancelled'])->default('planned');
            $table->enum('registration_status', ['open', 'closed', 'full'])->default('open');
            $table->dateTime('registration_deadline')->nullable();

            // Costs
            $table->decimal('estimated_cost_per_person', 10, 2)->default(0);
            $table->text('cost_breakdown')->nullable(); // Details of costs

            // Requirements & Equipment
            $table->json('requirements')->nullable(); // Array of requirements
            $table->json('equipment_list')->nullable(); // Array of required equipment
            $table->json('emergency_contacts')->nullable(); // Emergency contact info

            // Categorization
            $table->string('expedition_type')->default('hiking'); // hiking, climbing, caving, rafting, etc.
            $table->foreignId('division_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_official')->default(true); // Official Mapala expedition or personal
            $table->boolean('requires_approval')->default(true);

            // Weather & Conditions
            $table->string('best_season')->nullable(); // e.g., "April - September"
            $table->text('weather_notes')->nullable();

            // Documentation & Reports
            $table->text('trip_report')->nullable(); // Post-expedition report
            $table->json('highlights')->nullable(); // Key highlights/achievements
            $table->json('challenges')->nullable(); // Challenges faced
            $table->json('lessons_learned')->nullable();

            // Metadata
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();

            // Tracking
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('expedition_type');
            $table->index('start_date');
            $table->index(['registration_status', 'registration_deadline']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expeditions');
    }
};
