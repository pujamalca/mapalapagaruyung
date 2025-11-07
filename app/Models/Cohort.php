<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Cohort extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'code',
        'name',
        'year',
        'theme',
        'description',
        'status',
        'member_count',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'member_count' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'year', 'theme', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Automatically generate a code if it is not provided.
     */
    protected static function booted(): void
    {
        static::creating(function (Cohort $cohort): void {
            if (filled($cohort->code)) {
                return;
            }

            $prefix = Str::of($cohort->name ?? '')
                ->upper()
                ->replaceMatches('/[^A-Z0-9]/', '')
                ->substr(0, 6)
                ->toString() ?: 'ANGK';

            $year = $cohort->year ?? now()->year;
            $cohort->code = sprintf('%s-%s-%s', $prefix, $year, Str::upper(Str::random(3)));
        });
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->useDisk('public')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /**
     * Get all members belonging to this cohort.
     */
    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'cohort_id');
    }

    /**
     * Scope a query to only include active cohorts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include alumni cohorts.
     */
    public function scopeAlumni($query)
    {
        return $query->where('status', 'alumni');
    }

    /**
     * Scope to order by year descending.
     */
    public function scopeOrderByYear($query, $direction = 'desc')
    {
        return $query->orderBy('year', $direction);
    }

    /**
     * Get the full cohort display name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->year})";
    }
}
