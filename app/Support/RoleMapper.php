<?php

namespace App\Support;

use Illuminate\Support\Arr;

class RoleMapper
{
    /**
     * Map legacy / English role names to the canonical roles used in seeders.
     *
     * @var array<string, string>
     */
    protected static array $aliases = [
        'Member' => 'Anggota',
        'Active Member' => 'Anggota',
        'Senior Member' => 'Anggota Muda',
        'Junior Member' => 'Anggota Muda',
        'Prospective Member' => 'Calon Anggota',
        'Candidate Member' => 'Calon Anggota',
    ];

    /**
     * Canonical role names that exist in the system.
     *
     * @var array<int, string>
     */
    protected static array $canonicalRoles = [
        'Super Admin',
        'Admin',
        'BKP',
        'Ketua Divisi',
        'Anggota',
        'Anggota Muda',
        'Calon Anggota',
        'Alumni',
        'Content Editor',
    ];

    /**
     * Normalize one or more role names into the canonical names that exist in the database.
     *
     * @param  string|array<int, string>  $roles
     * @return array<int, string>
     */
    public static function normalize(string|array $roles): array
    {
        return collect(Arr::wrap($roles))
            ->filter()
            ->map(fn (string $role) => static::$aliases[$role] ?? $role)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Normalize a single role name.
     */
    public static function normalizeSingle(string $role): string
    {
        return static::normalize([$role])[0] ?? $role;
    }

    /**
     * All role labels accepted by importers / validators (aliases + canonical names).
     *
     * @return array<int, string>
     */
    public static function validNames(): array
    {
        return array_values(array_unique(array_merge(
            array_keys(static::$aliases),
            array_values(static::$aliases),
            static::$canonicalRoles
        )));
    }
}
