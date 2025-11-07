<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ApplicantProgress extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'applicant_progress';

    protected $fillable = [
        'applicant_id',
        'selection_stage_id',
        'status',
        'score',
        'detailed_scores',
        'notes',
        'feedback',
        'evaluation_data',
        'attended',
        'attended_at',
        'absence_reason',
        'evaluated_by',
        'evaluated_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'detailed_scores' => 'array',
            'evaluation_data' => 'array',
            'attended' => 'boolean',
            'attended_at' => 'datetime',
            'evaluated_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('recruitment')
            ->logOnlyDirty()
            ->logFillable();
    }

    // Relationships
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function selectionStage(): BelongsTo
    {
        return $this->belongsTo(SelectionStage::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeEvaluated($query)
    {
        return $query->whereNotNull('evaluated_at');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'scheduled' => 'Terjadwal',
            'in_progress' => 'Sedang Berlangsung',
            'passed' => 'Lulus',
            'failed' => 'Tidak Lulus',
            'skipped' => 'Dilewati',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'gray',
            'scheduled' => 'info',
            'in_progress' => 'warning',
            'passed' => 'success',
            'failed' => 'danger',
            'skipped' => 'gray',
            default => 'gray',
        };
    }

    public function isPassed(): bool
    {
        return $this->status === 'passed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isEvaluated(): bool
    {
        return $this->evaluated_at !== null;
    }

    public function markAsAttended(): void
    {
        $this->update([
            'attended' => true,
            'attended_at' => now(),
            'status' => 'in_progress',
        ]);
    }

    public function markAsPassed(int $score = null, string $feedback = null): void
    {
        $data = [
            'status' => 'passed',
            'evaluated_at' => now(),
        ];

        if ($score !== null) {
            $data['score'] = $score;
        }

        if ($feedback !== null) {
            $data['feedback'] = $feedback;
        }

        $this->update($data);

        // Update applicant's total score
        $this->applicant->calculateTotalScore();
    }

    public function markAsFailed(string $feedback = null): void
    {
        $data = [
            'status' => 'failed',
            'evaluated_at' => now(),
        ];

        if ($feedback !== null) {
            $data['feedback'] = $feedback;
        }

        $this->update($data);
    }

    public function evaluate(int $evaluatorId, int $score, array $detailedScores = [], string $notes = null, string $feedback = null): void
    {
        $isPassed = $this->selectionStage->isPassed($score);

        $this->update([
            'status' => $isPassed ? 'passed' : 'failed',
            'score' => $score,
            'detailed_scores' => $detailedScores,
            'notes' => $notes,
            'feedback' => $feedback,
            'evaluated_by' => $evaluatorId,
            'evaluated_at' => now(),
        ]);

        // Update applicant's total score
        $this->applicant->calculateTotalScore();

        // Update applicant status if failed
        if (!$isPassed) {
            $this->applicant->update(['status' => 'failed']);
        }
    }
}
