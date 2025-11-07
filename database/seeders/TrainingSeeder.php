<?php

namespace Database\Seeders;

use App\Models\Cohort;
use App\Models\TrainingProgram;
use App\Models\TrainingSession;
use App\Models\TrainingParticipant;
use App\Models\TrainingAttendance;
use App\Models\User;
use Illuminate\Database\Seeder;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎓 Seeding Training Programs...');

        // Get or create cohorts
        $cohort24 = Cohort::firstOrCreate(
            ['code' => 'XXIV'],
            [
                'name' => 'Kader XXIV',
                'year' => 2024,
                'status' => 'active',
                'description' => 'Angkatan 24',
            ]
        );

        $cohort25 = Cohort::firstOrCreate(
            ['code' => 'XXV'],
            [
                'name' => 'Kader XXV',
                'year' => 2025,
                'status' => 'active',
                'description' => 'Angkatan 25',
            ]
        );

        // Get BKP members (coordinators)
        $coordinators = User::whereHas('roles', function ($query) {
            $query->where('name', 'BKP');
        })->get();

        if ($coordinators->isEmpty()) {
            $coordinators = User::limit(3)->get();
        }

        // Get members for participants
        $members = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['member', 'active_member']);
        })->get();

        if ($members->isEmpty()) {
            $members = User::limit(10)->get();
        }

        // 1. Diklatsar (Basic Training) - Completed
        $diklatsar = TrainingProgram::create([
            'name' => 'Diklatsar Kader XXIV',
            'slug' => 'diklatsar-kader-xxiv',
            'description' => 'Pendidikan dan Pelatihan Dasar untuk calon anggota baru Mapala Pagaruyung angkatan XXIV. Mencakup materi dasar navigasi, survival, dan karakter pecinta alam.',
            'cohort_id' => $cohort24->id,
            'program_type' => 'basic',
            'level' => 'beginner',
            'start_date' => now()->subMonths(3),
            'end_date' => now()->subMonths(2),
            'location' => 'Basecamp Mapala Pagaruyung & Gunung Singgalang',
            'location_details' => 'Kegiatan dilaksanakan di basecamp untuk teori dan Gunung Singgalang untuk praktik lapangan',
            'max_participants' => 30,
            'min_participants' => 15,
            'training_fee' => 350000,
            'status' => 'completed',
            'registration_status' => 'closed',
            'is_mandatory' => true,
            'coordinator_id' => $coordinators->first()->id ?? null,
            'instructors' => [
                ['name' => 'Rian Syahputra', 'expertise' => 'Navigasi & Orienteering'],
                ['name' => 'Desi Ratnawati', 'expertise' => 'P3K & Survival'],
                ['name' => 'Ahmad Fauzi', 'expertise' => 'SAR & Rope Technique'],
            ],
            'learning_objectives' => [
                ['objective' => 'Memahami sejarah dan filosofi pecinta alam'],
                ['objective' => 'Menguasai teknik dasar navigasi darat dengan kompas dan peta'],
                ['objective' => 'Menguasai teknik survival dasar di alam bebas'],
                ['objective' => 'Memahami prinsip-prinsip SAR dan P3K'],
                ['objective' => 'Membangun karakter dan kerjasama tim'],
            ],
            'requirements' => [
                ['requirement' => 'Sehat jasmani dan rohani'],
                ['requirement' => 'Lulus seleksi penerimaan anggota baru'],
                ['requirement' => 'Memiliki komitmen tinggi'],
                ['requirement' => 'Bersedia mengikuti seluruh rangkaian kegiatan'],
            ],
            'materials_needed' => [
                ['material' => 'Perlengkapan carrier dan camping'],
                ['material' => 'Kompas, peta topografi, GPS'],
                ['material' => 'Perlengkapan P3K pribadi'],
                ['material' => 'Pakaian lapangan dan sepatu tracking'],
            ],
            'has_evaluation' => true,
            'passing_score' => 75,
            'evaluation_criteria' => [
                ['criteria' => 'Kehadiran', 'weight' => 20],
                ['criteria' => 'Ujian Tertulis', 'weight' => 30],
                ['criteria' => 'Praktik Navigasi', 'weight' => 25],
                ['criteria' => 'Praktik Survival', 'weight' => 25],
            ],
            'notes' => 'Program wajib bagi seluruh calon anggota baru',
        ]);

        // Sessions for Diklatsar
        $diklatsarSessions = [
            [
                'title' => 'Pembukaan & Materi Filosofi Pecinta Alam',
                'description' => 'Pengenalan organisasi dan filosofi dasar pecinta alam Indonesia',
                'order' => 1,
                'scheduled_date' => $diklatsar->start_date,
                'duration_minutes' => 180,
                'location' => 'Basecamp Mapala',
                'content' => '<p>Materi mencakup sejarah pecinta alam Indonesia, kode etik, dan pembentukan karakter pecinta alam sejati.</p>',
                'status' => 'completed',
            ],
            [
                'title' => 'Navigasi Darat: Kompas & Peta',
                'description' => 'Teknik dasar penggunaan kompas dan membaca peta topografi',
                'order' => 2,
                'scheduled_date' => $diklatsar->start_date->addDay(),
                'duration_minutes' => 240,
                'location' => 'Basecamp Mapala',
                'has_quiz' => true,
                'max_score' => 100,
                'status' => 'completed',
            ],
            [
                'title' => 'Survival & Bivak',
                'description' => 'Teknik bertahan hidup di alam bebas dan membuat bivak darurat',
                'order' => 3,
                'scheduled_date' => $diklatsar->start_date->addDays(2),
                'duration_minutes' => 300,
                'location' => 'Gunung Singgalang',
                'has_practical' => true,
                'max_score' => 100,
                'status' => 'completed',
            ],
            [
                'title' => 'P3K & Penanganan Kegawatdaruratan',
                'description' => 'Pertolongan pertama pada kecelakaan di alam bebas',
                'order' => 4,
                'scheduled_date' => $diklatsar->start_date->addDays(3),
                'duration_minutes' => 240,
                'location' => 'Basecamp Mapala',
                'has_practical' => true,
                'max_score' => 100,
                'status' => 'completed',
            ],
            [
                'title' => 'Praktik Lapangan & Ujian Navigasi',
                'description' => 'Ujian praktik navigasi dan survival di gunung',
                'order' => 5,
                'scheduled_date' => $diklatsar->end_date->subDay(),
                'duration_minutes' => 720,
                'location' => 'Gunung Singgalang',
                'has_practical' => true,
                'max_score' => 100,
                'status' => 'completed',
            ],
        ];

        foreach ($diklatsarSessions as $sessionData) {
            TrainingSession::create(array_merge($sessionData, [
                'training_program_id' => $diklatsar->id,
                'slug' => \Illuminate\Support\Str::slug($sessionData['title']),
            ]));
        }

        // Add participants to Diklatsar
        $diklatsarParticipants = $members->take(25);
        foreach ($diklatsarParticipants as $index => $member) {
            $isPassed = $index < 22; // 22 out of 25 passed
            $averageScore = $isPassed ? rand(75, 95) : rand(50, 70);

            TrainingParticipant::create([
                'training_program_id' => $diklatsar->id,
                'user_id' => $member->id,
                'registered_at' => $diklatsar->start_date->subWeeks(2),
                'status' => $isPassed ? 'passed' : 'failed',
                'total_score' => $averageScore * 5,
                'average_score' => $averageScore,
                'attendance_count' => $isPassed ? 5 : rand(3, 4),
                'total_sessions' => 5,
                'completed_at' => $diklatsar->end_date,
                'certificate_issued' => $isPassed,
                'certificate_issued_at' => $isPassed ? $diklatsar->end_date->addWeek() : null,
            ]);
        }

        // 2. Pelatihan Navigasi Lanjutan - Ongoing
        $navLanjutan = TrainingProgram::create([
            'name' => 'Pelatihan Navigasi Lanjutan',
            'slug' => 'pelatihan-navigasi-lanjutan',
            'description' => 'Pelatihan navigasi tingkat lanjut untuk member yang ingin mendalami teknik orienteering, GPS, dan navigasi ekstrim.',
            'cohort_id' => $cohort24->id,
            'program_type' => 'advanced',
            'level' => 'intermediate',
            'start_date' => now()->subWeeks(2),
            'end_date' => now()->addWeeks(2),
            'location' => 'Gunung Marapi & Gunung Kerinci',
            'location_details' => 'Praktik langsung di medan pegunungan dengan berbagai kondisi',
            'max_participants' => 20,
            'min_participants' => 10,
            'training_fee' => 500000,
            'status' => 'ongoing',
            'registration_status' => 'closed',
            'is_mandatory' => false,
            'coordinator_id' => $coordinators->skip(1)->first()->id ?? null,
            'instructors' => [
                ['name' => 'Rian Syahputra', 'expertise' => 'Navigasi Advanced & GPS'],
                ['name' => 'Budi Santoso', 'expertise' => 'Orienteering Competition'],
            ],
            'learning_objectives' => [
                ['objective' => 'Menguasai teknik triangulasi dan resection'],
                ['objective' => 'Mampu menggunakan GPS dan aplikasi navigasi digital'],
                ['objective' => 'Navigasi malam dan kondisi ekstrim'],
                ['objective' => 'Membuat peta jalur sendiri'],
            ],
            'requirements' => [
                ['requirement' => 'Sudah lulus Diklatsar'],
                ['requirement' => 'Menguasai navigasi dasar dengan baik'],
                ['requirement' => 'Memiliki peralatan navigasi sendiri'],
            ],
            'has_evaluation' => true,
            'passing_score' => 80,
            'evaluation_criteria' => [
                ['criteria' => 'Kehadiran', 'weight' => 15],
                ['criteria' => 'Ujian Navigasi Malam', 'weight' => 35],
                ['criteria' => 'Praktik GPS & Digital', 'weight' => 25],
                ['criteria' => 'Peta Jalur', 'weight' => 25],
            ],
        ]);

        // Sessions for Nav Lanjutan
        $navSessions = [
            [
                'title' => 'Triangulasi & Resection Advanced',
                'order' => 1,
                'scheduled_date' => $navLanjutan->start_date,
                'duration_minutes' => 240,
                'status' => 'completed',
            ],
            [
                'title' => 'GPS & Digital Navigation',
                'order' => 2,
                'scheduled_date' => $navLanjutan->start_date->addDays(3),
                'duration_minutes' => 180,
                'status' => 'completed',
            ],
            [
                'title' => 'Navigasi Malam',
                'order' => 3,
                'scheduled_date' => now()->subDays(3),
                'duration_minutes' => 360,
                'status' => 'ongoing',
            ],
            [
                'title' => 'Praktik Ekstrim: Kabut & Cuaca Buruk',
                'order' => 4,
                'scheduled_date' => now()->addDays(4),
                'duration_minutes' => 480,
                'status' => 'scheduled',
            ],
        ];

        foreach ($navSessions as $sessionData) {
            TrainingSession::create(array_merge($sessionData, [
                'training_program_id' => $navLanjutan->id,
                'slug' => \Illuminate\Support\Str::slug($sessionData['title']),
                'location' => $navLanjutan->location,
                'has_practical' => true,
                'max_score' => 100,
            ]));
        }

        // Add participants
        foreach ($members->take(15) as $member) {
            TrainingParticipant::create([
                'training_program_id' => $navLanjutan->id,
                'user_id' => $member->id,
                'registered_at' => $navLanjutan->start_date->subWeeks(3),
                'status' => 'attending',
                'attendance_count' => rand(2, 3),
                'total_sessions' => 4,
                'average_score' => rand(75, 90),
            ]);
        }

        // 3. Pelatihan SAR Dasar - Scheduled
        $sarDasar = TrainingProgram::create([
            'name' => 'Pelatihan SAR Dasar',
            'slug' => 'pelatihan-sar-dasar',
            'description' => 'Pelatihan Search and Rescue tingkat dasar untuk meningkatkan kemampuan penyelamatan di gunung dan alam bebas.',
            'cohort_id' => null, // Open for all cohorts
            'program_type' => 'specialized',
            'level' => 'intermediate',
            'start_date' => now()->addWeeks(3),
            'end_date' => now()->addWeeks(5),
            'location' => 'Basecamp & Gunung Singgalang',
            'max_participants' => 25,
            'min_participants' => 12,
            'training_fee' => 450000,
            'status' => 'scheduled',
            'registration_status' => 'open',
            'is_mandatory' => false,
            'coordinator_id' => $coordinators->last()->id ?? null,
            'instructors' => [
                ['name' => 'Ahmad Fauzi', 'expertise' => 'SAR & Rope Technique'],
                ['name' => 'Rahmat Hidayat', 'expertise' => 'Emergency Response'],
            ],
            'learning_objectives' => [
                ['objective' => 'Memahami prinsip SAR dan manajemen bencana'],
                ['objective' => 'Menguasai teknik pencarian dan tracking'],
                ['objective' => 'Menguasai teknik evakuasi korban'],
                ['objective' => 'Mampu berkomunikasi dalam operasi SAR'],
            ],
            'requirements' => [
                ['requirement' => 'Member aktif Mapala'],
                ['requirement' => 'Sehat jasmani dan rohani'],
                ['requirement' => 'Sudah lulus Diklatsar'],
                ['requirement' => 'Memiliki stamina yang baik'],
            ],
            'has_evaluation' => true,
            'passing_score' => 75,
            'evaluation_criteria' => [
                ['criteria' => 'Kehadiran', 'weight' => 20],
                ['criteria' => 'Ujian Tertulis', 'weight' => 25],
                ['criteria' => 'Praktik Pencarian', 'weight' => 25],
                ['criteria' => 'Praktik Evakuasi', 'weight' => 30],
            ],
        ]);

        // Sessions for SAR (scheduled)
        $sarSessions = [
            [
                'title' => 'Pengenalan SAR & Manajemen Bencana',
                'order' => 1,
                'scheduled_date' => $sarDasar->start_date,
                'duration_minutes' => 180,
            ],
            [
                'title' => 'Teknik Pencarian & Tracking',
                'order' => 2,
                'scheduled_date' => $sarDasar->start_date->addDays(3),
                'duration_minutes' => 240,
            ],
            [
                'title' => 'Rope Technique & Vertical Rescue',
                'order' => 3,
                'scheduled_date' => $sarDasar->start_date->addWeek(),
                'duration_minutes' => 300,
            ],
            [
                'title' => 'Evakuasi Korban & Medical Emergency',
                'order' => 4,
                'scheduled_date' => $sarDasar->start_date->addWeeks(2),
                'duration_minutes' => 240,
            ],
            [
                'title' => 'Simulasi Operasi SAR Terpadu',
                'order' => 5,
                'scheduled_date' => $sarDasar->end_date,
                'duration_minutes' => 480,
            ],
        ];

        foreach ($sarSessions as $sessionData) {
            TrainingSession::create(array_merge($sessionData, [
                'training_program_id' => $sarDasar->id,
                'slug' => \Illuminate\Support\Str::slug($sessionData['title']),
                'location' => $sarDasar->location,
                'has_practical' => true,
                'max_score' => 100,
                'status' => 'scheduled',
            ]));
        }

        // Add some registered participants
        foreach ($members->take(18) as $member) {
            TrainingParticipant::create([
                'training_program_id' => $sarDasar->id,
                'user_id' => $member->id,
                'registered_at' => now()->subWeeks(2),
                'status' => rand(0, 1) ? 'confirmed' : 'registered',
            ]);
        }

        // 4. Kader Muda Leadership Training - Draft
        $leadership = TrainingProgram::create([
            'name' => 'Kader Muda Leadership Training',
            'slug' => 'kader-muda-leadership-training',
            'description' => 'Program pelatihan kepemimpinan khusus untuk kaderisasi pengurus dan BKP Mapala Pagaruyung.',
            'cohort_id' => $cohort25->id,
            'program_type' => 'specialized',
            'level' => 'advanced',
            'start_date' => now()->addMonths(2),
            'end_date' => now()->addMonths(3),
            'location' => 'Basecamp Mapala',
            'max_participants' => 15,
            'min_participants' => 8,
            'training_fee' => 0, // Free for selected cadres
            'status' => 'draft',
            'registration_status' => 'closed',
            'is_mandatory' => false,
            'coordinator_id' => $coordinators->first()->id ?? null,
            'learning_objectives' => [
                ['objective' => 'Mengembangkan jiwa kepemimpinan dan manajerial'],
                ['objective' => 'Memahami manajemen organisasi pecinta alam'],
                ['objective' => 'Mampu merancang dan mengelola program'],
                ['objective' => 'Membangun networking dan kerjasama'],
            ],
            'requirements' => [
                ['requirement' => 'Rekomendasi dari pengurus atau BKP'],
                ['requirement' => 'Sudah menjadi member minimal 1 tahun'],
                ['requirement' => 'Aktif dalam kegiatan organisasi'],
            ],
            'has_evaluation' => true,
            'passing_score' => 80,
            'notes' => 'Program ini khusus untuk persiapan kader pengurus periode berikutnya',
        ]);

        $this->command->info('✅ Training programs seeded successfully!');
        $this->command->info("   - {$diklatsar->name} ({$diklatsar->status})");
        $this->command->info("   - {$navLanjutan->name} ({$navLanjutan->status})");
        $this->command->info("   - {$sarDasar->name} ({$sarDasar->status})");
        $this->command->info("   - {$leadership->name} ({$leadership->status})");
    }
}
