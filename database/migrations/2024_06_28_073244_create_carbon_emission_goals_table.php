<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarbonEmissionGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carbon_emission_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('min_target_energy_emission', 11, 2)->default(0.00);
            $table->decimal('max_target_energy_emission', 11, 2)->default(0.00);
            $table->decimal('min_target_transportation_emission', 11, 2)->default(0.00);
            $table->decimal('max_target_transportation_emission', 11, 2)->default(0.00);
            $table->decimal('min_target_lifestyle_emission', 11, 2)->default(0.00);
            $table->decimal('max_target_lifestyle_emission', 11, 2)->default(0.00);
            $table->decimal('achieved_energy_emission', 11, 2)->default(0.00);
            $table->decimal('achieved_transportation_emission', 11, 2)->default(0.00);
            $table->decimal('achieved_lifestyle_emission', 11, 2)->default(0.00);
            $table->decimal('carbon_emission_goal_status_id', 11, 2)->default(0.00);
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
        Schema::dropIfExists('carbon_emission_goals');
    }
}
