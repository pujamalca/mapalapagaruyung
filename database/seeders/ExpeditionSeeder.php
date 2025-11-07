<?php

namespace Database\Seeders;

use App\Models\Expedition;
use App\Models\ExpeditionParticipant;
use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpeditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏔️ Seeding Expeditions...');

        // Get divisions
        $divisions = Division::all();
        if ($divisions->isEmpty()) {
            $divisions = collect([null]); // No division
        }

        // Get members
        $members = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['member', 'active_member']);
        })->get();

        if ($members->isEmpty()) {
            $members = User::limit(20)->get();
        }

        // 1. Pendakian Gunung Kerinci - Completed
        $kerinci = Expedition::create([
            'title' => 'Pendakian Gunung Kerinci',
            'slug' => 'pendakian-gunung-kerinci',
            'description' => 'Pendakian ke puncak tertinggi di Sumatera, Gunung Kerinci (3805 mdpl). Ekspedisi resmi Mapala Pagaruyung dengan jalur pendakian via Kersik Tuo.',
            'destination' => 'Gunung Kerinci',
            'location' => 'Jambi, Sumatera',
            'route_description' => 'Basecamp Kersik Tuo → Shelter 1 → Shelter 2 → Shelter 3 → Puncak Gunung Kerinci (3805 mdpl)',
            'checkpoints' => [
                ['name' => 'Basecamp Kersik Tuo (1400 mdpl)'],
                ['name' => 'Shelter 1 (2000 mdpl)'],
                ['name' => 'Shelter 2 (2400 mdpl)'],
                ['name' => 'Shelter 3 (3200 mdpl)'],
                ['name' => 'Puncak Gunung Kerinci (3805 mdpl)'],
            ],
            'distance_km' => 14,
            'elevation_gain_m' => 2405,
            'difficulty_level' => 'hard',
            'start_date' => now()->subMonths(2),
            'end_date' => now()->subMonths(2)->addDays(3),
            'duration_days' => 4,
            'leader_id' => $members->first()->id ?? null,
            'max_participants' => 12,
            'min_participants' => 6,
            'status' => 'completed',
            'registration_status' => 'closed',
            'registration_deadline' => now()->subMonths(2)->subWeeks(2),
            'estimated_cost_per_person' => 850000,
            'cost_breakdown' => "Transportasi Padang-Jambi: Rp 300.000\nIzin pendakian: Rp 150.000\nKonsumsi 4 hari: Rp 250.000\nPorter: Rp 100.000\nLain-lain: Rp 50.000",
            'requirements' => [
                ['requirement' => 'Sehat jasmani dan rohani'],
                ['requirement' => 'Pengalaman mendaki minimal 2x'],
                ['requirement' => 'Stamina sangat baik'],
                ['requirement' => 'Lulus tes fisik'],
            ],
            'equipment_list' => [
                ['equipment' => 'Carrier 60-80L'],
                ['equipment' => 'Sleeping bag suhu 0°C'],
                ['equipment' => 'Tenda dome 2-3 orang'],
                ['equipment' => 'Kompas dan GPS'],
                ['equipment' => 'Headlamp'],
                ['equipment' => 'Jaket gunung windproof'],
            ],
            'emergency_contacts' => [
                ['name' => 'Pos SAR Kerinci', 'phone' => '0811-XXXX-XXX'],
                ['name' => 'Basecamp Kersik Tuo', 'phone' => '0812-XXXX-XXX'],
            ],
            'expedition_type' => 'hiking',
            'division_id' => $divisions->first()->id ?? null,
            'is_official' => true,
            'requires_approval' => true,
            'best_season' => 'Mei - September',
            'weather_notes' => 'Cuaca cerah di pagi hari, berkabut sore. Angin kencang di puncak.',
            'trip_report' => '<p>Ekspedisi berjalan lancar. Tim berhasil mencapai puncak pada hari ke-3 pukul 05.30 WIB. Cuaca sangat mendukung dengan visibility sempurna. Semua anggota tim dalam kondisi sehat dan fit.</p>',
            'highlights' => [
                ['highlight' => 'Berhasil summit semua anggota'],
                ['highlight' => 'Sunrise di puncak sangat indah'],
                ['highlight' => 'Koordinasi tim sangat baik'],
            ],
            'challenges' => [
                ['challenge' => 'Angin kencang di puncak'],
                ['challenge' => 'Jalur terjal di Shelter 2-3'],
            ],
            'lessons_learned' => [
                ['lesson' => 'Persiapan fisik sangat penting'],
                ['lesson' => 'Koordinasi dan komunikasi kunci keberhasilan'],
            ],
            'completed_at' => now()->subMonths(2)->addDays(3),
        ]);

        // Add participants to Kerinci (12 participants, all completed)
        foreach ($members->take(12) as $index => $member) {
            ExpeditionParticipant::create([
                'expedition_id' => $kerinci->id,
                'user_id' => $member->id,
                'registered_at' => $kerinci->start_date->subWeeks(3),
                'status' => 'completed',
                'role' => match ($index) {
                    0 => 'Navigator',
                    1 => 'Medic',
                    2 => 'Cook',
                    3 => 'Documentation',
                    default => null,
                },
                'is_leader' => $index === 0,
                'health_declaration' => 'Sehat, tidak ada riwayat penyakit',
                'fitness_verified' => true,
                'equipment_verified' => true,
                'payment_verified' => true,
                'payment_amount' => 850000,
                'performance_rating' => rand(4, 5),
                'performance_notes' => 'Performa sangat baik, teamwork solid',
            ]);
        }

        // 2. Ekspedisi Gunung Singgalang - Ongoing
        $singgalang = Expedition::create([
            'title' => 'Training Ekspedisi Gunung Singgalang',
            'slug' => 'training-ekspedisi-gunung-singgalang',
            'description' => 'Ekspedisi training untuk calon anggota baru. Pendakian Gunung Singgalang (2877 mdpl) via Pandai Sikek dengan fokus pada pembelajaran teknik survival dan navigasi.',
            'destination' => 'Gunung Singgalang',
            'location' => 'Tanah Datar, Sumatera Barat',
            'route_description' => 'Pandai Sikek → Batu Bajanjang → Puncak Singgalang',
            'checkpoints' => [
                ['name' => 'Pandai Sikek (900 mdpl)'],
                ['name' => 'Batu Bajanjang (2000 mdpl)'],
                ['name' => 'Puncak Singgalang (2877 mdpl)'],
            ],
            'distance_km' => 8,
            'elevation_gain_m' => 1977,
            'difficulty_level' => 'moderate',
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDay(),
            'duration_days' => 3,
            'leader_id' => $members->skip(1)->first()->id ?? null,
            'max_participants' => 15,
            'min_participants' => 8,
            'status' => 'ongoing',
            'registration_status' => 'closed',
            'estimated_cost_per_person' => 350000,
            'cost_breakdown' => "Transportasi: Rp 100.000\nKonsumsi: Rp 150.000\nPemandu lokal: Rp 50.000\nLain-lain: Rp 50.000",
            'requirements' => [
                ['requirement' => 'Calon anggota baru yang lulus seleksi'],
                ['requirement' => 'Sehat jasmani'],
                ['requirement' => 'Membawa peralatan lengkap'],
            ],
            'equipment_list' => [
                ['equipment' => 'Carrier 50-60L'],
                ['equipment' => 'Sleeping bag'],
                ['equipment' => 'Kompas'],
                ['equipment' => 'P3K pribadi'],
            ],
            'emergency_contacts' => [
                ['name' => 'Basecamp Mapala', 'phone' => '0812-XXXX-XXX'],
                ['name' => 'Pemandu Lokal', 'phone' => '0813-XXXX-XXX'],
            ],
            'expedition_type' => 'hiking',
            'division_id' => $divisions->first()->id ?? null,
            'is_official' => true,
            'best_season' => 'April - Oktober',
        ]);

        // Add participants (15 participating)
        foreach ($members->skip(12)->take(15) as $index => $member) {
            ExpeditionParticipant::create([
                'expedition_id' => $singgalang->id,
                'user_id' => $member->id,
                'registered_at' => $singgalang->start_date->subWeeks(2),
                'status' => 'participating',
                'role' => match ($index) {
                    0 => 'Navigator',
                    1 => 'Medic',
                    default => null,
                },
                'is_leader' => $index === 0,
                'fitness_verified' => true,
                'equipment_verified' => true,
                'payment_verified' => true,
                'payment_amount' => 350000,
            ]);
        }

        // 3. Ekspedisi Gua Ngalau Indah - Preparing
        $gua = Expedition::create([
            'title' => 'Ekspedisi Penelusuran Gua Ngalau Indah',
            'slug' => 'ekspedisi-penelusuran-gua-ngalau-indah',
            'description' => 'Penelusuran gua horizontal dengan formasi stalaktit dan stalakmit yang indah. Ekspedisi ini juga melibatkan dokumentasi dan pemetaan gua.',
            'destination' => 'Gua Ngalau Indah',
            'location' => 'Payakumbuh, Sumatera Barat',
            'route_description' => 'Pintu masuk → Ruang utama → Lorong sempit → Danau bawah tanah → Ruang stalaktit',
            'checkpoints' => [
                ['name' => 'Pintu Masuk'],
                ['name' => 'Ruang Utama'],
                ['name' => 'Lorong Sempit'],
                ['name' => 'Danau Bawah Tanah'],
                ['name' => 'Ruang Stalaktit'],
            ],
            'distance_km' => 2.5,
            'difficulty_level' => 'moderate',
            'start_date' => now()->addWeeks(2),
            'end_date' => now()->addWeeks(2)->addDays(1),
            'duration_days' => 2,
            'leader_id' => $members->skip(2)->first()->id ?? null,
            'max_participants' => 10,
            'min_participants' => 5,
            'status' => 'preparing',
            'registration_status' => 'open',
            'registration_deadline' => now()->addWeeks(1),
            'estimated_cost_per_person' => 250000,
            'cost_breakdown' => "Transportasi: Rp 80.000\nPemandu gua: Rp 100.000\nKonsumsi: Rp 50.000\nLain-lain: Rp 20.000",
            'requirements' => [
                ['requirement' => 'Tidak claustrophobia'],
                ['requirement' => 'Bisa berenang (ada bagian berair)'],
                ['requirement' => 'Fisik sehat'],
            ],
            'equipment_list' => [
                ['equipment' => 'Headlamp + cadangan baterai'],
                ['equipment' => 'Helmet caving'],
                ['equipment' => 'Sarung tangan'],
                ['equipment' => 'Sepatu tracking anti slip'],
                ['equipment' => 'Tas waterproof'],
            ],
            'emergency_contacts' => [
                ['name' => 'Tim SAR Payakumbuh', 'phone' => '0811-XXXX-XXX'],
            ],
            'expedition_type' => 'caving',
            'division_id' => $divisions->skip(1)->first()->id ?? null,
            'is_official' => true,
        ]);

        // Add participants (8 confirmed, 2 approved)
        foreach ($members->take(10) as $index => $member) {
            ExpeditionParticipant::create([
                'expedition_id' => $gua->id,
                'user_id' => $member->id,
                'registered_at' => now()->subWeek(),
                'status' => $index < 8 ? 'confirmed' : 'approved',
                'fitness_verified' => $index < 8,
                'equipment_verified' => $index < 8,
                'payment_verified' => $index < 6,
                'payment_amount' => 250000,
            ]);
        }

        // 4. Ekspedisi Konservasi Hutan Mangrove - Planned
        $mangrove = Expedition::create([
            'title' => 'Ekspedisi Konservasi Hutan Mangrove Bungus',
            'slug' => 'ekspedisi-konservasi-hutan-mangrove-bungus',
            'description' => 'Program konservasi dan penanaman mangrove di kawasan pesisir Bungus. Melibatkan penanaman 1000 bibit mangrove dan edukasi masyarakat lokal.',
            'destination' => 'Hutan Mangrove Bungus',
            'location' => 'Bungus, Padang',
            'route_description' => 'Basecamp → Area penanaman zona A → Area penanaman zona B → Survey kawasan',
            'distance_km' => 5,
            'difficulty_level' => 'easy',
            'start_date' => now()->addMonth(),
            'end_date' => now()->addMonth()->addDays(2),
            'duration_days' => 3,
            'leader_id' => $members->skip(3)->first()->id ?? null,
            'max_participants' => 20,
            'min_participants' => 10,
            'status' => 'planned',
            'registration_status' => 'open',
            'registration_deadline' => now()->addWeeks(3),
            'estimated_cost_per_person' => 200000,
            'cost_breakdown' => "Transportasi: Rp 50.000\nBibit mangrove: Rp 50.000\nKonsumsi: Rp 80.000\nLain-lain: Rp 20.000",
            'requirements' => [
                ['requirement' => 'Peduli lingkungan'],
                ['requirement' => 'Siap bekerja di area berlumpur'],
                ['requirement' => 'Bisa berenang (opsional)'],
            ],
            'equipment_list' => [
                ['equipment' => 'Sepatu boot'],
                ['equipment' => 'Sarung tangan kerja'],
                ['equipment' => 'Topi lapangan'],
                ['equipment' => 'Pakaian yang bisa kotor'],
            ],
            'emergency_contacts' => [
                ['name' => 'Dinas Lingkungan Hidup', 'phone' => '0751-XXXX'],
            ],
            'expedition_type' => 'conservation',
            'is_official' => true,
        ]);

        // Add participants (5 registered)
        foreach ($members->skip(15)->take(5) as $member) {
            ExpeditionParticipant::create([
                'expedition_id' => $mangrove->id,
                'user_id' => $member->id,
                'registered_at' => now()->subDays(3),
                'status' => 'registered',
            ]);
        }

        $this->command->info('✅ Expeditions seeded successfully!');
        $this->command->info("   - {$kerinci->title} ({$kerinci->status})");
        $this->command->info("   - {$singgalang->title} ({$singgalang->status})");
        $this->command->info("   - {$gua->title} ({$gua->status})");
        $this->command->info("   - {$mangrove->title} ({$mangrove->status})");
    }
}
