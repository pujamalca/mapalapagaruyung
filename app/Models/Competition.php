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

class Competition extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_type',
        'competition_type',
        'sport_category',
        'participation_type',
        'organizer',
        'location',
        'venue_details',
        'start_date',
        'end_date',
        'duration_days',
        'registration_open',
        'registration_close',
        'registration_status',
        'max_participants',
        'min_participants',
        'registration_fee',
        'fee_details',
        'fee_covered_by_mapala',
        'requirements',
        'categories',
        'prizes',
        'contact_persons',
        'website_url',
        'registration_url',
        'status',
        'coordinator_id',
        'is_official_event',
        'division_id',
        'event_report',
        'achievements_summary',
        'highlights',
        'notes',
        'metadata',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'registration_open' => 'datetime',
            'registration_close' => 'datetime',
            'completed_at' => 'datetime',
            'requirements' => 'array',
            'categories' => 'array',
            'prizes' => 'array',
            'contact_persons' => 'array',
            'achievements_summary' => 'array',
            'highlights' => 'array',
            'metadata' => 'array',
            'registration_fee' => 'decimal:2',
            'max_participants' => 'integer',
            'min_participants' => 'integer',
            'duration_days' => 'integer',
            'fee_covered_by_mapala' => 'boolean',
            'is_official_event' => 'boolean',
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
            ->useLogName('competition')
            ->logOnlyDirty()
            ->logFillable();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('posters')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);

        $this->addMediaCollection('photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);

        $this->addMediaCollection('certificates')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'application/pdf']);

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    // Relationships
    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'competition_participants')
            ->withPivot([
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
            ])
            ->withTimestamps()
            ->using(CompetitionParticipant::class);
    }

    public function competitionParticipants(): HasMany
    {
        return $this->hasMany(CompetitionParticipant::class);
    }

    // Scopes
    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }

    public function scopeRegistrationOpen($query)
    {
        return $query->where('status', 'registration_open');
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
        return $query->whereIn('status', ['planned', 'registration_open'])
            ->where('start_date', '>', now());
    }

    public function scopeOfficial($query)
    {
        return $query->where('is_official_event', true);
    }

    public function scopeExternal($query)
    {
        return $query->where('is_official_event', false);
    }

    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'planned' => 'Direncanakan',
            'registration_open' => 'Pendaftaran Dibuka',
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
            'registration_open' => 'info',
            'ongoing' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }

    public function getEventTypeLabelAttribute(): string
    {
        return match($this->event_type) {
            'competition' => 'Kompetisi',
            'workshop' => 'Workshop',
            'seminar' => 'Seminar',
            'gathering' => 'Gathering',
            'festival' => 'Festival',
            default => ucfirst($this->event_type),
        };
    }

    public function getCompetitionTypeLabelAttribute(): string
    {
        return match($this->competition_type) {
            'internal' => 'Internal',
            'external' => 'Eksternal',
            'regional' => 'Regional',
            'national' => 'Nasional',
            'international' => 'Internasional',
            default => ucfirst($this->competition_type),
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

        return $this->duration_days === 1
            ? '1 Hari'
            : "{$this->duration_days} Hari";
    }

    public function isRegistrationOpen(): bool
    {
        if ($this->registration_status !== 'open') {
            return false;
        }

        $now = now();

        if ($this->registration_open && $now->lessThan($this->registration_open)) {
            return false;
        }

        if ($this->registration_close && $now->greaterThan($this->registration_close)) {
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

        return $this->getParticipantCount() >= $this->max_participants;
    }

    public function getParticipantCount(): int
    {
        return $this->competitionParticipants()
            ->whereIn('status', ['confirmed', 'participating', 'completed'])
            ->count();
    }

    public function getWinnersCount(): int
    {
        return $this->competitionParticipants()
            ->whereNotNull('position')
            ->where('position', '<=', 3)
            ->count();
    }

    public function getMedalsCount(): array
    {
        return [
            'gold' => $this->competitionParticipants()->where('medal_type', 'gold')->count(),
            'silver' => $this->competitionParticipants()->where('medal_type', 'silver')->count(),
            'bronze' => $this->competitionParticipants()->where('medal_type', 'bronze')->count(),
        ];
    }

    public function markAsOngoing(): void
    {
        $this->update([
            'status' => 'ongoing',
            'registration_status' => 'closed',
        ]);

        // Update participant statuses
        $this->competitionParticipants()
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
        $this->competitionParticipants()
            ->where('status', 'participating')
            ->update(['status' => 'completed']);
    }
}
