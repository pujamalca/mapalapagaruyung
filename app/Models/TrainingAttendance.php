<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TrainingAttendance extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'training_session_id',
        'user_id',
        'status',
        'checked_in_at',
        'checked_out_at',
        'notes',
        'score',
        'quiz_score',
        'practical_score',
        'feedback',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
            'score' => 'integer',
            'quiz_score' => 'integer',
            'practical_score' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('training')
            ->logOnlyDirty()
            ->logFillable();
    }

    // Relationships
    public function trainingSession(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'present' => 'Hadir',
            'absent' => 'Tidak Hadir',
            'late' => 'Terlambat',
            'excused' => 'Izin',
            default => 'Tidak Diketahui',
        ];
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'present' => 'success',
            'absent' => 'danger',
            'late' => 'warning',
            'excused' => 'info',
            default => 'gray',
        ];
    }

    public function getDurationAttribute(): ?string
    {
        if (!$this->checked_in_at || !$this->checked_out_at) {
            return null;
        }

        $diff = $this->checked_in_at->diff($this->checked_out_at);
        $hours = $diff->h;
        $minutes = $diff->i;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} jam {$minutes} menit";
        } elseif ($hours > 0) {
            return "{$hours} jam";
        } else {
            return "{$minutes} menit";
        }
    }

    public function markAsPresent(): void
    {
        $this->update([
            'status' => 'present',
            'checked_in_at' => now(),
        ]);
    }

    public function checkOut(): void
    {
        $this->update([
            'checked_out_at' => now(),
        ]);
    }

    public function recordScore(int $score, ?string $feedback = null): void
    {
        $this->update([
            'score' => $score,
            'feedback' => $feedback,
            'recorded_by' => auth()->id(),
        ]);
    }
}
