<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EquipmentBorrowing extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'borrowing_code',
        'equipment_id',
        'user_id',
        'borrow_date',
        'due_date',
        'return_date',
        'quantity_borrowed',
        'status',
        'purpose',
        'purpose_details',
        'approved_by',
        'approved_at',
        'condition_borrowed',
        'condition_returned',
        'condition_notes_borrowed',
        'condition_notes_returned',
        'is_late',
        'days_late',
        'penalty_amount',
        'penalty_paid',
        'is_damaged',
        'damage_description',
        'damage_cost',
        'damage_cost_paid',
        'borrower_notes',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
            'approved_at' => 'datetime',
            'quantity_borrowed' => 'integer',
            'is_late' => 'boolean',
            'days_late' => 'integer',
            'penalty_amount' => 'decimal:2',
            'penalty_paid' => 'boolean',
            'is_damaged' => 'boolean',
            'damage_cost' => 'decimal:2',
            'damage_cost_paid' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('equipment')
            ->logOnlyDirty()
            ->logFillable();
    }

    // Relationships
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'active' => 'Sedang Dipinjam',
            'returned' => 'Sudah Dikembalikan',
            'overdue' => 'Terlambat',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'gray',
            'approved' => 'info',
            'active' => 'warning',
            'returned' => 'success',
            'overdue' => 'danger',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function isOverdue(): bool
    {
        if ($this->status === 'returned') {
            return false;
        }

        return $this->due_date->isPast();
    }

    public function getDaysLate(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $compareDate = $this->return_date ?? now();
        return $this->due_date->diffInDays($compareDate);
    }

    public function calculatePenalty(float $penaltyPerDay = 5000): float
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return $this->getDaysLate() * $penaltyPerDay;
    }

    public function approve(int $approverId): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
        ]);

        // Update equipment status
        $this->equipment->markAsBorrowed($this->user_id, $this->due_date);
    }

    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    public function returnEquipment(array $data = []): void
    {
        $isLate = $this->isOverdue();
        $daysLate = $isLate ? $this->getDaysLate() : 0;
        $penalty = $isLate ? $this->calculatePenalty() : 0;

        $this->update([
            'status' => 'returned',
            'return_date' => now(),
            'condition_returned' => $data['condition_returned'] ?? null,
            'condition_notes_returned' => $data['condition_notes_returned'] ?? null,
            'is_late' => $isLate,
            'days_late' => $daysLate,
            'penalty_amount' => $penalty,
            'is_damaged' => $data['is_damaged'] ?? false,
            'damage_description' => $data['damage_description'] ?? null,
            'damage_cost' => $data['damage_cost'] ?? 0,
        ]);

        // Update equipment status
        $this->equipment->markAsReturned();

        // If damaged, update equipment condition
        if ($data['is_damaged'] ?? false) {
            $this->equipment->update([
                'condition' => $data['condition_returned'] ?? 'damaged',
                'condition_notes' => $data['damage_description'] ?? null,
            ]);
        }
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);

        // If was active, return equipment
        if ($this->status === 'active') {
            $this->equipment->markAsReturned();
        }
    }

    public static function generateBorrowingCode(): string
    {
        $year = now()->year;
        $lastBorrowing = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastBorrowing ? ((int) substr($lastBorrowing->borrowing_code, -3)) + 1 : 1;

        return 'BRW-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
