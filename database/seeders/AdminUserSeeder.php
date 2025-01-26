<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Enums\AccountRolesEnum;
use App\Enums\AccountType;
use App\Models\User;
use App\Models\Country;
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $users = [
            [
                'full_name' => 'LOT Admin',
                'email' => 'lot@admin.com',
                'password' => bcrypt('$admin@password'),
                'is_verified' => true,
                'role_id' => AccountRolesEnum::ADMINISTRATOR->value,
                'account_type_id' => AccountType::ADMIN->value,
                'phone' => $faker->numerify('+234 ###-###-####'),
                'country_id' => Country::nigerian()
            ]// Add more...
        ]; 

        foreach($users as $user){
            User::updateOrCreate([
                'email' => $user['email']
            ], [...$user]);
        }


    }
}
