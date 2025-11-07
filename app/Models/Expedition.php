<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Expedition extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'destination',
        'location',
        'route_description',
        'checkpoints',
        'distance_km',
        'elevation_gain_m',
        'difficulty_level',
        'start_date',
        'end_date',
        'duration_days',
        'leader_id',
        'leader_name',
        'max_participants',
        'min_participants',
        'status',
        'registration_status',
        'registration_deadline',
        'estimated_cost_per_person',
        'cost_breakdown',
        'requirements',
        'equipment_list',
        'emergency_contacts',
        'expedition_type',
        'division_id',
        'is_official',
        'requires_approval',
        'best_season',
        'weather_notes',
        'trip_report',
        'highlights',
        'challenges',
        'lessons_learned',
        'metadata',
        'notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'registration_deadline' => 'datetime',
            'completed_at' => 'datetime',
            'checkpoints' => 'array',
            'requirements' => 'array',
            'equipment_list' => 'array',
            'emergency_contacts' => 'array',
            'highlights' => 'array',
            'challenges' => 'array',
            'lessons_learned' => 'array',
            'metadata' => 'array',
            'distance_km' => 'decimal:2',
            'estimated_cost_per_person' => 'decimal:2',
            'elevation_gain_m' => 'integer',
            'max_participants' => 'integer',
            'min_participants' => 'integer',
            'duration_days' => 'integer',
            'is_official' => 'boolean',
            'requires_approval' => 'boolean',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('expedition')
            ->logOnlyDirty()
            ->logFillable();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('route_maps')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'application/pdf']);

        $this->addMediaCollection('photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    // Relationships
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function user(): BelongsTo
    {
        return $this->leader();
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'expedition_participants')
            ->withPivot([
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
            ])
            ->withTimestamps()
            ->using(ExpeditionParticipant::class);
    }

    public function expeditionParticipants()
    {
        return $this->hasMany(ExpeditionParticipant::class);
    }

    // Scopes
    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }

    public function scopePreparing($query)
    {
        return $query->where('status', 'preparing');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['planned', 'preparing'])
            ->where('start_date', '>', now());
    }

    public function scopeRegistrationOpen($query)
    {
        return $query->where('registration_status', 'open')
            ->where(function ($q) {
                $q->whereNull('registration_deadline')
                    ->orWhere('registration_deadline', '>', now());
            });
    }

    public function scopeOfficial($query)
    {
        return $query->where('is_official', true);
    }

    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'planned' => 'Direncanakan',
            'preparing' => 'Persiapan',
            'ongoing' => 'Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'planned' => 'gray',
            'preparing' => 'info',
            'ongoing' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }

    public function getDifficultyLabelAttribute(): string
    {
        return match($this->difficulty_level) {
            'easy' => 'Mudah',
            'moderate' => 'Sedang',
            'hard' => 'Sulit',
            'extreme' => 'Ekstrim',
            default => 'Sedang',
        };
    }

    public function getDifficultyColorAttribute(): string
    {
        return match($this->difficulty_level) {
            'easy' => 'success',
            'moderate' => 'info',
            'hard' => 'warning',
            'extreme' => 'danger',
            default => 'gray',
        };
    }

    public function getRegistrationStatusLabelAttribute(): string
    {
        return match($this->registration_status) {
            'open' => 'Terbuka',
            'closed' => 'Ditutup',
            'full' => 'Penuh',
            default => 'Ditutup',
        };
    }

    public function calculateDuration(): void
    {
        if ($this->start_date && $this->end_date) {
            $this->duration_days = $this->start_date->diffInDays($this->end_date) + 1;
            $this->save();
        }
    }

    public function getDurationFormatted(): string
    {
        if (!$this->duration_days) {
            return '-';
        }

        $days = $this->duration_days;
        $nights = $days - 1;

        if ($nights > 0) {
            return "{$days}D{$nights}N";
        }

        return "{$days} Hari";
    }

    public function isRegistrationOpen(): bool
    {
        if ($this->registration_status !== 'open') {
            return false;
        }

        if ($this->registration_deadline && $this->registration_deadline->isPast()) {
            return false;
        }

        if ($this->isFull()) {
            return false;
        }

        return true;
    }

    public function isFull(): bool
    {
        if (!$this->max_participants) {
            return false;
        }

        return $this->participants()->wherePivotIn('status', ['approved', 'confirmed', 'participating'])->count() >= $this->max_participants;
    }

    public function getParticipantCount(): int
    {
        return $this->participants()->wherePivotIn('status', ['approved', 'confirmed', 'participating', 'completed'])->count();
    }

    public function canStart(): bool
    {
        $participantCount = $this->getParticipantCount();
        return $participantCount >= $this->min_participants && $this->status === 'preparing';
    }

    public function markAsOngoing(): void
    {
        $this->update([
            'status' => 'ongoing',
            'registration_status' => 'closed',
        ]);

        // Update participant statuses
        $this->expeditionParticipants()
            ->where('status', 'confirmed')
            ->update(['status' => 'participating']);
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Update participant statuses
        $this->expeditionParticipants()
            ->where('status', 'participating')
            ->update(['status' => 'completed']);
    }

    public function getAveragePerformanceRating(): ?float
    {
        return $this->expeditionParticipants()
            ->whereNotNull('performance_rating')
            ->avg('performance_rating');
    }
}
