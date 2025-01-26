<?php

namespace Database\Seeders;

use App\Models\EmissionPeriodFactor;
use Illuminate\Database\Seeder;

class EmissionPeriodFactorSeeder extends Seeder
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
                'name' => 'Weekly',
                'factor' => 1
            ],
            [
                'name' => 'Monthly',
                'factor' => 0.3
            ],
            [
                'name' => 'Annually',
                'factor' => 0.7
            ]
        ];

        foreach($factors as $factor){
            EmissionPeriodFactor::updateOrCreate([
                'name' => $factor['name']
            ], [
                'name' => $factor['name'],
                'factor' => $factor['factor']
            ]);
        }
    }
}
