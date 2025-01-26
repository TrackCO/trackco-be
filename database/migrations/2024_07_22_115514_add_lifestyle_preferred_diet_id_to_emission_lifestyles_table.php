<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLifestylePreferredDietIdToEmissionLifestylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emission_lifestyles', function (Blueprint $table) {
            $table->foreignId('lifestyle_preferred_diet_id')->nullable()->default(null)->constrained('lifestyle_preferred_diet_factors')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emission_lifestyles', function (Blueprint $table) {
            if (Schema::hasColumn('emission_lifestyles', 'lifestyle_preferred_diet_id')) {
                $table->dropForeign(['lifestyle_preferred_diet_id']);
                $table->dropColumn('lifestyle_preferred_diet_id');
            }
        });
    }
}
