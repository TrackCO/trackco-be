<?php

namespace Database\Seeders;

use App\Models\LifestylePeriodFactor;
use Illuminate\Database\Seeder;

class LifestylePeriodFactorSeeder extends Seeder
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
                'name' => '3 - 5 days',
                'factor' => 0.5
            ],
            [
                'name' => '10 - 20 days',
                'factor' => 0.6
            ]
        ];

        foreach($factors as $factor){
            LifestylePeriodFactor::updateOrCreate([
                'name' => $factor['name']
            ], [
                'name' => $factor['name'],
                'factor' => $factor['factor']
            ]);
        }
    }
}
