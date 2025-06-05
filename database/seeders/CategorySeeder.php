<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Audio Equipment',
                'description' => 'Professional audio equipment for events and productions',
                'icon' => 'fa-solid fa-microphone',
            ],
            [
                'name' => 'Lighting',
                'description' => 'Professional lighting equipment for events and productions',
                'icon' => 'fa-solid fa-lightbulb',
            ],
            [
                'name' => 'Video Equipment',
                'description' => 'Professional video equipment for recording and streaming',
                'icon' => 'fa-solid fa-video',
            ],
            [
                'name' => 'Power & Electrical',
                'description' => 'Power generators and electrical equipment',
                'icon' => 'fa-solid fa-plug',
            ],
            [
                'name' => 'Stage Equipment',
                'description' => 'Stages, platforms, and structural equipment',
                'icon' => 'fa-solid fa-theater-masks',
            ],
            [
                'name' => 'DJ Equipment',
                'description' => 'Complete DJ setups and accessories',
                'icon' => 'fa-solid fa-record-vinyl',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
