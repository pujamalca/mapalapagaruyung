<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Equipment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $table = 'equipment';

    protected $fillable = [
        'name',
        'code',
        'equipment_category_id',
        'description',
        'brand',
        'model',
        'purchase_date',
        'purchase_price',
        'condition',
        'status',
        'condition_notes',
        'storage_location',
        'unit',
        'quantity',
        'quantity_available',
        'last_maintenance_date',
        'next_maintenance_date',
        'maintenance_notes',
        'maintenance_interval_days',
        'current_borrower_id',
        'borrowed_until',
        'specifications',
        'metadata',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'purchase_price' => 'decimal:2',
            'quantity' => 'integer',
            'quantity_available' => 'integer',
            'last_maintenance_date' => 'date',
            'next_maintenance_date' => 'date',
            'borrowed_until' => 'date',
            'maintenance_interval_days' => 'integer',
            'specifications' => 'array',
            'metadata' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('equipment')
            ->logOnlyDirty()
            ->logFillable();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf']);
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class, 'equipment_category_id');
    }

    public function equipmentCategory(): BelongsTo
    {
        return $this->category();
    }

    public function currentBorrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_borrower_id');
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(EquipmentBorrowing::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function scopeNeedsMaintenance($query)
    {
        return $query->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<=', now()->addDays(30));
    }

    // Helper methods
    public function getConditionLabelAttribute(): string
    {
        return match($this->condition) {
            'excellent' => 'Sangat Baik',
            'good' => 'Baik',
            'fair' => 'Cukup',
            'poor' => 'Buruk',
            'damaged' => 'Rusak',
            default => 'Tidak Diketahui',
        };
    }

    public function getConditionColorAttribute(): string
    {
        return match($this->condition) {
            'excellent' => 'success',
            'good' => 'info',
            'fair' => 'warning',
            'poor' => 'danger',
            'damaged' => 'danger',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'maintenance' => 'Maintenance',
            'retired' => 'Tidak Dipakai',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'available' => 'success',
            'borrowed' => 'warning',
            'maintenance' => 'info',
            'retired' => 'gray',
            default => 'gray',
        };
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->condition !== 'damaged';
    }

    public function isBorrowed(): bool
    {
        return $this->status === 'borrowed';
    }

    public function needsMaintenance(): bool
    {
        if (!$this->next_maintenance_date) {
            return false;
        }

        return $this->next_maintenance_date->isPast() ||
               $this->next_maintenance_date->lte(now()->addDays(30));
    }

    public function getActiveBorrowing(): ?EquipmentBorrowing
    {
        return $this->borrowings()
            ->whereIn('status', ['approved', 'active'])
            ->first();
    }

    public function getTotalBorrowingsCount(): int
    {
        return $this->borrowings()->count();
    }

    public function markAsBorrowed(int $borrowerId, \Carbon\Carbon $until): void
    {
        $this->update([
            'status' => 'borrowed',
            'current_borrower_id' => $borrowerId,
            'borrowed_until' => $until,
        ]);
    }

    public function markAsReturned(): void
    {
        $this->update([
            'status' => 'available',
            'current_borrower_id' => null,
            'borrowed_until' => null,
        ]);
    }

    public function markUnderMaintenance(): void
    {
        $this->update(['status' => 'maintenance']);
    }

    public function completeMaintenance(): void
    {
        $this->update([
            'status' => 'available',
            'last_maintenance_date' => now(),
            'next_maintenance_date' => now()->addMonths(6),
        ]);
    }
}
