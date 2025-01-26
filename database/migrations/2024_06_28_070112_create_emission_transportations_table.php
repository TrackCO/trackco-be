<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmissionTransportationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emission_transportations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carbon_footprint_id')->constrained('carbon_footprints')->cascadeOnDelete()->cascadeOnUpdate();
            $table->json('enabled_mode')->nullable()->default(null);
            $table->decimal('flight_very_long_max', 11, 2)->default(0);
            $table->decimal('flight_very_long_min', 11, 2)->default(0);
            $table->decimal('flight_long_max', 11, 2)->default(0);
            $table->decimal('flight_long_min', 11, 2)->default(0);
            $table->decimal('flight_medium_max', 11, 2)->default(0);
            $table->decimal('flight_medium_min', 11, 2)->default(0);
            $table->decimal('flight_short_max', 11, 2)->default(0);
            $table->decimal('flight_short_min', 11, 2)->default(0);
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
        Schema::dropIfExists('emission_transportations');
    }
}
