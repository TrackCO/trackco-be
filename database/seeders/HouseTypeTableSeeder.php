<?php

namespace Database\Seeders;

use App\Models\HouseType;
use Illuminate\Database\Seeder;

class HouseTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $houseTypes = [
            [
                'name' => 'Detached',
                'factor' => 0.50
            ],
            [
                'name' => 'Flat',
                'factor' => 0.50
            ],
            [
                'name' => 'Semi-Detached',
                'factor' => 0.50
            ]
        ];

        array_map(fn($houseType) => HouseType::updateOrCreate([ 'name' => $houseType['name']], [...$houseType]), $houseTypes);

    }
}
