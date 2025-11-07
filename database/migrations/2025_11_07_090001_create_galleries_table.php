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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Category & Classification
            $table->foreignId('gallery_category_id')->nullable()->constrained()->nullOnDelete();
            $table->json('tags')->nullable();

            // Related Content (polymorphic)
            $table->nullableMorphs('galleryable'); // Can be related to Expedition, Competition, Training, etc.

            // Date & Location
            $table->date('event_date')->nullable();
            $table->string('location')->nullable();

            // Photographer/Uploader
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('photographer_name')->nullable();

            // Settings
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_public')->default(true);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            // Publishing & Stats
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('media_count')->default(0);

            // SEO & Metadata
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('published_at');
            $table->index('is_featured');
            $table->index('is_public');
            $table->index('event_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
