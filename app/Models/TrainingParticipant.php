<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TrainingParticipant extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'training_program_id',
        'user_id',
        'registered_at',
        'status',
        'total_score',
        'average_score',
        'attendance_count',
        'total_sessions',
        'evaluation_notes',
        'participant_feedback',
        'participant_rating',
        'completed_at',
        'certificate_issued',
        'certificate_issued_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'completed_at' => 'datetime',
            'certificate_issued_at' => 'datetime',
            'certificate_issued' => 'boolean',
            'total_score' => 'integer',
            'average_score' => 'decimal:2',
            'attendance_count' => 'integer',
            'total_sessions' => 'integer',
            'participant_rating' => 'integer',
            'metadata' => 'array',
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
    public function trainingProgram(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['registered', 'confirmed', 'attending']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'registered' => 'Terdaftar',
            'confirmed' => 'Dikonfirmasi',
            'attending' => 'Mengikuti',
            'completed' => 'Selesai',
            'passed' => 'Lulus',
            'failed' => 'Tidak Lulus',
            'dropped' => 'Mengundurkan Diri',
            'absent' => 'Tidak Hadir',
            default => 'Tidak Diketahui',
        ];
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'registered' => 'gray',
            'confirmed' => 'info',
            'attending' => 'warning',
            'completed' => 'primary',
            'passed' => 'success',
            'failed' => 'danger',
            'dropped' => 'gray',
            'absent' => 'danger',
            default => 'gray',
        ];
    }

    public function getAttendanceRateAttribute(): float
    {
        if ($this->total_sessions == 0) {
            return 0;
        }

        return round(($this->attendance_count / $this->total_sessions) * 100, 2);
    }

    public function isPassed(): bool
    {
        if (!$this->trainingProgram->has_evaluation) {
            return true;
        }

        return $this->average_score >= $this->trainingProgram->passing_score;
    }

    public function markAsCompleted(): void
    {
        $isPassed = $this->isPassed();

        $this->update([
            'status' => $isPassed ? 'passed' : 'failed',
            'completed_at' => now(),
        ]);
    }

    public function issueCertificate(): void
    {
        if ($this->status !== 'passed') {
            return;
        }

        $this->update([
            'certificate_issued' => true,
            'certificate_issued_at' => now(),
        ]);
    }

    public function calculateScores(): void
    {
        $attendanceRecords = TrainingAttendance::where('user_id', $this->user_id)
            ->whereHas('trainingSession', function ($query) {
                $query->where('training_program_id', $this->training_program_id);
            })
            ->get();

        $totalScore = $attendanceRecords->whereNotNull('score')->sum('score');
        $scoredSessions = $attendanceRecords->whereNotNull('score')->count();
        $averageScore = $scoredSessions > 0 ? round($totalScore / $scoredSessions, 2) : null;

        $this->update([
            'total_score' => $totalScore,
            'average_score' => $averageScore,
            'attendance_count' => $attendanceRecords->where('status', 'present')->count(),
            'total_sessions' => $attendanceRecords->count(),
        ]);
    }
}
