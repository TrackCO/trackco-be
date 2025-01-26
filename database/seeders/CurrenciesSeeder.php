<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            [
                'name' => 'USD',
                'symbol' => '$',
                'rate' => 1.33683766507674,
            ],
            [
                'name' => 'GBP',
                'symbol' => '£',
                'rate' => 1.0,
            ],
            [
                'name' => 'EUR',
                'symbol' => '€',
                'rate' => 1.22404198708589,
            ],
            [
                'name' => 'CAD',
                'symbol' => '$',
                'rate' => 1.82404815211396,
            ],
            [
                'name' => 'AUD',
                'symbol' => '$',
                'rate' => 1.97581665855479,
            ],
            [
                'name' => 'NZD',
                'symbol' => '$',
                'rate' => 2.18401843018917,
            ],
            [
                'name' => 'ZAR',
                'symbol' => 'R',
                'rate' => 24.0016235439177,
            ],
            [
                'name' => 'CNY',
                'symbol' => '¥',
                'rate' => 9.69260780687238,
            ],
            [
                'name' => 'HKD',
                'symbol' => '$',
                'rate' => 10.4382290145689,
            ],
            [
                'name' => 'INR',
                'symbol' => '₹',
                'rate' => 111.650323605568,
            ]

        ];

        array_map(fn($currency) => Currency::updateOrCreate([ 'name' => $currency['name']], [...$currency]), $currencies);

    }
}
