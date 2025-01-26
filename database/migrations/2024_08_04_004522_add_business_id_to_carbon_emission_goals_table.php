<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusinessIdToCarbonEmissionGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carbon_emission_goals', function (Blueprint $table) {
            $table->foreignId('business_id')->nullable()->default(null)->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carbon_emission_goals', function (Blueprint $table) {
            $table->dropForeign('carbon_emission_goals_business_id_foreign');
            $table->dropColumn('business_id');
        });
    }
}
