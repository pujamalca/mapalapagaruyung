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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // Equipment code: e.g., TND-001, CAR-025
            $table->foreignId('equipment_category_id')->constrained()->cascadeOnDelete();

            // Details
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();

            // Condition & Status
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor', 'damaged'])->default('good');
            $table->enum('status', ['available', 'borrowed', 'maintenance', 'retired'])->default('available');
            $table->text('condition_notes')->nullable();

            // Storage
            $table->string('storage_location')->nullable();
            $table->string('unit', 50)->default('unit');
            $table->integer('quantity')->default(1);
            $table->integer('quantity_available')->default(0);

            // Maintenance
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->text('maintenance_notes')->nullable();
            $table->integer('maintenance_interval_days')->nullable();

            // Borrowing info
            $table->foreignId('current_borrower_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('borrowed_until')->nullable();

            // Metadata
            $table->json('specifications')->nullable();
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('code');
            $table->index('status');
            $table->index('condition');
            $table->index('current_borrower_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
