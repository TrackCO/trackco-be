<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnergyConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carbon_footprint_id')->constrained('carbon_footprints')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('electricity_usage')->default(0);
            $table->integer('number_of_employees')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('energy_consumptions');
    }
}
