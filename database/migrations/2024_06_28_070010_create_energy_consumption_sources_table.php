<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnergyConsumptionSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_consumption_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('energy_consumption_id')->constrained('energy_consumptions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('energy_source_id')->constrained('energy_sources')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('value', 11, 2)->default(0.00);
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
        Schema::dropIfExists('energy_consumption_sources');
    }
}
