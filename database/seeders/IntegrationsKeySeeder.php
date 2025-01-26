<?php

namespace Database\Seeders;

use App\Enums\IntegrationSourceEnum;
use App\Models\Integration;
use Illuminate\Database\Seeder;

class IntegrationsKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $integrations = [
            [
                'source' => IntegrationSourceEnum::BHR->value,
                'app_secret_key' => bcrypt(generateRandomCharacters(40))
            ]
        ];

        array_map(fn($integration) => Integration::updateOrCreate([ 'source' => $integration['source']], [...$integration]), $integrations);
        
        print("Here are the generated keys: \n". json_encode($integrations, true)."\n"); // For easy access for now...
        
        # NOTE::Build admin interface to easily manage generated keys
    }
}
