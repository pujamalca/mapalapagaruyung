<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class TrainingProgram extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasSlug, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cohort_id',
        'program_type',
        'level',
        'start_date',
        'end_date',
        'location',
        'location_details',
        'max_participants',
        'min_participants',
        'registration_status',
        'instructors',
        'coordinator_id',
        'requirements',
        'learning_objectives',
        'materials_needed',
        'status',
        'is_mandatory',
        'has_evaluation',
        'passing_score',
        'evaluation_criteria',
        'training_fee',
        'notes',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'instructors' => 'array',
            'requirements' => 'array',
            'learning_objectives' => 'array',
            'materials_needed' => 'array',
            'evaluation_criteria' => 'array',
            'metadata' => 'array',
            'is_mandatory' => 'boolean',
            'has_evaluation' => 'boolean',
            'max_participants' => 'integer',
            'min_participants' => 'integer',
            'passing_score' => 'integer',
            'training_fee' => 'decimal:2',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('training')
            ->logOnlyDirty()
            ->logFillable();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('materials')
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation']);

        $this->addMediaCollection('photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('certificates')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'image/*']);
    }

    // Relationships
    public function cohort(): BelongsTo
    {
        return $this->belongsTo(Cohort::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(TrainingSession::class)->orderBy('order');
    }

    public function trainingSessions(): HasMany
    {
        return $this->sessions();
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'training_participants')
            ->withPivot([
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
            ])
            ->withTimestamps();
    }

    public function trainingParticipants(): HasMany
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['scheduled', 'ongoing']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
            ->where('start_date', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeOpenRegistration($query)
    {
        return $query->where('registration_status', 'open');
    }

    // Helper methods
    public function isRegistrationOpen(): bool
    {
        return $this->registration_status === 'open'
            && $this->status !== 'cancelled'
            && !$this->isFull();
    }

    public function isFull(): bool
    {
        if (!$this->max_participants) {
            return false;
        }

        return $this->participants()->count() >= $this->max_participants;
    }

    public function hasMinimumParticipants(): bool
    {
        return $this->participants()->count() >= $this->min_participants;
    }

    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'scheduled' => 'Terjadwal',
            'ongoing' => 'Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'scheduled' => 'info',
            'ongoing' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }

    public function getTotalParticipantsAttribute(): int
    {
        return $this->participants()->count();
    }

    public function getCompletedParticipantsAttribute(): int
    {
        return $this->trainingParticipants()->where('status', 'completed')->count();
    }

    public function getPassedParticipantsAttribute(): int
    {
        return $this->trainingParticipants()->where('status', 'passed')->count();
    }

    public function getAverageAttendanceRateAttribute(): ?float
    {
        $participants = $this->trainingParticipants;

        if ($participants->isEmpty()) {
            return null;
        }

        $totalRate = $participants->sum(function ($participant) {
            if ($participant->total_sessions == 0) {
                return 0;
            }
            return ($participant->attendance_count / $participant->total_sessions) * 100;
        });

        return round($totalRate / $participants->count(), 2);
    }
}
