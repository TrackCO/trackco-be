<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;
class AssignReferralCodeToOldUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::whereNull('referral_code')->get();

        foreach($users as $user){
            $user->referral_code = generateRandomCharacters(15);
            $user->save();
        }
    }
}
