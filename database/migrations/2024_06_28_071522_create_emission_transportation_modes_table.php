<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmissionTransportationModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emission_transportation_modes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transportation_mode_id')->constrained('transportation_modes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('value', 11, 2)->default(0.00);
            $table->foreignId('emission_transportation_id')->constrained('emission_transportations')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('emission_transportation_modes');
    }
}
