<?php

namespace Database\Seeders;

use App\Models\EmissionCycle;
use Illuminate\Database\Seeder;

class EmissionCycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emissionCycles = [
            'Weekly',
            'Monthly',
            'Annually'
        ];
        array_map(fn($emissionCycle) => EmissionCycle::updateOrCreate(['name' => $emissionCycle], ['name' => $emissionCycle]), $emissionCycles);

    }
}
