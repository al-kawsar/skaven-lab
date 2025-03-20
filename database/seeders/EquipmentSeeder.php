<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentLocation;
use App\Services\Inventory\InventoryCodeGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EquipmentSeeder extends Seeder
{
    private $sequences = [];

    private function generateUniqueCode($categoryCode)
    {
        if (!isset($this->sequences[$categoryCode])) {
            $this->sequences[$categoryCode] = 1;
        }

        $year = date('y');
        $sequence = str_pad($this->sequences[$categoryCode], 4, '0', STR_PAD_LEFT);
        $code = "{$categoryCode}{$year}{$sequence}";

        $this->sequences[$categoryCode]++;

        return $code;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate tables first to ensure clean state
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Equipment::truncate();
        EquipmentCategory::truncate();
        EquipmentLocation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create categories if not exists
        $categories = [
            ['name' => 'Komputer', 'code' => 'KMP'],
            ['name' => 'Periferal', 'code' => 'PRF'],
            ['name' => 'Jaringan', 'code' => 'NET'],
            ['name' => 'Elektronik', 'code' => 'ELK'],
            ['name' => 'Furniture', 'code' => 'FRN'],
            ['name' => 'Software', 'code' => 'SFT'],
            ['name' => 'Audio Visual', 'code' => 'AVS'],
            ['name' => 'Alat Ukur', 'code' => 'MSR'],
            ['name' => 'Keamanan', 'code' => 'SEC'],
            ['name' => 'Penyimpanan', 'code' => 'STR']
        ];

        // Create categories first
        foreach ($categories as $category) {
            EquipmentCategory::create([
                'name' => $category['name'],
                'code' => $category['code']
            ]);
        }

        // Verify categories exist
        if (EquipmentCategory::count() !== count($categories)) {
            $this->command->error('Failed to create equipment categories');
            return;
        }

        // Create locations if not exists
        $locations = [
            ['name' => 'Lab Komputer 1', 'code' => 'LK1'],
            ['name' => 'Lab Komputer 2', 'code' => 'LK2'],
            ['name' => 'Lab Multimedia', 'code' => 'LMM'],
            ['name' => 'Lab Jaringan', 'code' => 'LJR'],
            ['name' => 'Ruang Server', 'code' => 'SRV'],
            ['name' => 'Gudang Utama', 'code' => 'GDU'],
            ['name' => 'Gudang Peralatan', 'code' => 'GDP'],
            ['name' => 'Ruang Teknisi', 'code' => 'TEK']
        ];

        // Create locations
        foreach ($locations as $location) {
            EquipmentLocation::create([
                'name' => $location['name'],
                'code' => $location['code']
            ]);
        }

        // Verify locations exist
        if (EquipmentLocation::count() !== count($locations)) {
            $this->command->error('Failed to create equipment locations');
            return;
        }

        // Skip seeding equipment if data already exists
        if (Equipment::count() > 0) {
            $this->command->info('Equipment data already exists, skipping...');
            return;
        }

        // Generate equipment data
        $equipment = [];

        // Komputer
        $category = EquipmentCategory::where('code', 'KMP')->first();
        for ($i = 1; $i <= 15; $i++) {
            $equipment[] = [
                'id' => Str::uuid()->toString(),
                'name' => 'Komputer Desktop Intel i5',
                'code' => $this->generateUniqueCode($category->code),
                'stock' => rand(1, 5),
                'condition' => ['baik', 'rusak ringan', 'rusak berat'][rand(0, 2)],
                'category_id' => $category->id,
                'location_id' => rand(1, 2),
                'description' => 'PC Desktop dengan prosesor Intel i5, RAM 8GB, SSD 256GB',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Periferal
        $category = EquipmentCategory::where('code', 'PRF')->first();
        $periferals = ['Monitor LED 24"', 'Keyboard Mechanical', 'Mouse Gaming', 'Webcam HD', 'Headset Gaming', 'Scanner', 'Printer Laser', 'UPS 1200VA'];
        foreach ($periferals as $name) {
            for ($i = 1; $i <= 5; $i++) {
                $equipment[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => $name,
                    'code' => $this->generateUniqueCode($category->code),
                    'stock' => rand(2, 10),
                    'condition' => ['baik', 'rusak ringan'][rand(0, 1)],
                    'category_id' => $category->id,
                    'location_id' => rand(1, 8),
                    'description' => "Perangkat $name untuk kebutuhan lab",
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Jaringan
        $category = EquipmentCategory::where('code', 'NET')->first();
        $networks = ['Router Wireless', 'Switch 24-Port', 'Access Point', 'Network Cable Tester', 'Patch Panel', 'Network Toolset'];
        foreach ($networks as $name) {
            for ($i = 1; $i <= 3; $i++) {
                $equipment[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => $name,
                    'code' => $this->generateUniqueCode($category->code),
                    'stock' => rand(1, 5),
                    'condition' => ['baik', 'rusak ringan'][rand(0, 1)],
                    'category_id' => $category->id,
                    'location_id' => 4,
                    'description' => "Perangkat jaringan $name",
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Elektronik
        $category = EquipmentCategory::where('code', 'ELK')->first();
        $electronics = ['Proyektor HD', 'Smart TV 55"', 'DVD Player', 'Sound System', 'Microphone Wireless', 'Power Supply', 'Stabilizer'];
        foreach ($electronics as $name) {
            for ($i = 1; $i <= 4; $i++) {
                $equipment[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => $name,
                    'code' => $this->generateUniqueCode($category->code),
                    'stock' => rand(1, 3),
                    'condition' => ['baik', 'rusak ringan', 'rusak berat'][rand(0, 2)],
                    'category_id' => $category->id,
                    'location_id' => rand(1, 8),
                    'description' => "Peralatan elektronik $name",
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Furniture
        $category = EquipmentCategory::where('code', 'FRN')->first();
        $furniture = ['Meja Komputer', 'Kursi Lab', 'Lemari Peralatan', 'Rak Server', 'Meja Instruktur'];
        foreach ($furniture as $name) {
            for ($i = 1; $i <= 5; $i++) {
                $equipment[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => $name,
                    'code' => $this->generateUniqueCode($category->code),
                    'stock' => rand(5, 15),
                    'condition' => ['baik', 'rusak ringan'][rand(0, 1)],
                    'category_id' => $category->id,
                    'location_id' => rand(1, 8),
                    'description' => "$name untuk kebutuhan lab",
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Software
        $category = EquipmentCategory::where('code', 'SFT')->first();
        $software = ['Windows 10 Pro', 'Microsoft Office', 'Adobe Creative Suite', 'AutoCAD', 'Antivirus'];
        foreach ($software as $name) {
            for ($i = 1; $i <= 4; $i++) {
                $equipment[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => "Lisensi $name",
                    'code' => $this->generateUniqueCode($category->code),
                    'stock' => rand(10, 30),
                    'condition' => 'baik',
                    'category_id' => $category->id,
                    'location_id' => 5,
                    'description' => "Lisensi software $name",
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Audio Visual
        $category = EquipmentCategory::where('code', 'AVS')->first();
        $audiovisual = ['Kamera DSLR', 'Tripod', 'Green Screen', 'Studio Light', 'Audio Mixer'];
        foreach ($audiovisual as $name) {
            for ($i = 1; $i <= 3; $i++) {
                $equipment[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => $name,
                    'code' => $this->generateUniqueCode($category->code),
                    'stock' => rand(1, 5),
                    'condition' => ['baik', 'rusak ringan'][rand(0, 1)],
                    'category_id' => $category->id,
                    'location_id' => 3,
                    'description' => "Peralatan audio visual $name",
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Alat Ukur
        $category = EquipmentCategory::where('code', 'MSR')->first();
        $measuring = ['Multimeter Digital', 'Oscilloscope', 'Function Generator', 'Logic Analyzer', 'Power Meter'];
        foreach ($measuring as $name) {
            for ($i = 1; $i <= 3; $i++) {
                $equipment[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => $name,
                    'code' => $this->generateUniqueCode($category->code),
                    'stock' => rand(2, 6),
                    'condition' => ['baik', 'rusak ringan', 'rusak berat'][rand(0, 2)],
                    'category_id' => $category->id,
                    'location_id' => rand(1, 8),
                    'description' => "Alat ukur $name",
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Keamanan
        $category = EquipmentCategory::where('code', 'SEC')->first();
        $security = ['CCTV Camera', 'DVR System', 'Access Card Reader', 'Fire Extinguisher', 'First Aid Kit'];
        foreach ($security as $name) {
            for ($i = 1; $i <= 3; $i++) {
                $equipment[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => $name,
                    'code' => $this->generateUniqueCode($category->code),
                    'stock' => rand(1, 4),
                    'condition' => ['baik', 'rusak ringan'][rand(0, 1)],
                    'category_id' => $category->id,
                    'location_id' => rand(1, 8),
                    'description' => "Peralatan keamanan $name",
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Penyimpanan
        $category = EquipmentCategory::where('code', 'STR')->first();
        $storage = ['External HDD 2TB', 'NAS Storage', 'Flash Drive 64GB', 'Memory Card 128GB', 'Backup Tape'];
        foreach ($storage as $name) {
            for ($i = 1; $i <= 3; $i++) {
                $equipment[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => $name,
                    'code' => $this->generateUniqueCode($category->code),
                    'stock' => rand(2, 8),
                    'condition' => ['baik', 'rusak ringan'][rand(0, 1)],
                    'category_id' => $category->id,
                    'location_id' => rand(1, 8),
                    'description' => "Media penyimpanan $name",
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        try {
            // Create equipment in chunks to avoid memory issues
            foreach (array_chunk($equipment, 100) as $chunk) {
                Equipment::insert($chunk);
            }
            $this->command->info('Successfully seeded ' . count($equipment) . ' equipment items');
        } catch (\Exception $e) {
            $this->command->error('Failed to seed equipment: ' . $e->getMessage());
            $this->command->error('SQL: ' . $e->getPrevious()->getMessage());
        }
    }
}
