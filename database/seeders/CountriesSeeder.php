<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('countries.json');
        $countries = json_decode(file_get_contents($path), true);

        foreach ($countries as $country) {
            Country::updateOrCreate([
                'code' => $country['code'],
            ], [
                'name' => $country['name'],
                'code' => $country['code'],
                'flag' => $country['emoji'],
                'dial_code' => $country['dial_code'],
            ]);
        }
    }
}
