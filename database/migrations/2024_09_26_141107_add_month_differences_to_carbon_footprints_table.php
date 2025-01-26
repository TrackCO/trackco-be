<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMonthDifferencesToCarbonFootprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carbon_footprints', function (Blueprint $table) {
            $table->integer('month_from')->nullable()->default(1);
            $table->integer('month_to')->nullable()->default(12);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carbon_footprints', function (Blueprint $table) {
            $table->dropColumn('month_from');
            $table->dropColumn('month_to');
        });
    }
}
