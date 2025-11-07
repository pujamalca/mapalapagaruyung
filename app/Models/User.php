<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia, HasAvatar, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    use InteractsWithMedia;
    use LogsActivity;

    protected string $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'avatar',
        'bio',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'preferences',
        'metadata',
        'password',
        // Mapala-specific fields
        'nim',
        'major',
        'faculty',
        'enrollment_year',
        'cohort_id',
        'member_number',
        'mapala_join_year',
        'member_status',
        'address',
        'blood_type',
        'medical_history',
        'emergency_contact',
        'skills',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'preferences' => 'array',
            'metadata' => 'array',
            'password' => 'hashed',
            // Mapala-specific casts
            'enrollment_year' => 'integer',
            'mapala_join_year' => 'integer',
            'emergency_contact' => 'array',
            'skills' => 'array',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();

        $this->addMediaCollection('certificates')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);
    }

    public function cohort(): BelongsTo
    {
        return $this->belongsTo(Cohort::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function divisions(): BelongsToMany
    {
        return $this->belongsToMany(Division::class, 'division_user')
            ->withPivot(['joined_at', 'role', 'is_active'])
            ->withTimestamps();
    }

    public function activeDivisions(): BelongsToMany
    {
        return $this->divisions()->wherePivot('is_active', true);
    }

    public function getAvatarUrl(): string
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        if ($this->hasMedia('avatar')) {
            return $this->getFirstMediaUrl('avatar');
        }

        return sprintf('https://www.gravatar.com/avatar/%s?d=mp', md5(strtolower(trim((string) $this->email))));
    }

    /**
     * Get the Filament avatar URL for the user.
     * This method is automatically called by Filament to display user avatar.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->avatar) {
            // If avatar is already a full URL, return it
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }

            // Otherwise, return the storage URL
            return Storage::url($this->avatar);
        }

        // Fallback to null (will show initials)
        return null;
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['Super Admin', 'Admin']) || $this->can('manage-users');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    public function getFullNameAttribute(): string
    {
        return Str::of($this->name)
            ->whenBlank(fn () => Str::of($this->username))
            ->whenBlank(fn () => Str::of($this->email))
            ->toString();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('user')
            ->logOnlyDirty()
            ->logFillable();
    }

    /**
     * Check if user is a Mapala member (not just prospective).
     */
    public function isMember(): bool
    {
        return in_array($this->member_status, ['junior', 'member', 'alumni']);
    }

    /**
     * Check if user is an active member.
     */
    public function isActiveMember(): bool
    {
        return in_array($this->member_status, ['junior', 'member']) && $this->is_active;
    }

    /**
     * Check if user is alumni.
     */
    public function isAlumni(): bool
    {
        return $this->member_status === 'alumni';
    }

    /**
     * Check if user is prospective member.
     */
    public function isProspective(): bool
    {
        return $this->member_status === 'prospective';
    }

    /**
     * Get formatted member status.
     */
    public function getMemberStatusLabelAttribute(): string
    {
        return match($this->member_status) {
            'prospective' => 'Calon Anggota',
            'junior' => 'Anggota Muda',
            'member' => 'Anggota',
            'alumni' => 'Alumni',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get emergency contact name.
     */
    public function getEmergencyContactNameAttribute(): ?string
    {
        return $this->emergency_contact['name'] ?? null;
    }

    /**
     * Get emergency contact phone.
     */
    public function getEmergencyContactPhoneAttribute(): ?string
    {
        return $this->emergency_contact['phone'] ?? null;
    }
}

