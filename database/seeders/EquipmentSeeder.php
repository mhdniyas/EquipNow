<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipmentItems = [
            // Microphones
            'Microphones' => [
                [
                    'name' => 'Shure SM58',
                    'description' => 'Industry standard dynamic vocal microphone',
                    'daily_rate' => 15.00,
                    'deposit_amount' => 100.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 10,
                    'quantity_available' => 10,
                    'condition_notes' => 'Excellent condition, regularly maintained',
                ],
                [
                    'name' => 'Sennheiser EW 100 G4',
                    'description' => 'Wireless handheld microphone system',
                    'daily_rate' => 35.00,
                    'deposit_amount' => 250.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 5,
                    'quantity_available' => 5,
                    'condition_notes' => 'New units, all accessories included',
                ],
            ],
            
            // Speakers
            'Speakers' => [
                [
                    'name' => 'JBL PRX815',
                    'description' => '15" two-way powered loudspeaker',
                    'daily_rate' => 75.00,
                    'deposit_amount' => 500.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 8,
                    'quantity_available' => 8,
                    'condition_notes' => 'Good condition, minor cosmetic wear',
                ],
                [
                    'name' => 'QSC KW181',
                    'description' => '18" powered subwoofer',
                    'daily_rate' => 85.00,
                    'deposit_amount' => 600.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 4,
                    'quantity_available' => 4,
                    'condition_notes' => 'Excellent condition, regularly tested',
                ],
            ],
            
            // Mixers
            'Mixers' => [
                [
                    'name' => 'Allen & Heath SQ-6',
                    'description' => 'Digital mixer with 24 faders and 48 inputs',
                    'daily_rate' => 120.00,
                    'deposit_amount' => 1000.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 2,
                    'quantity_available' => 2,
                    'condition_notes' => 'Excellent condition, latest firmware',
                ],
                [
                    'name' => 'Yamaha MG12XU',
                    'description' => '12-channel analog mixer with effects',
                    'daily_rate' => 40.00,
                    'deposit_amount' => 300.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 3,
                    'quantity_available' => 3,
                    'condition_notes' => 'Good condition, all channels working properly',
                ],
            ],
            
            // LED Lights
            'LED Lights' => [
                [
                    'name' => 'ADJ Mega Par Profile',
                    'description' => 'RGB+UV LED par can',
                    'daily_rate' => 20.00,
                    'deposit_amount' => 150.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 16,
                    'quantity_available' => 16,
                    'condition_notes' => 'Good condition, regularly maintained',
                ],
            ],
            
            // Moving Heads
            'Moving Heads' => [
                [
                    'name' => 'Chauvet Intimidator Spot 375Z',
                    'description' => '150W LED moving head light',
                    'daily_rate' => 65.00,
                    'deposit_amount' => 450.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 6,
                    'quantity_available' => 6,
                    'condition_notes' => 'Excellent condition, all features working',
                ],
            ],
            
            // Cameras
            'Cameras' => [
                [
                    'name' => 'Sony PXW-FS5',
                    'description' => 'Professional 4K camcorder',
                    'daily_rate' => 150.00,
                    'deposit_amount' => 2000.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 2,
                    'quantity_available' => 2,
                    'condition_notes' => 'Excellent condition, includes basic accessories',
                ],
            ],
            
            // Projectors
            'Projectors' => [
                [
                    'name' => 'Epson Pro G7500U',
                    'description' => '6500-lumen WUXGA projector',
                    'daily_rate' => 200.00,
                    'deposit_amount' => 1500.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 3,
                    'quantity_available' => 3,
                    'condition_notes' => 'Good condition, lamps have >80% life remaining',
                ],
            ],
            
            // DJ Controllers
            'DJ Controllers' => [
                [
                    'name' => 'Pioneer DDJ-1000',
                    'description' => '4-channel DJ controller for Rekordbox',
                    'daily_rate' => 100.00,
                    'deposit_amount' => 800.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 3,
                    'quantity_available' => 3,
                    'condition_notes' => 'Excellent condition, includes software license',
                ],
            ],
            
            // Generators
            'Generators' => [
                [
                    'name' => 'Honda EU7000is',
                    'description' => '7000W super quiet inverter generator',
                    'daily_rate' => 175.00,
                    'deposit_amount' => 1000.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 2,
                    'quantity_available' => 2,
                    'condition_notes' => 'Excellent condition, regularly serviced',
                ],
            ],
            
            // Stage Platforms
            'Stage Platforms' => [
                [
                    'name' => 'Staging Deck 4\'x8\'',
                    'description' => 'Professional staging deck with adjustable legs',
                    'daily_rate' => 50.00,
                    'deposit_amount' => 300.00,
                    'status' => Equipment::STATUS_AVAILABLE,
                    'quantity' => 12,
                    'quantity_available' => 12,
                    'condition_notes' => 'Good condition, some minor scratches',
                ],
            ],
        ];

        foreach ($equipmentItems as $subcategoryName => $items) {
            $subcategory = Subcategory::where('name', $subcategoryName)->first();
            
            if ($subcategory) {
                foreach ($items as $item) {
                    Equipment::firstOrCreate(
                        [
                            'name' => $item['name'],
                            'category_id' => $subcategory->category_id,
                            'subcategory_id' => $subcategory->id
                        ],
                        [
                            'description' => $item['description'],
                            'daily_rate' => $item['daily_rate'],
                            'deposit_amount' => $item['deposit_amount'],
                            'status' => $item['status'],
                            'quantity' => $item['quantity'],
                            'quantity_available' => $item['quantity_available'],
                            'condition_notes' => $item['condition_notes'],
                        ]
                    );
                }
            }
        }
    }
}
