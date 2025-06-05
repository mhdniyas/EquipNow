<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Combo;
use App\Models\ComboItem;
use App\Models\Equipment;
use Illuminate\Database\Seeder;

class ComboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $combos = [
            [
                'name' => 'Basic PA System',
                'description' => 'Complete PA system for small events (up to 100 people)',
                'category_name' => 'Audio Equipment',
                'combo_price' => 225.00,
                'status' => 'active',
                'items' => [
                    ['equipment_name' => 'JBL PRX815', 'quantity' => 2, 'is_free' => false],
                    ['equipment_name' => 'QSC KW181', 'quantity' => 1, 'is_free' => false],
                    ['equipment_name' => 'Yamaha MG12XU', 'quantity' => 1, 'is_free' => false],
                    ['equipment_name' => 'Shure SM58', 'quantity' => 2, 'is_free' => true],
                ]
            ],
            [
                'name' => 'Small Event Lighting Package',
                'description' => 'Basic lighting setup for small venues',
                'category_name' => 'Lighting',
                'combo_price' => 150.00,
                'status' => 'active',
                'items' => [
                    ['equipment_name' => 'ADJ Mega Par Profile', 'quantity' => 8, 'is_free' => false],
                    ['equipment_name' => 'Chauvet Intimidator Spot 375Z', 'quantity' => 2, 'is_free' => false],
                ]
            ],
            [
                'name' => 'DJ Performance Package',
                'description' => 'Complete DJ setup with sound and lighting',
                'category_name' => 'DJ Equipment',
                'combo_price' => 350.00,
                'status' => 'active',
                'items' => [
                    ['equipment_name' => 'Pioneer DDJ-1000', 'quantity' => 1, 'is_free' => false],
                    ['equipment_name' => 'JBL PRX815', 'quantity' => 2, 'is_free' => false],
                    ['equipment_name' => 'QSC KW181', 'quantity' => 2, 'is_free' => false],
                    ['equipment_name' => 'ADJ Mega Par Profile', 'quantity' => 4, 'is_free' => true],
                ]
            ],
        ];

        foreach ($combos as $comboData) {
            $category = Category::where('name', $comboData['category_name'])->first();
            
            if ($category) {
                $combo = Combo::firstOrCreate(
                    [
                        'name' => $comboData['name'],
                        'category_id' => $category->id
                    ],
                    [
                        'description' => $comboData['description'],
                        'combo_price' => $comboData['combo_price'],
                        'status' => $comboData['status'],
                    ]
                );
                
                // Add items to combo
                foreach ($comboData['items'] as $itemData) {
                    $equipment = Equipment::where('name', $itemData['equipment_name'])->first();
                    
                    if ($equipment) {
                        ComboItem::firstOrCreate(
                            [
                                'combo_id' => $combo->id,
                                'equipment_id' => $equipment->id,
                            ],
                            [
                                'quantity' => $itemData['quantity'],
                                'is_free' => $itemData['is_free'],
                            ]
                        );
                    }
                }
            }
        }
    }
}
