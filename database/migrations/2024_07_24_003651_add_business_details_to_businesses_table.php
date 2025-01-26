<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusinessDetailsToBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('website_url')->nullable()->default(null);
            $table->longText('logo_url')->nullable()->default(null);
            $table->integer('no_of_employees')->default(0);
            $table->foreignId('country_id')->nullable()->default(null)->constrained('countries')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('website_url');
            $table->dropColumn('logo_url');
            $table->dropColumn('no_of_employees');
            $table->dropForeign('country_id');
            $table->dropColumn('country_id');
        });
    }
}
