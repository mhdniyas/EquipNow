<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            // Audio Equipment subcategories
            'Audio Equipment' => [
                ['name' => 'Microphones', 'description' => 'Wired and wireless microphones for various applications'],
                ['name' => 'Speakers', 'description' => 'PA speakers, monitors, and subwoofers'],
                ['name' => 'Mixers', 'description' => 'Analog and digital audio mixers'],
                ['name' => 'Audio Interfaces', 'description' => 'Digital audio interfaces and converters'],
            ],
            
            // Lighting subcategories
            'Lighting' => [
                ['name' => 'LED Lights', 'description' => 'Energy-efficient LED lighting solutions'],
                ['name' => 'Moving Heads', 'description' => 'Dynamic moving head lighting fixtures'],
                ['name' => 'Par Cans', 'description' => 'Traditional and LED par can lights'],
                ['name' => 'Light Controllers', 'description' => 'DMX controllers and lighting consoles'],
            ],
            
            // Video Equipment subcategories
            'Video Equipment' => [
                ['name' => 'Cameras', 'description' => 'Professional video and DSLR cameras'],
                ['name' => 'Projectors', 'description' => 'Video projectors of various brightness levels'],
                ['name' => 'Screens', 'description' => 'Projection screens and displays'],
                ['name' => 'Video Switchers', 'description' => 'Video mixers and switching equipment'],
            ],
            
            // Power & Electrical subcategories
            'Power & Electrical' => [
                ['name' => 'Generators', 'description' => 'Portable power generators'],
                ['name' => 'Distribution Boxes', 'description' => 'Power distribution systems'],
                ['name' => 'Cables & Adapters', 'description' => 'Power cables and electrical adapters'],
            ],
            
            // Stage Equipment subcategories
            'Stage Equipment' => [
                ['name' => 'Stage Platforms', 'description' => 'Modular stage systems and risers'],
                ['name' => 'Truss Systems', 'description' => 'Aluminum truss for lighting and equipment mounting'],
                ['name' => 'Backdrop & Drapes', 'description' => 'Stage backdrops and curtain systems'],
            ],
            
            // DJ Equipment subcategories
            'DJ Equipment' => [
                ['name' => 'DJ Controllers', 'description' => 'Digital DJ controllers and interfaces'],
                ['name' => 'Turntables', 'description' => 'Vinyl turntables and direct-drive systems'],
                ['name' => 'CDJs', 'description' => 'Professional CD/media players for DJs'],
                ['name' => 'DJ Accessories', 'description' => 'Headphones, cables, and other DJ accessories'],
            ],
        ];

        foreach ($subcategories as $categoryName => $categorySubcategories) {
            $category = Category::where('name', $categoryName)->first();
            
            if ($category) {
                foreach ($categorySubcategories as $subcategory) {
                    Subcategory::firstOrCreate(
                        [
                            'name' => $subcategory['name'],
                            'category_id' => $category->id
                        ],
                        [
                            'description' => $subcategory['description'],
                            'category_id' => $category->id
                        ]
                    );
                }
            }
        }
    }
}
