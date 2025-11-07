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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Type & Classification
            $table->string('event_type')->default('competition'); // competition, workshop, seminar, gathering, festival
            $table->string('competition_type')->default('external'); // internal, external, regional, national, international
            $table->string('sport_category')->nullable(); // climbing, hiking, orienteering, SAR, etc
            $table->string('participation_type')->default('individual'); // individual, team, both

            // Organizer & Location
            $table->string('organizer'); // e.g., FPTI, MAPALA Indonesia
            $table->string('location');
            $table->text('venue_details')->nullable();

            // Schedule
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('duration_days')->nullable();

            // Registration
            $table->dateTime('registration_open')->nullable();
            $table->dateTime('registration_close')->nullable();
            $table->enum('registration_status', ['open', 'closed', 'full'])->default('open');
            $table->integer('max_participants')->nullable();
            $table->integer('min_participants')->nullable();

            // Financial
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->text('fee_details')->nullable();
            $table->boolean('fee_covered_by_mapala')->default(false); // Apakah biaya ditanggung Mapala?

            // Requirements & Categories
            $table->json('requirements')->nullable(); // Array of requirements
            $table->json('categories')->nullable(); // Array of competition categories/classes
            $table->json('prizes')->nullable(); // Array of prizes/awards

            // Contact & Links
            $table->json('contact_persons')->nullable();
            $table->string('website_url')->nullable();
            $table->string('registration_url')->nullable();

            // Status & Management
            $table->enum('status', ['planned', 'registration_open', 'ongoing', 'completed', 'cancelled'])->default('planned');
            $table->foreignId('coordinator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_official_event')->default(false); // Official Mapala event or external
            $table->foreignId('division_id')->nullable()->constrained()->nullOnDelete();

            // Results & Documentation
            $table->text('event_report')->nullable();
            $table->json('achievements_summary')->nullable(); // Overall achievements
            $table->json('highlights')->nullable();
            $table->text('notes')->nullable();

            // Metadata
            $table->json('metadata')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('event_type');
            $table->index('competition_type');
            $table->index('status');
            $table->index('start_date');
            $table->index(['registration_status', 'registration_close']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
