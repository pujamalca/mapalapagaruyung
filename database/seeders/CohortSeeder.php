<?php

namespace Database\Seeders;

use App\Models\Cohort;
use Illuminate\Database\Seeder;

class CohortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cohorts = [
            [
                'name' => 'Kader XVIII',
                'year' => 2020,
                'theme' => 'Harmoni Alam dan Manusia',
                'description' => 'Angkatan pertama di era pandemi dengan semangat yang tinggi.',
                'status' => 'alumni',
                'member_count' => 28,
                'sort_order' => 1,
            ],
            [
                'name' => 'Kader XIX',
                'year' => 2021,
                'theme' => 'Jejak Langkah Pejuang Alam',
                'description' => 'Melanjutkan tradisi dengan inovasi baru.',
                'status' => 'alumni',
                'member_count' => 32,
                'sort_order' => 2,
            ],
            [
                'name' => 'Kader XX',
                'year' => 2022,
                'theme' => 'Bersatu Menembus Batas',
                'description' => 'Angkatan yang solid dan penuh prestasi.',
                'status' => 'alumni',
                'member_count' => 35,
                'sort_order' => 3,
            ],
            [
                'name' => 'Kader XXI',
                'year' => 2023,
                'theme' => 'Eksplorasi Tanpa Henti',
                'description' => 'Mengukir sejarah dengan berbagai ekspedisi menantang.',
                'status' => 'alumni',
                'member_count' => 30,
                'sort_order' => 4,
            ],
            [
                'name' => 'Kader XXII',
                'year' => 2024,
                'theme' => 'Menggapai Puncak Bersama',
                'description' => 'Angkatan aktif dengan semangat kekeluargaan yang kuat.',
                'status' => 'active',
                'member_count' => 38,
                'sort_order' => 5,
            ],
            [
                'name' => 'Kader XXIII',
                'year' => 2025,
                'theme' => 'Penjaga Rimba Nusantara',
                'description' => 'Angkatan terbaru dengan fokus pada konservasi alam.',
                'status' => 'active',
                'member_count' => 0,
                'sort_order' => 6,
            ],
        ];

        foreach ($cohorts as $cohortData) {
            $cohort = Cohort::create($cohortData);

            $this->command->info("Created cohort: {$cohort->name} ({$cohort->year})");
        }

        $this->command->info('Cohort seeder completed successfully!');
    }
}
