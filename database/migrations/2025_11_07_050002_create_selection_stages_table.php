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
        Schema::create('selection_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_period_id')->constrained()->cascadeOnDelete();

            $table->string('name'); // e.g., "Wawancara", "Praktik Lapangan"
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('order')->default(0); // Sequence order

            // Stage details
            $table->datetime('scheduled_date')->nullable();
            $table->string('location')->nullable();
            $table->text('instructions')->nullable();

            // Scoring
            $table->boolean('is_scored')->default(true);
            $table->integer('max_score')->default(100);
            $table->integer('passing_score')->default(70);

            // Evaluation criteria
            $table->json('criteria')->nullable(); // Array of evaluation criteria
            $table->json('metadata')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Indexes
            $table->index(['recruitment_period_id', 'order']);
            $table->unique(['recruitment_period_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selection_stages');
    }
};
