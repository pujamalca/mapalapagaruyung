<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SelectionStage extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'recruitment_period_id',
        'name',
        'slug',
        'description',
        'order',
        'scheduled_date',
        'location',
        'instructions',
        'is_scored',
        'max_score',
        'passing_score',
        'criteria',
        'metadata',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'scheduled_date' => 'datetime',
            'is_scored' => 'boolean',
            'max_score' => 'integer',
            'passing_score' => 'integer',
            'criteria' => 'array',
            'metadata' => 'array',
            'is_active' => 'boolean',
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
    public function recruitmentPeriod(): BelongsTo
    {
        return $this->belongsTo(RecruitmentPeriod::class);
    }

    public function applicants(): BelongsToMany
    {
        return $this->belongsToMany(Applicant::class, 'applicant_progress')
            ->withPivot([
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
            ])
            ->withTimestamps();
    }

    public function progress(): HasMany
    {
        return $this->hasMany(ApplicantProgress::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByPeriod($query, int $periodId)
    {
        return $query->where('recruitment_period_id', $periodId);
    }

    // Helper methods
    public function isScored(): bool
    {
        return $this->is_scored;
    }

    public function isPassed(int $score): bool
    {
        if (!$this->is_scored) {
            return true;
        }

        return $score >= $this->passing_score;
    }

    public function getTotalApplicantsAttribute(): int
    {
        return $this->progress()->count();
    }

    public function getPassedApplicantsAttribute(): int
    {
        return $this->progress()->where('status', 'passed')->count();
    }

    public function getFailedApplicantsAttribute(): int
    {
        return $this->progress()->where('status', 'failed')->count();
    }

    public function getAverageScoreAttribute(): ?float
    {
        if (!$this->is_scored) {
            return null;
        }

        return $this->progress()
            ->whereNotNull('score')
            ->avg('score');
    }
}
