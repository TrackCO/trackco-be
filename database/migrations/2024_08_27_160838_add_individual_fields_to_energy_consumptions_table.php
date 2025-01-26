<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndividualFieldsToEnergyConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('energy_consumptions', function (Blueprint $table) {
            $table->foreignId('house_type_id')->nullable()->default(null)->constrained('house_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('size', 11, 2)->nullable()->default(0.00);
            $table->decimal('solar', 11, 2)->nullable()->default(0.00);
            $table->decimal('wind', 11, 2)->nullable()->default(0.00);
            $table->decimal('hydro_power', 11, 2)->nullable()->default(0.00);
            $table->decimal('nuclear', 11, 2)->nullable()->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('energy_consumptions', function (Blueprint $table) {
            //
        });
    }
}
