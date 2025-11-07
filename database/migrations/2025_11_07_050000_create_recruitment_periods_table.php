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
        Schema::create('recruitment_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Open Recruitment Kader XXIV 2025/2026"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('cohort_id')->nullable()->constrained()->nullOnDelete(); // Target cohort

            // Period dates
            $table->datetime('registration_start');
            $table->datetime('registration_end');
            $table->datetime('selection_start')->nullable();
            $table->datetime('selection_end')->nullable();
            $table->datetime('announcement_date')->nullable();

            // Status
            $table->enum('status', ['draft', 'open', 'selection', 'closed'])->default('draft');
            $table->boolean('is_active')->default(false);

            // Limits
            $table->integer('max_applicants')->nullable(); // Maximum number of applicants
            $table->integer('target_accepted')->nullable(); // Target number to accept

            // Requirements and information
            $table->json('requirements')->nullable(); // List of requirements
            $table->json('selection_stages')->nullable(); // Ordered list of stage names
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->text('payment_instructions')->nullable();

            // Settings
            $table->json('form_fields')->nullable(); // Custom form configuration
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('is_active');
            $table->index(['registration_start', 'registration_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitment_periods');
    }
};
