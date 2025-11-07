<?php

namespace Database\Seeders;

use App\Models\Cohort;
use App\Models\RecruitmentPeriod;
use App\Models\SelectionStage;
use Illuminate\Database\Seeder;

class RecruitmentSeeder extends Seeder
{
    public function run(): void
    {
        // Get the latest cohort for recruitment
        $latestCohort = Cohort::latest('year')->first();

        // Create a current recruitment period (open)
        $currentPeriod = RecruitmentPeriod::create([
            'name' => 'Open Recruitment Kader XXIV 2025/2026',
            'slug' => 'open-recruitment-kader-xxiv-2025-2026',
            'description' => 'Pendaftaran anggota baru Mapala Pagaruyung periode 2025/2026. Mari bergabung bersama kami untuk menjelajahi alam Indonesia!',
            'cohort_id' => $latestCohort?->id,
            'registration_start' => now()->subDays(7),
            'registration_end' => now()->addDays(14),
            'selection_start' => now()->addDays(15),
            'selection_end' => now()->addDays(45),
            'announcement_date' => now()->addDays(50),
            'status' => 'open',
            'is_active' => true,
            'max_applicants' => 100,
            'target_accepted' => 30,
            'registration_fee' => 50000,
            'payment_instructions' => '<p><strong>Cara Pembayaran:</strong></p><ul><li>Transfer ke rekening BNI: 1234567890 a.n. Mapala Pagaruyung</li><li>Upload bukti transfer pada form pendaftaran</li><li>Konfirmasi via WhatsApp: 0812-3456-7890</li></ul>',
            'requirements' => [
                ['requirement' => 'Mahasiswa aktif Universitas Andalas'],
                ['requirement' => 'IPK minimal 2.50'],
                ['requirement' => 'Sehat jasmani dan rohani'],
                ['requirement' => 'Tidak sedang menjabat di organisasi lain sebagai pengurus inti'],
                ['requirement' => 'Bersedia mengikuti seluruh rangkaian seleksi'],
                ['requirement' => 'Memiliki motivasi tinggi untuk belajar alam terbuka'],
            ],
            'selection_stages' => [
                ['stage' => 'Verifikasi Berkas'],
                ['stage' => 'Wawancara'],
                ['stage' => 'Tes Fisik'],
                ['stage' => 'Praktik Lapangan'],
                ['stage' => 'Evaluasi Akhir'],
            ],
            'metadata' => [
                'registration_prefix' => 'MAP-2025-',
                'contact_person' => 'Panitia ORC Mapala Pagaruyung',
                'contact_phone' => '0812-3456-7890',
                'contact_email' => 'recruitment@mapalapagaruyung.org',
            ],
        ]);

        // Create selection stages for current period
        $stages = [
            [
                'name' => 'Verifikasi Berkas',
                'slug' => 'verifikasi-berkas',
                'description' => 'Verifikasi kelengkapan dan keabsahan dokumen pendaftaran',
                'order' => 1,
                'scheduled_date' => $currentPeriod->registration_end->addDays(1),
                'location' => 'Sekretariat Mapala Pagaruyung',
                'instructions' => 'Pastikan semua dokumen telah diupload dengan jelas dan lengkap',
                'is_scored' => false,
                'max_score' => 0,
                'passing_score' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Wawancara',
                'slug' => 'wawancara',
                'description' => 'Wawancara untuk mengetahui motivasi, pengalaman, dan kepribadian calon anggota',
                'order' => 2,
                'scheduled_date' => $currentPeriod->selection_start,
                'location' => 'Basecamp Mapala Pagaruyung',
                'instructions' => 'Berpakaian rapi dan sopan. Datang 15 menit sebelum jadwal.',
                'is_scored' => true,
                'max_score' => 100,
                'passing_score' => 70,
                'criteria' => [
                    ['name' => 'Motivasi', 'weight' => 30, 'max_score' => 100],
                    ['name' => 'Komunikasi', 'weight' => 25, 'max_score' => 100],
                    ['name' => 'Kepribadian', 'weight' => 25, 'max_score' => 100],
                    ['name' => 'Pengetahuan Alam', 'weight' => 20, 'max_score' => 100],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Tes Fisik',
                'slug' => 'tes-fisik',
                'description' => 'Tes kemampuan fisik dasar untuk kegiatan alam terbuka',
                'order' => 3,
                'scheduled_date' => $currentPeriod->selection_start->addDays(3),
                'location' => 'Lapangan Unand / Gunung Padang',
                'instructions' => 'Kenakan pakaian olahraga. Bawa air minum dan obat-obatan pribadi jika diperlukan.',
                'is_scored' => true,
                'max_score' => 100,
                'passing_score' => 60,
                'criteria' => [
                    ['name' => 'Lari 3 KM', 'weight' => 25, 'max_score' => 100],
                    ['name' => 'Push Up', 'weight' => 20, 'max_score' => 100],
                    ['name' => 'Sit Up', 'weight' => 20, 'max_score' => 100],
                    ['name' => 'Daya Tahan', 'weight' => 35, 'max_score' => 100],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Praktik Lapangan',
                'slug' => 'praktik-lapangan',
                'description' => 'Praktik langsung di alam untuk menilai kemampuan adaptasi dan kerjasama',
                'order' => 4,
                'scheduled_date' => $currentPeriod->selection_start->addDays(10),
                'location' => 'Gunung Singgalang',
                'instructions' => 'Persiapkan perlengkapan pribadi sesuai daftar yang telah diberikan. Kegiatan berlangsung 2 hari 1 malam.',
                'is_scored' => true,
                'max_score' => 100,
                'passing_score' => 70,
                'criteria' => [
                    ['name' => 'Kerjasama Tim', 'weight' => 30, 'max_score' => 100],
                    ['name' => 'Skill Teknis', 'weight' => 25, 'max_score' => 100],
                    ['name' => 'Adaptasi', 'weight' => 25, 'max_score' => 100],
                    ['name' => 'Mental & Sikap', 'weight' => 20, 'max_score' => 100],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Evaluasi Akhir',
                'slug' => 'evaluasi-akhir',
                'description' => 'Penilaian menyeluruh dari semua tahapan',
                'order' => 5,
                'scheduled_date' => $currentPeriod->selection_end,
                'location' => 'Sekretariat Mapala Pagaruyung',
                'instructions' => 'Tahap final untuk menentukan kelulusan berdasarkan akumulasi nilai seluruh tahapan',
                'is_scored' => true,
                'max_score' => 100,
                'passing_score' => 75,
                'criteria' => [
                    ['name' => 'Total Nilai Keseluruhan', 'weight' => 60, 'max_score' => 100],
                    ['name' => 'Perkembangan Selama Seleksi', 'weight' => 25, 'max_score' => 100],
                    ['name' => 'Potensi Kontribusi', 'weight' => 15, 'max_score' => 100],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($stages as $stageData) {
            SelectionStage::create(array_merge($stageData, [
                'recruitment_period_id' => $currentPeriod->id,
            ]));
        }

        // Create a past recruitment period (closed)
        $pastCohort = Cohort::where('year', '<', now()->year)->latest('year')->first();

        if ($pastCohort) {
            RecruitmentPeriod::create([
                'name' => 'Open Recruitment Kader XXIII 2024/2025',
                'slug' => 'open-recruitment-kader-xxiii-2024-2025',
                'description' => 'Periode recruitment tahun lalu yang telah selesai',
                'cohort_id' => $pastCohort->id,
                'registration_start' => now()->subYear()->subMonths(2),
                'registration_end' => now()->subYear()->subMonth(),
                'selection_start' => now()->subYear()->subDays(25),
                'selection_end' => now()->subYear()->addDays(5),
                'announcement_date' => now()->subYear()->addDays(10),
                'status' => 'closed',
                'is_active' => false,
                'max_applicants' => 80,
                'target_accepted' => 25,
                'registration_fee' => 45000,
                'requirements' => [
                    ['requirement' => 'Mahasiswa aktif Universitas Andalas'],
                    ['requirement' => 'IPK minimal 2.50'],
                    ['requirement' => 'Sehat jasmani dan rohani'],
                ],
                'metadata' => [
                    'registration_prefix' => 'MAP-2024-',
                ],
            ]);
        }

        $this->command->info('✅ Recruitment periods and selection stages created successfully!');
        $this->command->info('   - Current period: ' . $currentPeriod->name);
        $this->command->info('   - Selection stages: 5 stages configured');
    }
}
