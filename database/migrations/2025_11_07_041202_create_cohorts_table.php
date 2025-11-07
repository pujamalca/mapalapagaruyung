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
        Schema::create('cohorts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Kader XXIII
            $table->integer('year');
            $table->string('theme')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'alumni'])->default('active');
            $table->integer('member_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('year');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cohorts');
    }
};
