<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\GalleryCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📸 Seeding Galleries...');

        // Create categories
        $categories = [
            [
                'name' => 'Ekspedisi',
                'slug' => 'ekspedisi',
                'description' => 'Dokumentasi perjalanan dan pendakian gunung',
                'icon' => 'heroicon-o-map',
                'color' => 'success',
                'order' => 1,
            ],
            [
                'name' => 'Pelatihan',
                'slug' => 'pelatihan',
                'description' => 'Foto kegiatan pelatihan dan pendidikan',
                'icon' => 'heroicon-o-academic-cap',
                'color' => 'info',
                'order' => 2,
            ],
            [
                'name' => 'Kompetisi',
                'slug' => 'kompetisi',
                'description' => 'Foto event dan kompetisi',
                'icon' => 'heroicon-o-trophy',
                'color' => 'warning',
                'order' => 3,
            ],
            [
                'name' => 'Kegiatan Organisasi',
                'slug' => 'kegiatan-organisasi',
                'description' => 'Kegiatan internal dan gathering',
                'icon' => 'heroicon-o-users',
                'color' => 'primary',
                'order' => 4,
            ],
            [
                'name' => 'Konservasi',
                'slug' => 'konservasi',
                'description' => 'Kegiatan pelestarian lingkungan',
                'icon' => 'heroicon-o-leaf',
                'color' => 'emerald',
                'order' => 5,
            ],
        ];

        foreach ($categories as $categoryData) {
            GalleryCategory::create($categoryData);
        }

        // Get users
        $users = User::limit(5)->get();
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Skipping gallery creation.');
            return;
        }

        // Get categories
        $ekspedisi = GalleryCategory::where('slug', 'ekspedisi')->first();
        $pelatihan = GalleryCategory::where('slug', 'pelatihan')->first();
        $kompetisi = GalleryCategory::where('slug', 'kompetisi')->first();
        $organisasi = GalleryCategory::where('slug', 'kegiatan-organisasi')->first();

        // Create galleries
        $galleries = [
            [
                'title' => 'Pendakian Gunung Kerinci 2024',
                'slug' => 'pendakian-gunung-kerinci-2024',
                'description' => 'Dokumentasi lengkap pendakian ke puncak tertinggi Sumatera, Gunung Kerinci (3805 mdpl). Perjalanan 4 hari 3 malam dengan pemandangan luar biasa.',
                'gallery_category_id' => $ekspedisi->id,
                'event_date' => now()->subMonths(2),
                'location' => 'Gunung Kerinci, Jambi',
                'uploaded_by' => $users->first()->id,
                'photographer_name' => 'Tim Dokumentasi Mapala',
                'status' => 'published',
                'is_featured' => true,
                'is_public' => true,
                'tags' => ['gunung kerinci', 'pendakian', 'ekspedisi', 'sumatera'],
                'meta_title' => 'Foto Pendakian Gunung Kerinci 2024 | Mapala Pagaruyung',
                'meta_description' => 'Dokumentasi perjalanan pendakian Gunung Kerinci oleh Mapala Pagaruyung',
            ],
            [
                'title' => 'Diklatsar Kader XXIV',
                'slug' => 'diklatsar-kader-xxiv',
                'description' => 'Pendidikan dan Pelatihan Dasar untuk calon anggota baru Mapala Pagaruyung angkatan XXIV. Kegiatan intensif selama 5 hari di Gunung Singgalang.',
                'gallery_category_id' => $pelatihan->id,
                'event_date' => now()->subMonths(3),
                'location' => 'Gunung Singgalang, Tanah Datar',
                'uploaded_by' => $users->skip(1)->first()->id,
                'status' => 'published',
                'is_featured' => false,
                'is_public' => true,
                'tags' => ['diklatsar', 'pelatihan', 'kader', 'singgalang'],
            ],
            [
                'title' => 'Kejuaraan Panjat Tebing Nasional 2024',
                'slug' => 'kejuaraan-panjat-tebing-nasional-2024',
                'description' => 'Mapala Pagaruyung meraih prestasi gemilang di Kejuaraan Panjat Tebing Nasional. Beberapa anggota berhasil naik podium.',
                'gallery_category_id' => $kompetisi->id,
                'event_date' => now()->subMonth(),
                'location' => 'Jakarta',
                'uploaded_by' => $users->skip(2)->first()->id,
                'status' => 'published',
                'is_featured' => true,
                'is_public' => true,
                'tags' => ['panjat tebing', 'kompetisi', 'nasional', 'prestasi'],
            ],
            [
                'title' => 'Gathering Akhir Tahun 2024',
                'slug' => 'gathering-akhir-tahun-2024',
                'description' => 'Pertemuan tahunan seluruh anggota Mapala Pagaruyung untuk evaluasi kegiatan dan perencanaan tahun depan.',
                'gallery_category_id' => $organisasi->id,
                'event_date' => now()->subWeeks(2),
                'location' => 'Basecamp Mapala Pagaruyung',
                'uploaded_by' => $users->first()->id,
                'status' => 'published',
                'is_featured' => false,
                'is_public' => true,
                'tags' => ['gathering', 'organisasi', 'internal'],
            ],
            [
                'title' => 'Ekspedisi Gunung Singgalang - Draft',
                'slug' => 'ekspedisi-gunung-singgalang-draft',
                'description' => 'Ekspedisi training yang sedang berlangsung. Galeri akan dipublikasikan setelah kegiatan selesai.',
                'gallery_category_id' => $ekspedisi->id,
                'event_date' => now()->subDay(),
                'location' => 'Gunung Singgalang',
                'uploaded_by' => $users->first()->id,
                'status' => 'draft',
                'is_featured' => false,
                'is_public' => false,
                'tags' => ['singgalang', 'training'],
            ],
        ];

        foreach ($galleries as $galleryData) {
            Gallery::create($galleryData);
        }

        $this->command->info('✅ Galleries seeded successfully!');
        $this->command->info('   - Created ' . count($categories) . ' gallery categories');
        $this->command->info('   - Created ' . count($galleries) . ' galleries');
    }
}
