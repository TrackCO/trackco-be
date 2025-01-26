<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmissionCycleToEmissionTransportationModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emission_transportation_modes', function (Blueprint $table) {
            $table->foreignId('emission_cycle_id')->constrained('emission_cycles')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emission_transportation_modes', function (Blueprint $table) {
            $table->dropForeign('emission_cycle_id');
            $table->dropColumn('emission_cycle_id');
        });
    }
}
