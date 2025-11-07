<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionDefinitions = [
            // System
            [
                'slug' => 'access-admin-panel',
                'label' => 'Access Admin Panel',
                'module' => 'system',
                'description' => 'Akses penuh ke panel admin.',
            ],
            [
                'slug' => 'access-settings',
                'label' => 'Access Settings',
                'module' => 'system',
                'description' => 'Akses halaman pengaturan aplikasi.',
            ],
            [
                'slug' => 'view-activity-log',
                'label' => 'View Activity Log',
                'module' => 'system',
                'description' => 'Melihat catatan aktivitas aplikasi.',
            ],

            // Users
            [
                'slug' => 'manage-users',
                'label' => 'Manage Users',
                'module' => 'users',
                'description' => 'Mengelola data pengguna termasuk role & status aktif.',
            ],
            [
                'slug' => 'view-users',
                'label' => 'View Users',
                'module' => 'users',
                'description' => 'Melihat daftar anggota.',
            ],
            [
                'slug' => 'manage-roles',
                'label' => 'Manage Roles',
                'module' => 'users',
                'description' => 'Mengelola role dan permission.',
            ],
            [
                'slug' => 'manage-permissions',
                'label' => 'Manage Permissions',
                'module' => 'users',
                'description' => 'Mengelola daftar permission yang tersedia.',
            ],

            // Content
            [
                'slug' => 'manage-posts',
                'label' => 'Manage Posts',
                'module' => 'content',
                'description' => 'Membuat dan mengelola postingan.',
            ],
            [
                'slug' => 'view-posts',
                'label' => 'View Posts',
                'module' => 'content',
                'description' => 'Melihat postingan.',
            ],
            [
                'slug' => 'manage-pages',
                'label' => 'Manage Pages',
                'module' => 'content',
                'description' => 'Mengelola halaman statis.',
            ],
            [
                'slug' => 'manage-categories',
                'label' => 'Manage Categories',
                'module' => 'content',
                'description' => 'Mengelola struktur kategori konten.',
            ],
            [
                'slug' => 'manage-tags',
                'label' => 'Manage Tags',
                'module' => 'content',
                'description' => 'Mengelola tag untuk klasifikasi konten.',
            ],
            [
                'slug' => 'manage-comments',
                'label' => 'Manage Comments',
                'module' => 'content',
                'description' => 'Moderasi dan penyuntingan komentar.',
            ],

            // Mapala - Cohorts
            [
                'slug' => 'manage-cohorts',
                'label' => 'Manage Cohorts',
                'module' => 'mapala',
                'description' => 'Mengelola data angkatan/kader.',
            ],
            [
                'slug' => 'view-cohorts',
                'label' => 'View Cohorts',
                'module' => 'mapala',
                'description' => 'Melihat daftar angkatan.',
            ],

            // Mapala - Divisions
            [
                'slug' => 'manage-divisions',
                'label' => 'Manage Divisions',
                'module' => 'mapala',
                'description' => 'Mengelola divisi dan struktur organisasi.',
            ],
            [
                'slug' => 'view-divisions',
                'label' => 'View Divisions',
                'module' => 'mapala',
                'description' => 'Melihat divisi organisasi.',
            ],
            [
                'slug' => 'manage-division-members',
                'label' => 'Manage Division Members',
                'module' => 'mapala',
                'description' => 'Mengelola anggota dalam divisi.',
            ],

            // Mapala - Recruitment
            [
                'slug' => 'manage-recruitment',
                'label' => 'Manage Recruitment',
                'module' => 'mapala',
                'description' => 'Mengelola periode open recruitment.',
            ],
            [
                'slug' => 'view-applicants',
                'label' => 'View Applicants',
                'module' => 'mapala',
                'description' => 'Melihat data pendaftar.',
            ],
            [
                'slug' => 'manage-applicants',
                'label' => 'Manage Applicants',
                'module' => 'mapala',
                'description' => 'Mengelola dan mengevaluasi pendaftar.',
            ],

            // Mapala - Training (BKP)
            [
                'slug' => 'manage-training',
                'label' => 'Manage Training',
                'module' => 'mapala',
                'description' => 'Mengelola program pelatihan BKP.',
            ],
            [
                'slug' => 'view-training',
                'label' => 'View Training',
                'module' => 'mapala',
                'description' => 'Melihat program pelatihan.',
            ],
            [
                'slug' => 'evaluate-training',
                'label' => 'Evaluate Training',
                'module' => 'mapala',
                'description' => 'Mengevaluasi peserta pelatihan.',
            ],

            // Mapala - Expeditions
            [
                'slug' => 'manage-expeditions',
                'label' => 'Manage Expeditions',
                'module' => 'mapala',
                'description' => 'Mengelola kegiatan ekspedisi.',
            ],
            [
                'slug' => 'view-expeditions',
                'label' => 'View Expeditions',
                'module' => 'mapala',
                'description' => 'Melihat daftar ekspedisi.',
            ],
            [
                'slug' => 'join-expeditions',
                'label' => 'Join Expeditions',
                'module' => 'mapala',
                'description' => 'Mendaftar sebagai peserta ekspedisi.',
            ],

            // Mapala - Competitions
            [
                'slug' => 'manage-competitions',
                'label' => 'Manage Competitions',
                'module' => 'mapala',
                'description' => 'Mengelola kompetisi dan event.',
            ],
            [
                'slug' => 'view-competitions',
                'label' => 'View Competitions',
                'module' => 'mapala',
                'description' => 'Melihat kompetisi dan event.',
            ],
            [
                'slug' => 'register-competitions',
                'label' => 'Register Competitions',
                'module' => 'mapala',
                'description' => 'Mendaftar kompetisi.',
            ],

            // Mapala - Gallery
            [
                'slug' => 'manage-gallery',
                'label' => 'Manage Gallery',
                'module' => 'mapala',
                'description' => 'Mengelola galeri foto.',
            ],
            [
                'slug' => 'view-gallery',
                'label' => 'View Gallery',
                'module' => 'mapala',
                'description' => 'Melihat galeri foto.',
            ],
            [
                'slug' => 'upload-photos',
                'label' => 'Upload Photos',
                'module' => 'mapala',
                'description' => 'Mengunggah foto ke galeri.',
            ],

            // Mapala - Equipment
            [
                'slug' => 'manage-equipment',
                'label' => 'Manage Equipment',
                'module' => 'mapala',
                'description' => 'Mengelola inventaris peralatan.',
            ],
            [
                'slug' => 'view-equipment',
                'label' => 'View Equipment',
                'module' => 'mapala',
                'description' => 'Melihat daftar peralatan.',
            ],
            [
                'slug' => 'borrow-equipment',
                'label' => 'Borrow Equipment',
                'module' => 'mapala',
                'description' => 'Meminjam peralatan.',
            ],
            [
                'slug' => 'manage-borrowing',
                'label' => 'Manage Borrowing',
                'module' => 'mapala',
                'description' => 'Mengelola peminjaman peralatan.',
            ],
        ];

        $permissions = collect($permissionDefinitions)->mapWithKeys(function (array $attributes) {
            $permission = Permission::updateOrCreate(
                ['slug' => $attributes['slug']],
                [
                    'name' => $attributes['slug'],
                    'slug' => $attributes['slug'],
                    'module' => $attributes['module'],
                    'description' => $attributes['description'],
                    'guard_name' => 'web',
                    'metadata' => [
                        'label' => $attributes['label'],
                    ],
                ]
            );

            return [$permission->slug => $permission];
        });

        // 1. Super Admin - Full access to everything
        $superAdmin = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Memiliki akses penuh ke seluruh fitur sistem.',
                'guard_name' => 'web',
                'is_system' => true,
            ]
        );
        $superAdmin->syncPermissions($permissions->values());

        // 2. Admin - Organizational management
        $admin = Role::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Admin',
                'description' => 'Administrator organisasi dengan akses penuh kecuali pengaturan sistem.',
                'guard_name' => 'web',
            ]
        );
        $admin->syncPermissions(
            $permissions->only([
                'access-admin-panel',
                'manage-users', 'view-users',
                'manage-posts', 'view-posts', 'manage-pages', 'manage-categories', 'manage-tags', 'manage-comments',
                'manage-cohorts', 'view-cohorts',
                'manage-divisions', 'view-divisions', 'manage-division-members',
                'manage-recruitment', 'view-applicants', 'manage-applicants',
                'manage-training', 'view-training', 'evaluate-training',
                'manage-expeditions', 'view-expeditions',
                'manage-competitions', 'view-competitions',
                'manage-gallery', 'view-gallery', 'upload-photos',
                'manage-equipment', 'view-equipment', 'borrow-equipment', 'manage-borrowing',
                'view-activity-log',
            ])->values()
        );

        // 3. BKP (Badan Khusus Pendidikan) - Recruitment and Training
        $bkp = Role::updateOrCreate(
            ['slug' => 'bkp'],
            [
                'name' => 'BKP',
                'description' => 'Badan Khusus Pendidikan - Mengelola rekrutmen dan pelatihan anggota.',
                'guard_name' => 'web',
            ]
        );
        $bkp->syncPermissions(
            $permissions->only([
                'access-admin-panel',
                'view-users',
                'manage-recruitment', 'view-applicants', 'manage-applicants',
                'manage-training', 'view-training', 'evaluate-training',
                'view-cohorts',
                'view-divisions',
                'view-posts', 'manage-posts',
                'view-gallery', 'upload-photos',
            ])->values()
        );

        // 4. Ketua Divisi - Division leadership
        $ketuaDivisi = Role::updateOrCreate(
            ['slug' => 'ketua-divisi'],
            [
                'name' => 'Ketua Divisi',
                'description' => 'Ketua divisi dengan akses mengelola anggota divisi dan kegiatan.',
                'guard_name' => 'web',
            ]
        );
        $ketuaDivisi->syncPermissions(
            $permissions->only([
                'access-admin-panel',
                'view-users',
                'view-divisions', 'manage-division-members',
                'view-cohorts',
                'view-expeditions', 'manage-expeditions', 'join-expeditions',
                'view-competitions',
                'view-posts', 'manage-posts',
                'view-gallery', 'upload-photos',
                'view-equipment', 'borrow-equipment',
            ])->values()
        );

        // 5. Anggota (Full Member) - Standard member access
        $anggota = Role::updateOrCreate(
            ['slug' => 'anggota'],
            [
                'name' => 'Anggota',
                'description' => 'Anggota penuh Mapala dengan akses kegiatan dan konten.',
                'guard_name' => 'web',
            ]
        );
        $anggota->syncPermissions(
            $permissions->only([
                'access-admin-panel',
                'view-users',
                'view-divisions',
                'view-cohorts',
                'view-expeditions', 'join-expeditions',
                'view-competitions', 'register-competitions',
                'view-posts', 'manage-posts',
                'view-gallery', 'upload-photos',
                'view-equipment', 'borrow-equipment',
            ])->values()
        );

        // 6. Anggota Muda (Junior Member) - Limited member access
        $anggotaMuda = Role::updateOrCreate(
            ['slug' => 'anggota-muda'],
            [
                'name' => 'Anggota Muda',
                'description' => 'Anggota muda dengan akses terbatas.',
                'guard_name' => 'web',
            ]
        );
        $anggotaMuda->syncPermissions(
            $permissions->only([
                'access-admin-panel',
                'view-users',
                'view-divisions',
                'view-cohorts',
                'view-expeditions', 'join-expeditions',
                'view-competitions',
                'view-posts',
                'view-gallery',
                'view-equipment', 'borrow-equipment',
            ])->values()
        );

        // 7. Calon Anggota (Prospective Member) - Very limited access
        $calonAnggota = Role::updateOrCreate(
            ['slug' => 'calon-anggota'],
            [
                'name' => 'Calon Anggota',
                'description' => 'Calon anggota yang sedang dalam masa seleksi.',
                'guard_name' => 'web',
            ]
        );
        $calonAnggota->syncPermissions(
            $permissions->only([
                'access-admin-panel',
                'view-divisions',
                'view-posts',
                'view-gallery',
                'view-training',
            ])->values()
        );

        // 8. Alumni - View-only access
        $alumni = Role::updateOrCreate(
            ['slug' => 'alumni'],
            [
                'name' => 'Alumni',
                'description' => 'Alumni Mapala dengan akses viewing saja.',
                'guard_name' => 'web',
            ]
        );
        $alumni->syncPermissions(
            $permissions->only([
                'access-admin-panel',
                'view-users',
                'view-divisions',
                'view-cohorts',
                'view-expeditions',
                'view-competitions',
                'view-posts',
                'view-gallery',
            ])->values()
        );

        // 9. Content Editor (keep existing)
        $contentEditor = Role::updateOrCreate(
            ['slug' => 'content-editor'],
            [
                'name' => 'Content Editor',
                'description' => 'Mengelola konten tanpa akses ke pengaturan kritikal.',
                'guard_name' => 'web',
            ]
        );
        $contentEditor->syncPermissions(
            $permissions->only([
                'access-admin-panel',
                'manage-posts', 'view-posts',
                'manage-pages',
                'manage-categories',
                'manage-tags',
                'manage-comments',
                'view-gallery', 'upload-photos',
            ])->values()
        );

        $this->command->info('✅ Permissions created: ' . $permissions->count());
        $this->command->info('✅ Roles created: 9 (Super Admin, Admin, BKP, Ketua Divisi, Anggota, Anggota Muda, Calon Anggota, Alumni, Content Editor)');
    }
}
