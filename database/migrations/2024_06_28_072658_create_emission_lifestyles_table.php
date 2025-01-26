<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmissionLifestylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emission_lifestyles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carbon_footprint_id')->constrained('carbon_footprints')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('currency')->nullable()->default(null);
            $table->string('period')->nullable()->default(null);
            $table->decimal('paper_products_spending', 11, 2)->default(0.00);
            $table->decimal('it_equipment_spending', 11, 2)->default(0.00);
            $table->decimal('telephone_bills', 11, 2)->default(0.00);
            $table->decimal('banking_finance', 11, 2)->default(0.00);
            $table->decimal('recreational_activities', 11, 2)->default(0.00);
            $table->decimal('insurance', 11, 2)->default(0.00);
            $table->decimal('pharmaceuticals', 11, 2)->default(0.00);
            $table->decimal('education', 11, 2)->default(0.00);
            $table->decimal('diet_reference', 11, 2)->default(0.00);
            $table->decimal('waste_handling', 11, 2)->default(0.00);
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
        Schema::dropIfExists('emission_lifestyles');
    }
}
