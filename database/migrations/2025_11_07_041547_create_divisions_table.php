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
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->default('#3B82F6'); // Default blue color
            $table->foreignId('head_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('work_program')->nullable(); // Program kerja divisi
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
        });

        // Pivot table for many-to-many relationship between divisions and users
        Schema::create('division_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('joined_at')->nullable();
            $table->string('role')->nullable(); // Ketua divisi, Anggota, dll
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Composite unique constraint
            $table->unique(['division_id', 'user_id']);

            // Indexes
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division_user');
        Schema::dropIfExists('divisions');
    }
};
