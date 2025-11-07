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

class Applicant extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'recruitment_period_id',
        'registration_number',
        'full_name',
        'email',
        'phone',
        'birth_date',
        'birth_place',
        'gender',
        'address',
        'nim',
        'major',
        'faculty',
        'enrollment_year',
        'gpa',
        'blood_type',
        'medical_history',
        'emergency_contact',
        'organization_experience',
        'outdoor_experience',
        'skills',
        'motivation',
        'photo_path',
        'ktp_path',
        'ktm_path',
        'form_path',
        'payment_proof_path',
        'status',
        'status_notes',
        'total_score',
        'current_stage',
        'last_stage_update',
        'form_data',
        'metadata',
        'assigned_to',
        'verified_at',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'enrollment_year' => 'integer',
            'gpa' => 'decimal:2',
            'emergency_contact' => 'array',
            'skills' => 'array',
            'form_data' => 'array',
            'metadata' => 'array',
            'total_score' => 'integer',
            'last_stage_update' => 'datetime',
            'verified_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('ktp')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);

        $this->addMediaCollection('ktm')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);

        $this->addMediaCollection('form')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);

        $this->addMediaCollection('payment_proof')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);
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

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function selectionStages(): BelongsToMany
    {
        return $this->belongsToMany(SelectionStage::class, 'applicant_progress')
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
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRegistered($query)
    {
        return $query->where('status', 'registered');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeInSelection($query)
    {
        return $query->where('status', 'in_selection');
    }

    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeByPeriod($query, int $periodId)
    {
        return $query->where('recruitment_period_id', $periodId);
    }

    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'registered' => 'Terdaftar',
            'verified' => 'Terverifikasi',
            'in_selection' => 'Dalam Seleksi',
            'passed' => 'Lulus',
            'failed' => 'Tidak Lulus',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            'withdrawn' => 'Mengundurkan Diri',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'registered' => 'gray',
            'verified' => 'info',
            'in_selection' => 'warning',
            'passed' => 'success',
            'failed' => 'danger',
            'accepted' => 'success',
            'rejected' => 'danger',
            'withdrawn' => 'gray',
            default => 'gray',
        };
    }

    public function getGenderLabelAttribute(): ?string
    {
        return match($this->gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => null,
        };
    }

    public function isVerified(): bool
    {
        return in_array($this->status, ['verified', 'in_selection', 'passed', 'accepted']);
    }

    public function canBeEvaluated(): bool
    {
        return in_array($this->status, ['verified', 'in_selection']);
    }

    public function markAsVerified(): void
    {
        $this->update([
            'status' => 'verified',
            'verified_at' => now(),
        ]);
    }

    public function markAsAccepted(): void
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
    }

    public function getEmergencyContactNameAttribute(): ?string
    {
        return $this->emergency_contact['name'] ?? null;
    }

    public function getEmergencyContactPhoneAttribute(): ?string
    {
        return $this->emergency_contact['phone'] ?? null;
    }

    public function getEmergencyContactRelationshipAttribute(): ?string
    {
        return $this->emergency_contact['relationship'] ?? null;
    }

    public function calculateTotalScore(): void
    {
        $totalScore = $this->progress()
            ->whereNotNull('score')
            ->sum('score');

        $this->update(['total_score' => $totalScore]);
    }

    public function updateCurrentStage(SelectionStage $stage): void
    {
        $this->update([
            'current_stage' => $stage->name,
            'last_stage_update' => now(),
        ]);
    }
}
