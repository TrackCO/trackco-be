<?php

namespace Database\Seeders;

use App\Models\ProjectCategory;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ProjectCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Natural climate solutions',
            'Avoidance',
            'Engineered climate solutions',
            'Water technologies'
        ];

        array_map( fn($category) => ProjectCategory::updateOrCreate(['name' => $category], ['name' => $category]), $categories );

    }
}
