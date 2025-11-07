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
        Schema::create('equipment_borrowings', function (Blueprint $table) {
            $table->id();
            $table->string('borrowing_code')->unique(); // e.g., BRW-2025-001
            $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Borrowing Details
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->integer('quantity_borrowed')->default(1);

            // Status
            $table->enum('status', ['pending', 'approved', 'active', 'returned', 'overdue', 'cancelled'])->default('pending');

            // Purpose
            $table->string('purpose')->nullable(); // expedition, training, personal, etc
            $table->text('purpose_details')->nullable();

            // Approvals
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();

            // Condition tracking
            $table->enum('condition_borrowed', ['excellent', 'good', 'fair', 'poor'])->nullable();
            $table->enum('condition_returned', ['excellent', 'good', 'fair', 'poor', 'damaged'])->nullable();
            $table->text('condition_notes_borrowed')->nullable();
            $table->text('condition_notes_returned')->nullable();

            // Penalties
            $table->boolean('is_late')->default(false);
            $table->integer('days_late')->default(0);
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->boolean('penalty_paid')->default(false);

            // Damage
            $table->boolean('is_damaged')->default(false);
            $table->text('damage_description')->nullable();
            $table->decimal('damage_cost', 10, 2)->default(0);
            $table->boolean('damage_cost_paid')->default(false);

            // Notes
            $table->text('borrower_notes')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('borrowing_code');
            $table->index('status');
            $table->index(['borrow_date', 'due_date']);
            $table->index('is_late');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_borrowings');
    }
};
