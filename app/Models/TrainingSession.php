<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TrainingSession extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'training_program_id',
        'title',
        'slug',
        'description',
        'order',
        'scheduled_date',
        'duration_minutes',
        'location',
        'learning_objectives',
        'content',
        'materials',
        'equipment_needed',
        'instructor_id',
        'instructor_name',
        'has_quiz',
        'has_practical',
        'max_score',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'datetime',
            'materials' => 'array',
            'equipment_needed' => 'array',
            'has_quiz' => 'boolean',
            'has_practical' => 'boolean',
            'order' => 'integer',
            'duration_minutes' => 'integer',
            'max_score' => 'integer',
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

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(TrainingAttendance::class);
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'Terjadwal',
            'ongoing' => 'Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    public function getDurationFormatted(): string
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} jam {$minutes} menit";
        } elseif ($hours > 0) {
            return "{$hours} jam";
        } else {
            return "{$minutes} menit";
        }
    }

    public function getAttendanceRate(): float
    {
        $total = $this->attendance()->count();
        if ($total === 0) {
            return 0;
        }

        $present = $this->attendance()->where('status', 'present')->count();
        return round(($present / $total) * 100, 2);
    }

    public function getAverageScore(): ?float
    {
        return $this->attendance()
            ->whereNotNull('score')
            ->avg('score');
    }
}
