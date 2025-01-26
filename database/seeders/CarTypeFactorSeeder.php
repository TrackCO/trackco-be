<?php

namespace Database\Seeders;

use App\Models\CarTypeFactor;
use Illuminate\Database\Seeder;

class CarTypeFactorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carTypeFactors = [
            [
                'name' => 'Gasoline',
                'factor' => 0.9
            ],
            [
                'name' => 'Diesel',
                'factor' => 0.4
            ],
            [
                'name' => 'Electric',
                'factor' => 2.3
            ],
            [
                'name' => 'Hybrid',
                'factor' => 0.4
            ]
        ];

        foreach($carTypeFactors as $carTypeFactor){
            CarTypeFactor::updateOrCreate([
                'name' => $carTypeFactor['name']
            ], [
                'name' => $carTypeFactor['name'],
                'factor' => $carTypeFactor['factor']
            ]);
        }
    }
}
