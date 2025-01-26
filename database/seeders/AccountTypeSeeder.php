<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accountTypes = [
            'Business',
            'Individual',
            'Admin'
        ];
        array_map(fn($accountType) => AccountType::updateOrCreate(['name' => $accountType], ['name' => $accountType]), $accountTypes);

    }
}
