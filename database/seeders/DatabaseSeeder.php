<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            AccountTypeSeeder::class,
            EmissionCycleSeeder::class,
            UnitsSeeder::class,
            CountriesSeeder::class,
            CurrenciesSeeder::class,
            EnergySourcesSeeder::class,
            LifestyleSectorFactorSeeder::class,
            CarTypeFactorSeeder::class,
            EmissionPeriodFactorSeeder::class,
            LifestylePeriodFactorSeeder::class,
            LifestylePreferredDietFactorSeeder::class,
            ProjectCategorySeeder::class,
            HouseTypeTableSeeder::class,
            AdminUserSeeder::class,
            AssignReferralCodeToOldUsersSeeder::class
        ]);
    }
}
