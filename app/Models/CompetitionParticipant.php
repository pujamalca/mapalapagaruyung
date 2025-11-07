<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CompetitionParticipant extends Pivot
{
    use LogsActivity;

    protected $table = 'competition_participants';

    public $incrementing = true;

    protected $fillable = [
        'competition_id',
        'user_id',
        'registered_at',
        'status',
        'category',
        'team_name',
        'is_team_leader',
        'bib_number',
        'team_members',
        'registration_fee_paid',
        'fee_verified',
        'payment_date',
        'rank',
        'position',
        'score',
        'time_record',
        'achievements',
        'medal_type',
        'certificate_number',
        'certificate_issued',
        'certificate_issued_at',
        'awards',
        'equipment_list',
        'equipment_verified',
        'participant_notes',
        'coordinator_notes',
        'performance_rating',
        'experience_feedback',
        'metadata',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'payment_date' => 'datetime',
            'certificate_issued_at' => 'datetime',
            'fee_verified' => 'boolean',
            'is_team_leader' => 'boolean',
            'certificate_issued' => 'boolean',
            'equipment_verified' => 'boolean',
            'registration_fee_paid' => 'decimal:2',
            'score' => 'decimal:2',
            'position' => 'integer',
            'performance_rating' => 'integer',
            'team_members' => 'array',
            'achievements' => 'array',
            'awards' => 'array',
            'metadata' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('competition')
            ->logOnlyDirty()
            ->logFillable();
    }

    // Relationships
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
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

    public function scopeWinners($query)
    {
        return $query->whereNotNull('position')
            ->where('position', '<=', 3)
            ->orderBy('position');
    }

    public function scopeMedalists($query)
    {
        return $query->whereNotNull('medal_type');
    }

    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'registered' => 'Terdaftar',
            'confirmed' => 'Dikonfirmasi',
            'participating' => 'Mengikuti',
            'completed' => 'Selesai',
            'withdrawn' => 'Mengundurkan Diri',
            'disqualified' => 'Diskualifikasi',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'registered' => 'gray',
            'confirmed' => 'info',
            'participating' => 'warning',
            'completed' => 'success',
            'withdrawn' => 'gray',
            'disqualified' => 'danger',
            default => 'gray',
        };
    }

    public function getMedalColorAttribute(): ?string
    {
        return match($this->medal_type) {
            'gold' => 'warning',
            'silver' => 'gray',
            'bronze' => 'orange',
            default => null,
        };
    }

    public function getMedalLabelAttribute(): ?string
    {
        return match($this->medal_type) {
            'gold' => 'Emas 🥇',
            'silver' => 'Perak 🥈',
            'bronze' => 'Perunggu 🥉',
            default => null,
        };
    }

    public function confirm(): void
    {
        $this->update(['status' => 'confirmed']);
    }

    public function withdraw(string $reason = null): void
    {
        $this->update([
            'status' => 'withdrawn',
            'notes' => $reason ?? $this->notes,
        ]);
    }

    public function disqualify(string $reason = null): void
    {
        $this->update([
            'status' => 'disqualified',
            'notes' => $reason ?? $this->notes,
        ]);
    }

    public function verifyFee(): void
    {
        $this->update([
            'fee_verified' => true,
            'payment_date' => now(),
        ]);
    }

    public function verifyEquipment(): void
    {
        $this->update(['equipment_verified' => true]);
    }

    public function recordResult(array $data): void
    {
        $this->update([
            'rank' => $data['rank'] ?? null,
            'position' => $data['position'] ?? null,
            'score' => $data['score'] ?? null,
            'time_record' => $data['time_record'] ?? null,
            'medal_type' => $data['medal_type'] ?? null,
        ]);
    }

    public function issueCertificate(?string $certificateNumber = null): void
    {
        $this->update([
            'certificate_issued' => true,
            'certificate_issued_at' => now(),
            'certificate_number' => $certificateNumber ?? $this->certificate_number,
        ]);
    }

    public function ratePerformance(int $rating, ?string $notes = null): void
    {
        $this->update([
            'performance_rating' => $rating,
            'coordinator_notes' => $notes,
        ]);
    }

    public function isWinner(): bool
    {
        return $this->position !== null && $this->position <= 3;
    }

    public function hasMedal(): bool
    {
        return $this->medal_type !== null;
    }
}
