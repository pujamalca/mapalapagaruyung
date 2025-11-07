<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Gunung & Rimba',
                'description' => 'Divisi yang fokus pada kegiatan pendakian gunung dan eksplorasi hutan. Mengelola kegiatan pendakian, navigasi darat, survival, dan penelusuran area rimba.',
                'icon' => '🏔️',
                'color' => '#10B981',
                'work_program' => '<ul><li>Pendakian rutin setiap bulan</li><li>Pelatihan navigasi dan pemetaan</li><li>Workshop survival skills</li><li>Ekspedisi tahunan ke gunung-gunung besar</li></ul>',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Panjat Tebing',
                'description' => 'Divisi yang mengelola kegiatan rock climbing, wall climbing, dan vertical rescue. Bertanggung jawab atas pelatihan teknik pemanjatan dan keselamatan vertikal.',
                'icon' => '🧗',
                'color' => '#F59E0B',
                'work_program' => '<ul><li>Latihan rutin panjat tebing indoor dan outdoor</li><li>Pelatihan vertical rescue</li><li>Pemeliharaan peralatan climbing</li><li>Kompetisi climbing internal</li></ul>',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Penelusuran Gua',
                'description' => 'Divisi yang fokus pada eksplorasi dan penelusuran gua (caving/speleology). Melakukan kegiatan pemetaan gua, dokumentasi, dan konservasi ekosistem gua.',
                'icon' => '🕳️',
                'color' => '#6B7280',
                'work_program' => '<ul><li>Eksplorasi gua-gua di Sumatera Barat</li><li>Pelatihan teknik caving</li><li>Pemetaan dan dokumentasi gua</li><li>Edukasi konservasi ekosistem gua</li></ul>',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Arung Jeram',
                'description' => 'Divisi yang mengelola kegiatan rafting, kayaking, dan river crossing. Bertanggung jawab atas keselamatan dan teknik penyelamatan di sungai.',
                'icon' => '🚣',
                'color' => '#3B82F6',
                'work_program' => '<ul><li>Rafting rutin di sungai-sungai Sumatera Barat</li><li>Pelatihan swift water rescue</li><li>Pemeliharaan perahu dan peralatan</li><li>Ekspedisi sungai-sungai besar</li></ul>',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Konservasi Alam',
                'description' => 'Divisi yang fokus pada pelestarian lingkungan, edukasi konservasi, dan kegiatan sosial berbasis lingkungan. Mengelola program reboisasi dan clean up.',
                'icon' => '🌿',
                'color' => '#059669',
                'work_program' => '<ul><li>Program reboisasi dan penanaman pohon</li><li>Clean up mountain dan sungai</li><li>Edukasi lingkungan ke masyarakat</li><li>Kampanye zero waste</li></ul>',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'SAR & P3K',
                'description' => 'Divisi yang mengelola bidang Search and Rescue serta Pertolongan Pertama Pada Kecelakaan. Bertanggung jawab atas pelatihan medis dan operasi penyelamatan.',
                'icon' => '🚑',
                'color' => '#EF4444',
                'work_program' => '<ul><li>Pelatihan P3K dan first responder</li><li>Simulasi SAR dan evakuasi</li><li>Pemeliharaan peralatan medis</li><li>Koordinasi dengan BASARNAS</li></ul>',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Dokumentasi & Media',
                'description' => 'Divisi yang mengelola dokumentasi kegiatan, publikasi, dan media sosial organisasi. Bertanggung jawab atas konten visual dan narasi organisasi.',
                'icon' => '📸',
                'color' => '#8B5CF6',
                'work_program' => '<ul><li>Dokumentasi foto dan video kegiatan</li><li>Pengelolaan media sosial</li><li>Pembuatan konten edukatif</li><li>Publikasi laporan ekspedisi</li></ul>',
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($divisions as $divisionData) {
            $division = Division::create($divisionData);

            $this->command->info("Created division: {$division->name}");
        }

        $this->command->info('Division seeder completed successfully!');
    }
}
