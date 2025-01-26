<?php

namespace Database\Seeders;

use App\Models\LifestyleSectorFactor;
use Illuminate\Database\Seeder;

class LifestyleSectorFactorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lifestyleSectors = [
            [
                'name' => 'Paper Based Products',
                'slug' => 'paper_based_products',
                'factor' => 0.698,
            ],
            [
                'name' => 'Banking and Finance(mortgage payments and loan interest)',
                'slug' => 'banking_and_finance',
                'factor' => 0.07,
            ],
            [
                'name' => 'Motor Vehicles(not including fuel)',
                'slug' => 'motor_vehicles',
                'factor' => 0.366,
            ],
            [
                'name' => 'Hotels/Restaurants/Pubs',
                'slug' => 'hotels_restaurants',
                'factor' => 0.241,
            ],
            [
                'name' => 'Insurance',
                'slug' => 'insurance',
                'factor' => 0.068,
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'factor' => 0.067,
            ],
            [
                'name' => 'Pharmaceuticals',
                'slug' => 'pharmaceuticals',
                'factor' => 0.514,
            ],
            [
                'name' => 'Cloths/Textiles and Shoes',
                'slug' => 'cloths_and_shoes',
                'factor' => 0.782,
            ],
            [
                'name' => 'Recreational Activities',
                'slug' => 'recreational_activities',
                'factor' => 0.155,
            ],
            [
                'name' => 'Furniture',
                'slug' => 'furniture',
                'factor' => 0.563,
            ]
        ];

        foreach ($lifestyleSectors as $lifestyleSector) {
            LifestyleSectorFactor::updateOrCreate([
                'slug' => $lifestyleSector['slug'],
            ], [
                'name' => $lifestyleSector['name'],
                'slug' => $lifestyleSector['slug'],
                'factor' => $lifestyleSector['factor'],
            ]);
        }
    }
}
