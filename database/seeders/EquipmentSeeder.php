<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        // Create Equipment Categories
        $categories = [
            [
                'name' => 'Tenda & Shelter',
                'description' => 'Tenda, flysheet, dan perlengkapan shelter',
                'icon' => 'heroicon-o-home',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Carrier & Tas',
                'description' => 'Carrier, tas gunung, dan tas pendakian',
                'icon' => 'heroicon-o-backpack',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Sleeping System',
                'description' => 'Sleeping bag, matras, dan perlengkapan tidur',
                'icon' => 'heroicon-o-moon',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Navigasi',
                'description' => 'Kompas, GPS, peta, dan alat navigasi',
                'icon' => 'heroicon-o-map',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Peralatan Masak',
                'description' => 'Kompor, nesting, dan perlengkapan masak',
                'icon' => 'heroicon-o-fire',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Panjat Tebing',
                'description' => 'Harness, carabiner, rope, dan perlengkapan climbing',
                'icon' => 'heroicon-o-bolt',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Penerangan',
                'description' => 'Headlamp, senter, dan perlengkapan penerangan',
                'icon' => 'heroicon-o-light-bulb',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Keselamatan & P3K',
                'description' => 'P3K, rescue equipment, dan perlengkapan keselamatan',
                'icon' => 'heroicon-o-shield-check',
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            EquipmentCategory::create($category);
        }

        // Get categories
        $tendaCategory = EquipmentCategory::where('name', 'Tenda & Shelter')->first();
        $carrierCategory = EquipmentCategory::where('name', 'Carrier & Tas')->first();
        $sleepingCategory = EquipmentCategory::where('name', 'Sleeping System')->first();
        $navigasiCategory = EquipmentCategory::where('name', 'Navigasi')->first();
        $masakCategory = EquipmentCategory::where('name', 'Peralatan Masak')->first();
        $climbingCategory = EquipmentCategory::where('name', 'Panjat Tebing')->first();
        $peneranganCategory = EquipmentCategory::where('name', 'Penerangan')->first();
        $keselamatanCategory = EquipmentCategory::where('name', 'Keselamatan & P3K')->first();

        // Create Equipment
        $equipment = [
            // Tenda & Shelter
            [
                'code' => 'TND-001',
                'equipment_category_id' => $tendaCategory->id,
                'name' => 'Tenda Kapasitas 4 Orang',
                'brand' => 'Consina',
                'model' => 'Magnum 4',
                'description' => 'Tenda double layer kapasitas 4 orang dengan flysheet waterproof',
                'quantity' => 8,
                'quantity_available' => 6,
                'unit' => 'unit',
                'condition' => 'good',
                'status' => 'available',
                'storage_location' => 'Gudang A, Rak 1',
                'purchase_date' => now()->subYears(2),
                'purchase_price' => 1500000,
                'specifications' => [
                    'Kapasitas' => '4 orang',
                    'Material' => 'Polyester 190T',
                    'Berat' => '3.5 kg',
                    'Dimensi' => '240 x 210 x 130 cm',
                ],
                'last_maintenance_date' => now()->subMonths(3),
                'next_maintenance_date' => now()->addMonths(3),
                'maintenance_interval_days' => 180,
            ],
            [
                'code' => 'TND-002',
                'equipment_category_id' => $tendaCategory->id,
                'name' => 'Tenda Kapasitas 2 Orang',
                'brand' => 'Eiger',
                'model' => 'E167',
                'description' => 'Tenda ringan untuk 2 orang, cocok untuk pendakian ringan',
                'quantity' => 12,
                'quantity_available' => 10,
                'unit' => 'unit',
                'condition' => 'excellent',
                'status' => 'available',
                'storage_location' => 'Gudang A, Rak 1',
                'purchase_date' => now()->subMonths(6),
                'purchase_price' => 850000,
                'specifications' => [
                    'Kapasitas' => '2 orang',
                    'Material' => 'Polyester 210T',
                    'Berat' => '2.2 kg',
                    'Dimensi' => '210 x 150 x 110 cm',
                ],
                'next_maintenance_date' => now()->addMonths(4),
            ],
            [
                'code' => 'FLY-001',
                'equipment_category_id' => $tendaCategory->id,
                'name' => 'Flysheet 4x5 meter',
                'brand' => 'Rei',
                'model' => 'Flysheet Plus',
                'description' => 'Flysheet besar untuk shelter kelompok',
                'quantity' => 5,
                'quantity_available' => 4,
                'unit' => 'unit',
                'condition' => 'good',
                'status' => 'available',
                'storage_location' => 'Gudang A, Rak 2',
                'purchase_price' => 450000,
                'specifications' => [
                    'Dimensi' => '4 x 5 meter',
                    'Material' => 'Tarpaulin PE',
                    'Berat' => '1.8 kg',
                ],
            ],

            // Carrier & Tas
            [
                'code' => 'CAR-001',
                'equipment_category_id' => $carrierCategory->id,
                'name' => 'Carrier 60 Liter',
                'brand' => 'Deuter',
                'model' => 'Aircontact 65+10',
                'description' => 'Carrier besar dengan sistem ventilasi punggung',
                'quantity' => 15,
                'quantity_available' => 12,
                'unit' => 'unit',
                'condition' => 'good',
                'status' => 'available',
                'storage_location' => 'Gudang A, Rak 3',
                'purchase_date' => now()->subYears(1),
                'purchase_price' => 2500000,
                'specifications' => [
                    'Kapasitas' => '65+10 Liter',
                    'Berat' => '2.8 kg',
                    'Material' => 'Nylon Ripstop',
                    'Rain Cover' => 'Included',
                ],
                'last_maintenance_date' => now()->subMonths(2),
                'next_maintenance_date' => now()->addMonths(4),
            ],
            [
                'code' => 'CAR-002',
                'equipment_category_id' => $carrierCategory->id,
                'name' => 'Carrier 50 Liter',
                'brand' => 'Consina',
                'model' => 'Alpinist 50+5',
                'description' => 'Carrier sedang untuk pendakian 2-3 hari',
                'quantity' => 20,
                'quantity_available' => 18,
                'unit' => 'unit',
                'condition' => 'excellent',
                'status' => 'available',
                'storage_location' => 'Gudang A, Rak 3',
                'purchase_date' => now()->subMonths(8),
                'purchase_price' => 1200000,
                'specifications' => [
                    'Kapasitas' => '50+5 Liter',
                    'Berat' => '2.2 kg',
                    'Material' => 'Polyester 600D',
                ],
            ],

            // Sleeping System
            [
                'code' => 'SB-001',
                'equipment_category_id' => $sleepingCategory->id,
                'name' => 'Sleeping Bag Suhu Dingin',
                'brand' => 'Mountain Hardwear',
                'model' => 'Lamina -15°C',
                'description' => 'Sleeping bag untuk cuaca dingin hingga -15°C',
                'quantity' => 18,
                'quantity_available' => 16,
                'unit' => 'unit',
                'condition' => 'good',
                'status' => 'available',
                'storage_location' => 'Gudang B, Rak 1',
                'purchase_price' => 1800000,
                'specifications' => [
                    'Temperature Rating' => '-15°C',
                    'Material' => 'Synthetic Insulation',
                    'Berat' => '1.6 kg',
                    'Panjang' => '220 cm',
                ],
            ],
            [
                'code' => 'MAT-001',
                'equipment_category_id' => $sleepingCategory->id,
                'name' => 'Matras Lipat Foam',
                'brand' => 'Naturehike',
                'model' => 'Folding Mat',
                'description' => 'Matras foam lipat untuk isolasi dari tanah',
                'quantity' => 25,
                'quantity_available' => 22,
                'unit' => 'unit',
                'condition' => 'excellent',
                'status' => 'available',
                'storage_location' => 'Gudang B, Rak 2',
                'purchase_price' => 150000,
                'specifications' => [
                    'Material' => 'EVA Foam',
                    'Ketebalan' => '1.5 cm',
                    'Dimensi' => '180 x 50 cm',
                    'Berat' => '250 gram',
                ],
            ],

            // Navigasi
            [
                'code' => 'KOM-001',
                'equipment_category_id' => $navigasiCategory->id,
                'name' => 'Kompas Orienteering',
                'brand' => 'Silva',
                'model' => 'Expedition 4',
                'description' => 'Kompas presisi untuk navigasi dan orienteering',
                'quantity' => 30,
                'quantity_available' => 28,
                'unit' => 'unit',
                'condition' => 'excellent',
                'status' => 'available',
                'storage_location' => 'Lemari Alat',
                'purchase_price' => 450000,
                'specifications' => [
                    'Type' => 'Baseplate Compass',
                    'Features' => 'Adjustable declination, magnifying lens',
                ],
            ],
            [
                'code' => 'GPS-001',
                'equipment_category_id' => $navigasiCategory->id,
                'name' => 'GPS Handheld',
                'brand' => 'Garmin',
                'model' => 'eTrex 32x',
                'description' => 'GPS handheld dengan peta dan kompas elektronik',
                'quantity' => 5,
                'quantity_available' => 4,
                'unit' => 'unit',
                'condition' => 'good',
                'status' => 'available',
                'storage_location' => 'Lemari Alat',
                'purchase_date' => now()->subYears(1),
                'purchase_price' => 3500000,
                'specifications' => [
                    'Display' => '2.2" color',
                    'Memory' => '8GB',
                    'Battery' => 'AA x 2',
                    'Waterproof' => 'IPX7',
                ],
            ],

            // Peralatan Masak
            [
                'code' => 'KMP-001',
                'equipment_category_id' => $masakCategory->id,
                'name' => 'Kompor Portable Gas',
                'brand' => 'Kovea',
                'model' => 'Cube',
                'description' => 'Kompor gas portable dengan sistem windproof',
                'quantity' => 12,
                'quantity_available' => 10,
                'unit' => 'unit',
                'condition' => 'good',
                'status' => 'available',
                'storage_location' => 'Gudang B, Rak 3',
                'purchase_price' => 350000,
                'specifications' => [
                    'Fuel Type' => 'Butane Gas',
                    'Berat' => '180 gram',
                    'Boiling Time' => '3-4 minutes/liter',
                ],
            ],
            [
                'code' => 'NST-001',
                'equipment_category_id' => $masakCategory->id,
                'name' => 'Nesting Set 6 Orang',
                'brand' => 'Naturehike',
                'model' => 'Cookset 6-7 Person',
                'description' => 'Set alat masak untuk 6-7 orang',
                'quantity' => 8,
                'quantity_available' => 7,
                'unit' => 'set',
                'condition' => 'good',
                'status' => 'available',
                'storage_location' => 'Gudang B, Rak 3',
                'purchase_price' => 550000,
                'specifications' => [
                    'Material' => 'Aluminum',
                    'Kapasitas' => '6-7 orang',
                    'Isi' => '3 pot, 1 wajan, sendok, spatula',
                ],
            ],

            // Panjat Tebing
            [
                'code' => 'HAR-001',
                'equipment_category_id' => $climbingCategory->id,
                'name' => 'Harness Full Body',
                'brand' => 'Petzl',
                'model' => 'Corax',
                'description' => 'Harness full body untuk panjat tebing dan rescue',
                'quantity' => 15,
                'quantity_available' => 14,
                'unit' => 'unit',
                'condition' => 'excellent',
                'status' => 'available',
                'storage_location' => 'Lemari Climbing',
                'purchase_date' => now()->subMonths(10),
                'purchase_price' => 1200000,
                'specifications' => [
                    'Size' => 'Adjustable',
                    'Weight Capacity' => '120 kg',
                    'Certification' => 'CE EN 12277',
                ],
                'last_maintenance_date' => now()->subMonths(1),
                'next_maintenance_date' => now()->addMonths(5),
            ],
            [
                'code' => 'CAR-100',
                'equipment_category_id' => $climbingCategory->id,
                'name' => 'Carabiner HMS',
                'brand' => 'Black Diamond',
                'model' => 'RockLock Screwgate',
                'description' => 'Carabiner HMS dengan screw gate untuk belay',
                'quantity' => 30,
                'quantity_available' => 28,
                'unit' => 'unit',
                'condition' => 'excellent',
                'status' => 'available',
                'storage_location' => 'Lemari Climbing',
                'purchase_price' => 250000,
                'specifications' => [
                    'Gate Type' => 'Screwgate',
                    'Strength' => '24kN',
                    'Weight' => '71 gram',
                ],
            ],
            [
                'code' => 'ROP-001',
                'equipment_category_id' => $climbingCategory->id,
                'name' => 'Rope Dynamic 10mm',
                'brand' => 'Edelweiss',
                'model' => 'Toplight II 10mm',
                'description' => 'Tali dinamis diameter 10mm panjang 50 meter',
                'quantity' => 4,
                'quantity_available' => 3,
                'unit' => 'unit',
                'condition' => 'good',
                'status' => 'available',
                'storage_location' => 'Lemari Climbing',
                'purchase_date' => now()->subYears(1)->subMonths(6),
                'purchase_price' => 4500000,
                'specifications' => [
                    'Diameter' => '10 mm',
                    'Length' => '50 meter',
                    'Type' => 'Dynamic Single Rope',
                    'Certification' => 'UIAA',
                ],
                'maintenance_notes' => 'Cek kondisi secara rutin sebelum digunakan',
                'next_maintenance_date' => now()->addMonths(2),
            ],

            // Penerangan
            [
                'code' => 'HDL-001',
                'equipment_category_id' => $peneranganCategory->id,
                'name' => 'Headlamp LED 500 Lumens',
                'brand' => 'Petzl',
                'model' => 'Tikka',
                'description' => 'Headlamp LED 500 lumens dengan mode red light',
                'quantity' => 25,
                'quantity_available' => 23,
                'unit' => 'unit',
                'condition' => 'excellent',
                'status' => 'available',
                'storage_location' => 'Lemari Alat',
                'purchase_price' => 450000,
                'specifications' => [
                    'Brightness' => '500 lumens',
                    'Battery' => 'AAA x 3',
                    'Waterproof' => 'IPX4',
                    'Weight' => '82 gram',
                ],
            ],

            // Keselamatan & P3K
            [
                'code' => 'P3K-001',
                'equipment_category_id' => $keselamatanCategory->id,
                'name' => 'Tas P3K Lengkap',
                'brand' => 'OneMed',
                'model' => 'First Aid Kit Complete',
                'description' => 'Tas P3K lengkap dengan obat-obatan dan peralatan medis dasar',
                'quantity' => 10,
                'quantity_available' => 9,
                'unit' => 'set',
                'condition' => 'good',
                'status' => 'available',
                'storage_location' => 'Lemari Alat',
                'purchase_price' => 750000,
                'specifications' => [
                    'Isi' => 'Perban, plester, antiseptik, obat-obatan dasar',
                    'Kapasitas' => 'Untuk 10-15 orang',
                ],
                'maintenance_notes' => 'Cek expired date obat-obatan setiap 3 bulan',
                'next_maintenance_date' => now()->addMonths(1),
            ],
            [
                'code' => 'WHI-001',
                'equipment_category_id' => $keselamatanCategory->id,
                'name' => 'Peluit Emergency',
                'brand' => 'Fox 40',
                'model' => 'Classic',
                'description' => 'Peluit darurat dengan suara keras untuk sinyal',
                'quantity' => 50,
                'quantity_available' => 48,
                'unit' => 'unit',
                'condition' => 'excellent',
                'status' => 'available',
                'storage_location' => 'Lemari Alat',
                'purchase_price' => 35000,
            ],
        ];

        foreach ($equipment as $item) {
            Equipment::create($item);
        }
    }
}
