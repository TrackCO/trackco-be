<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarTransportationConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_transportation_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_type_id')->constrained('car_types')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('emission_transportation_id');
            $table->foreign('emission_transportation_id', 'car_trans_emission_fk')
                ->references('id')
                ->on('emission_transportations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->decimal('annual_mileage', 10, 2)->default(0.00);
            $table->decimal('average_consumption', 10, 2)->default(0.00);
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
        Schema::dropIfExists('car_transportation_consumptions');
    }
}
