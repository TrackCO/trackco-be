<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            'KWh',
            'Tonnes',
            'Litres',
            'GBP (£)',
            'Therms',
            'Miles',
            'Km',
            'US Gallons',
            'x 10kg bags',
            'x 20kg bags',
            'x 25kg bags',
            'x 50kg bags'
        ];
        array_map(fn($unit) => Unit::updateOrCreate(['name' => $unit], ['name' => $unit]), $units);

    }
}
