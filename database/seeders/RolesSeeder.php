<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'Business Owner',
            'Employee',
            'Individual',
            'Administrator',
        ];

        array_map(fn($role) => Role::updateOrCreate(['name' => $role], ['name' => $role]), $roles);
    }
}
