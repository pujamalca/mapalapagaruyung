<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class RecruitmentPeriod extends Model
{
    use HasFactory, SoftDeletes, HasSlug, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cohort_id',
        'registration_start',
        'registration_end',
        'selection_start',
        'selection_end',
        'announcement_date',
        'status',
        'is_active',
        'max_applicants',
        'target_accepted',
        'requirements',
        'selection_stages',
        'registration_fee',
        'payment_instructions',
        'form_fields',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'registration_start' => 'datetime',
            'registration_end' => 'datetime',
            'selection_start' => 'datetime',
            'selection_end' => 'datetime',
            'announcement_date' => 'datetime',
            'is_active' => 'boolean',
            'max_applicants' => 'integer',
            'target_accepted' => 'integer',
            'registration_fee' => 'decimal:2',
            'requirements' => 'array',
            'selection_stages' => 'array',
            'form_fields' => 'array',
            'metadata' => 'array',
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
            ->useLogName('recruitment')
            ->logOnlyDirty()
            ->logFillable();
    }

    // Relationships
    public function cohort(): BelongsTo
    {
        return $this->belongsTo(Cohort::class);
    }

    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }

    public function selectionStages(): HasMany
    {
        return $this->hasMany(SelectionStage::class)->orderBy('order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
            ->where('is_active', true)
            ->where('registration_start', '<=', now())
            ->where('registration_end', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('registration_start', '>', now());
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed')
            ->orWhere('registration_end', '<', now());
    }

    // Helper methods
    public function isOpen(): bool
    {
        return $this->is_active
            && $this->status === 'open'
            && $this->registration_start <= now()
            && $this->registration_end >= now();
    }

    public function isRegistrationClosed(): bool
    {
        return $this->registration_end < now();
    }

    public function hasReachedMaxApplicants(): bool
    {
        if (!$this->max_applicants) {
            return false;
        }

        return $this->applicants()->count() >= $this->max_applicants;
    }

    public function canAcceptApplications(): bool
    {
        return $this->isOpen() && !$this->hasReachedMaxApplicants();
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'open' => 'Terbuka',
            'selection' => 'Seleksi',
            'closed' => 'Ditutup',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'open' => 'success',
            'selection' => 'warning',
            'closed' => 'danger',
            default => 'gray',
        };
    }

    public function getTotalApplicantsAttribute(): int
    {
        return $this->applicants()->count();
    }

    public function getVerifiedApplicantsAttribute(): int
    {
        return $this->applicants()->where('status', 'verified')->count();
    }

    public function getAcceptedApplicantsAttribute(): int
    {
        return $this->applicants()->where('status', 'accepted')->count();
    }
}
