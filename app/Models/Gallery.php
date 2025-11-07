<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Gallery extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'gallery_category_id',
        'tags',
        'galleryable_type',
        'galleryable_id',
        'event_date',
        'location',
        'uploaded_by',
        'photographer_name',
        'is_featured',
        'is_public',
        'status',
        'published_at',
        'view_count',
        'media_count',
        'meta_title',
        'meta_description',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'tags' => 'array',
            'metadata' => 'array',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'published_at' => 'datetime',
            'view_count' => 'integer',
            'media_count' => 'integer',
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
            ->useLogName('gallery')
            ->logOnlyDirty()
            ->logFillable();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->useFallbackUrl('/images/placeholder.jpg');

        $this->addMediaCollection('videos')
            ->acceptsMimeTypes(['video/mp4', 'video/mpeg', 'video/quicktime']);

        $this->addMediaCollection('cover')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->useFallbackUrl('/images/gallery-cover-placeholder.jpg');
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }

    public function galleryCategory(): BelongsTo
    {
        return $this->category();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function galleryable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeRecent($query, int $limit = 10)
    {
        return $query->published()->orderBy('event_date', 'desc')->limit($limit);
    }

    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'published' => 'Dipublikasikan',
            'archived' => 'Diarsipkan',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'published' => 'success',
            'archived' => 'warning',
            default => 'gray',
        };
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function updateMediaCount(): void
    {
        $imageCount = $this->getMedia('images')->count();
        $videoCount = $this->getMedia('videos')->count();
        $this->update(['media_count' => $imageCount + $videoCount]);
    }

    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'is_public' => true,
            'published_at' => $this->published_at ?? now(),
        ]);
    }

    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }

    public function getCoverImage(): ?string
    {
        $cover = $this->getFirstMediaUrl('cover');
        if ($cover) {
            return $cover;
        }

        // Fallback to first image
        return $this->getFirstMediaUrl('images');
    }

    public function getImagesCount(): int
    {
        return $this->getMedia('images')->count();
    }

    public function getVideosCount(): int
    {
        return $this->getMedia('videos')->count();
    }

    public function getTotalMediaCount(): int
    {
        return $this->getImagesCount() + $this->getVideosCount();
    }
}
