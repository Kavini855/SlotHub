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
                'name' => 'Beauty & Wellness',
                'description' => 'Salon, beauty, grooming and wellness services.',
            ],
            [
                'name' => 'Education & Tutoring',
                'description' => 'Private lessons, tutoring and educational sessions.',
            ],
            [
                'name' => 'Health & Fitness',
                'description' => 'Fitness training, coaching and wellness sessions.',
            ],
            [
                'name' => 'Photography',
                'description' => 'Photography sessions for personal and professional needs.',
            ],
            [
                'name' => 'Consulting',
                'description' => 'Professional consultation and advisory services.',
            ],
            [
                'name' => 'Home Services',
                'description' => 'Repair, maintenance and other home-related services.',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}