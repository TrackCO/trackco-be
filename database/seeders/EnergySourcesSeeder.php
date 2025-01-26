<?php

namespace Database\Seeders;

use App\Models\EnergySource;
use App\Models\EnergyUnitFactor;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class EnergySourcesSeeder extends Seeder
{
    private function findUnitByName($name)
    {
        return Unit::where('name', $name)->firstOrFail();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EnergyUnitFactor::whereNotNull('id')->delete();
        EnergySource::whereNotNull('id')->delete();
        // Reset the auto-increment counters
        \DB::statement('ALTER TABLE energy_unit_factors AUTO_INCREMENT = 1;');
        \DB::statement('ALTER TABLE energy_sources AUTO_INCREMENT = 1;');
        
        $sources = [
            [
                'name' => 'Natural Gas',
                'slug' => 'natural_gas',
                'factors' => [
                    [
                        'unit_id' => $this->findUnitByName('KWh')?->id,
                        'factor' => 0.18293,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('Therms')?->id,
                        'factor' => 5.36115,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('GBP (£)')?->id,
                        'factor' => 2.4900,
                    ]
                ]
            ],
            [
                'name' => 'Heating Oil',
                'slug' => 'heating_oil',
                'factors' => [
                    [
                        'unit_id' => $this->findUnitByName('KWh')?->id,
                        'factor' => 0.24677,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('Litres')?->id,
                        'factor' => 2.54016,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('Tonnes')?->id,
                        'factor' => 3165.04,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('US Gallons')?->id,
                        'factor' => 9.61544,
                    ]
                ]
            ],
            [
                'name' => 'Coal',
                'slug' => 'coal',
                'factors' => [
                    [
                        'unit_id' => $this->findUnitByName('KWh')?->id,
                        'factor' => 0.34721,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('Tonnes')?->id,
                        'factor' => 2904.95,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('x 10kg bags')?->id,
                        'factor' => 29.0495,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('x 20kg bags')?->id,
                        'factor' => 58.0991,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('x 25kg bags')?->id,
                        'factor' => 72.6238,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('x 50kg bags')?->id,
                        'factor' => 145.2476
                    ]
                ]
            ],
            [
                'name' => 'LPG',
                'slug' => 'lpg',
                'factors' => [
                    [
                        'unit_id' => $this->findUnitByName('KWh')?->id,
                        'factor' => 0.21450,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('Litres')?->id,
                        'factor' => 1.55713,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('Therms')?->id,
                        'factor' => 6.28626,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('US Gallons')?->id,
                        'factor' => 5.89437,
                    ]
                ]
            ],
            [
                'name' => 'Propane',
                'slug' => 'propane',
                'factors' => [
                    [
                        'unit_id' => $this->findUnitByName('Litres')?->id,
                        'factor' => 1.54358,
                    ],
                    [
                        'unit_id' => $this->findUnitByName('US Gallons')?->id,
                        'factor' => 5.84308,
                    ]
                ]
            ],
            [
                'name' => 'Wooden Pellets',
                'slug' => 'wooden_pellets',
                'factors' => [
                    [
                        'unit_id' => $this->findUnitByName('Tonnes')?->id,
                        'factor' => 51.56192,
                    ]
                ]
            ]
        ];

        foreach ($sources as $source) {
            $energySource = EnergySource::updateOrCreate([
                'slug' => $source['slug'],
            ], [
                'name' => $source['name'],
                'slug' => $source['slug'],
            ]);

            foreach ($source['factors'] as $factor) {
                EnergyUnitFactor::updateOrCreate([
                    'energy_source_id' => $energySource->id,
                    'unit_id' => $factor['unit_id'],
                ], [
                    'energy_source_id' => $energySource->id,
                    'factor' => $factor['factor'],
                    'unit_id' => $factor['unit_id']
                ]);
            }
        }
    }
}
