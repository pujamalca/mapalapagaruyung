<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionParticipant;
use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏆 Seeding Competitions...');

        $divisions = Division::all();
        $members = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['member', 'active_member']))->get();

        if ($members->isEmpty()) {
            $members = User::limit(15)->get();
        }

        // 1. Kejuaraan Panjat Tebing Nasional - Completed
        $climbing = Competition::create([
            'title' => 'Kejuaraan Panjat Tebing Nasional 2024',
            'slug' => 'kejuaraan-panjat-tebing-nasional-2024',
            'description' => 'Kompetisi panjat tebing tingkat nasional diselenggarakan oleh FPTI dengan kategori speed climbing dan lead climbing.',
            'event_type' => 'competition',
            'competition_type' => 'national',
            'sport_category' => 'Sport Climbing',
            'participation_type' => 'individual',
            'organizer' => 'FPTI (Federasi Panjat Tebing Indonesia)',
            'location' => 'Jakarta',
            'venue_details' => 'GOR Sarana Olahraga Jakarta',
            'start_date' => now()->subMonths(1),
            'end_date' => now()->subMonths(1)->addDays(2),
            'duration_days' => 3,
            'registration_fee' => 500000,
            'fee_covered_by_mapala' => true,
            'categories' => [
                ['name' => 'Speed Climbing Putra'],
                ['name' => 'Speed Climbing Putri'],
                ['name' => 'Lead Climbing Putra'],
                ['name' => 'Lead Climbing Putri'],
            ],
            'prizes' => [
                ['position' => 'Juara 1', 'prize' => 'Trophy + Uang Tunai', 'amount' => 5000000],
                ['position' => 'Juara 2', 'prize' => 'Trophy + Uang Tunai', 'amount' => 3000000],
                ['position' => 'Juara 3', 'prize' => 'Trophy + Uang Tunai', 'amount' => 2000000],
            ],
            'status' => 'completed',
            'registration_status' => 'closed',
            'coordinator_id' => $members->first()->id ?? null,
            'is_official_event' => false,
            'division_id' => $divisions->first()->id ?? null,
            'completed_at' => now()->subMonths(1)->addDays(2),
            'achievements_summary' => [
                ['achievement' => 'Juara 1 Speed Climbing Putra', 'winner' => 'Ahmad Fauzi'],
                ['achievement' => 'Juara 2 Lead Climbing Putra', 'winner' => 'Budi Santoso'],
            ],
        ]);

        // Add participants with results
        foreach ($members->take(8) as $index => $member) {
            $position = $index + 1;
            $medal = match(true) {
                $position === 1 => 'gold',
                $position === 2 => 'silver',
                $position === 3 => 'bronze',
                default => null,
            };

            CompetitionParticipant::create([
                'competition_id' => $climbing->id,
                'user_id' => $member->id,
                'registered_at' => $climbing->start_date->subWeeks(3),
                'status' => 'completed',
                'category' => $index < 4 ? 'Speed Climbing Putra' : 'Lead Climbing Putra',
                'bib_number' => 'BIB' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'rank' => $position <= 3 ? "Juara $position" : "Posisi $position",
                'position' => $position,
                'score' => rand(70, 95),
                'time_record' => $index < 4 ? '00:0' . rand(5, 9) . ':' . rand(10, 59) : null,
                'medal_type' => $medal,
                'certificate_issued' => true,
                'certificate_number' => 'CERT-2024-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'certificate_issued_at' => $climbing->completed_at,
                'fee_verified' => true,
            ]);
        }

        // 2. Workshop Navigasi Lanjutan - Ongoing
        $workshop = Competition::create([
            'title' => 'Workshop Navigasi & Orienteering Lanjutan',
            'slug' => 'workshop-navigasi-orienteering-lanjutan',
            'description' => 'Workshop intensif tentang teknik navigasi lanjutan dan orienteering untuk member Mapala se-Sumatera.',
            'event_type' => 'workshop',
            'competition_type' => 'regional',
            'sport_category' => 'Orienteering',
            'participation_type' => 'individual',
            'organizer' => 'Mapala Pagaruyung',
            'location' => 'Padang',
            'venue_details' => 'Basecamp Mapala Pagaruyung',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'duration_days' => 3,
            'registration_fee' => 150000,
            'status' => 'ongoing',
            'registration_status' => 'closed',
            'coordinator_id' => $members->skip(1)->first()->id ?? null,
            'is_official_event' => true,
            'division_id' => $divisions->first()->id ?? null,
        ]);

        // Add participants
        foreach ($members->skip(8)->take(12) as $member) {
            CompetitionParticipant::create([
                'competition_id' => $workshop->id,
                'user_id' => $member->id,
                'registered_at' => $workshop->start_date->subWeeks(2),
                'status' => 'participating',
                'fee_verified' => true,
            ]);
        }

        // 3. Gathering Mapala Nusantara - Planned
        $gathering = Competition::create([
            'title' => 'Gathering Mapala Nusantara 2025',
            'slug' => 'gathering-mapala-nusantara-2025',
            'description' => 'Pertemuan akbar Mapala se-Indonesia dengan berbagai kegiatan, lomba, dan diskusi.',
            'event_type' => 'gathering',
            'competition_type' => 'national',
            'participation_type' => 'team',
            'organizer' => 'MAPALA Indonesia',
            'location' => 'Yogyakarta',
            'start_date' => now()->addMonths(2),
            'end_date' => now()->addMonths(2)->addDays(4),
            'duration_days' => 5,
            'registration_open' => now(),
            'registration_close' => now()->addMonth(),
            'registration_fee' => 300000,
            'max_participants' => 50,
            'status' => 'planned',
            'registration_status' => 'open',
            'is_official_event' => false,
        ]);

        // Add some registered participants
        foreach ($members->take(6) as $member) {
            CompetitionParticipant::create([
                'competition_id' => $gathering->id,
                'user_id' => $member->id,
                'registered_at' => now()->subDays(3),
                'status' => 'registered',
            ]);
        }

        $this->command->info('✅ Competitions seeded successfully!');
        $this->command->info("   - {$climbing->title} ({$climbing->status})");
        $this->command->info("   - {$workshop->title} ({$workshop->status})");
        $this->command->info("   - {$gathering->title} ({$gathering->status})");
    }
}
