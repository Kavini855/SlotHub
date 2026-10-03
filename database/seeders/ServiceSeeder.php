<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providerOne = User::firstOrCreate(
            ['email' => 'provider1@slothub.test'],
            [
                'name' => 'Nimal Perera',
                'role' => 'provider',
                'password' => Hash::make('password'),
            ]
        );

        $providerTwo = User::firstOrCreate(
            ['email' => 'provider2@slothub.test'],
            [
                'name' => 'Sarah Fernando',
                'role' => 'provider',
                'password' => Hash::make('password'),
            ]
        );

        $beauty = Category::where('slug', 'beauty-wellness')->firstOrFail();
        $education = Category::where('slug', 'education-tutoring')->firstOrFail();
        $fitness = Category::where('slug', 'health-fitness')->firstOrFail();
        $photography = Category::where('slug', 'photography')->firstOrFail();
        $consulting = Category::where('slug', 'consulting')->firstOrFail();

        $services = [
            [
                'provider_id' => $providerOne->id,
                'category_id' => $beauty->id,
                'name' => 'Hair Styling Session',
                'description' => 'Professional hair styling appointment.',
                'duration_minutes' => 45,
                'price' => 2500.00,
                'is_active' => true,
            ],
            [
                'provider_id' => $providerOne->id,
                'category_id' => $fitness->id,
                'name' => 'Personal Training Session',
                'description' => 'One-to-one fitness training session.',
                'duration_minutes' => 60,
                'price' => 3500.00,
                'is_active' => true,
            ],
            [
                'provider_id' => $providerOne->id,
                'category_id' => $consulting->id,
                'name' => 'Career Consultation',
                'description' => 'Individual career guidance and consultation.',
                'duration_minutes' => 45,
                'price' => 3000.00,
                'is_active' => true,
            ],
            [
                'provider_id' => $providerTwo->id,
                'category_id' => $education->id,
                'name' => 'Programming Tutoring',
                'description' => 'Individual programming and software development tutoring.',
                'duration_minutes' => 60,
                'price' => 2000.00,
                'is_active' => true,
            ],
            [
                'provider_id' => $providerTwo->id,
                'category_id' => $photography->id,
                'name' => 'Portrait Photography',
                'description' => 'Personal portrait photography session.',
                'duration_minutes' => 90,
                'price' => 7500.00,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                [
                    'provider_id' => $service['provider_id'],
                    'name' => $service['name'],
                ],
                $service
            );
        }
    }
}