<?php

namespace Database\Seeders;

use App\Models\LifestylePreferredDietFactor;
use Illuminate\Database\Seeder;

class LifestylePreferredDietFactorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $factors = [
            [
                'name' => 'For a heavy meat eater',
                'factor' => 1.277
            ],
            [
                'name' => 'For a medium meat eater',
                'factor' => 1
            ],
            [
                'name' => 'For a low meat eater',
                'factor' => 0.829
            ],
            [
                'name' => 'For a pescatarian (fish eater)',
                'factor' => 0.694
            ],
            [
                'name' => 'For a vegetarian',
                'factor' => 0.677
            ],
            [
                'name' => 'For a vegan',
                'factor' => 0.513
            ]
        ];

        // Delete current records
        LifestylePreferredDietFactor::whereNotNull('id')->delete();
        foreach($factors as $factor){
            LifestylePreferredDietFactor::updateOrCreate([
                'name' => $factor['name']
            ], [
                'name' => $factor['name'],
                'factor' => $factor['factor']
            ]);
        }
    }
}
