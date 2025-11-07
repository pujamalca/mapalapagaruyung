<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ExpeditionParticipant extends Pivot
{
    use LogsActivity;

    protected $table = 'expedition_participants';

    public $incrementing = true;

    protected $fillable = [
        'expedition_id',
        'user_id',
        'registered_at',
        'status',
        'role',
        'is_leader',
        'health_declaration',
        'fitness_verified',
        'medical_notes',
        'equipment_verified',
        'equipment_checked_at',
        'equipment_notes',
        'performance_rating',
        'performance_notes',
        'participant_feedback',
        'tasks_assigned',
        'tasks_completed',
        'contribution_notes',
        'payment_amount',
        'payment_verified',
        'payment_date',
        'metadata',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'equipment_checked_at' => 'datetime',
            'payment_date' => 'datetime',
            'fitness_verified' => 'boolean',
            'equipment_verified' => 'boolean',
            'is_leader' => 'boolean',
            'payment_verified' => 'boolean',
            'performance_rating' => 'integer',
            'payment_amount' => 'decimal:2',
            'tasks_assigned' => 'array',
            'tasks_completed' => 'array',
            'metadata' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('expedition')
            ->logOnlyDirty()
            ->logFillable();
    }

    // Relationships
    public function expedition(): BelongsTo
    {
        return $this->belongsTo(Expedition::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeParticipating($query)
    {
        return $query->where('status', 'participating');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'registered' => 'Terdaftar',
            'approved' => 'Disetujui',
            'confirmed' => 'Dikonfirmasi',
            'participating' => 'Mengikuti',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'rejected' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'registered' => 'gray',
            'approved' => 'info',
            'confirmed' => 'primary',
            'participating' => 'warning',
            'completed' => 'success',
            'cancelled' => 'gray',
            'rejected' => 'danger',
            default => 'gray',
        };
    }

    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    public function confirm(): void
    {
        $this->update(['status' => 'confirmed']);
    }

    public function reject(string $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'notes' => $reason ?? $this->notes,
        ]);
    }

    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $reason ?? $this->notes,
        ]);
    }

    public function verifyFitness(): void
    {
        $this->update(['fitness_verified' => true]);
    }

    public function verifyEquipment(): void
    {
        $this->update([
            'equipment_verified' => true,
            'equipment_checked_at' => now(),
        ]);
    }

    public function verifyPayment(): void
    {
        $this->update([
            'payment_verified' => true,
            'payment_date' => now(),
        ]);
    }

    public function ratePerformance(int $rating, ?string $notes = null): void
    {
        $this->update([
            'performance_rating' => $rating,
            'performance_notes' => $notes,
        ]);
    }

    public function isReadyToParticipate(): bool
    {
        return $this->status === 'confirmed'
            && $this->fitness_verified
            && $this->equipment_verified
            && $this->payment_verified;
    }
}
