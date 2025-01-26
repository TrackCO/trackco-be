<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable()->default(null);
            $table->string('last_name')->nullable()->default(null);
            $table->string('full_name')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password')->nullable()->default(null);
            $table->boolean('is_verified')->default(true);
            $table->foreignId('role_id')->nullable()->default(null)->constrained('roles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('business_id')->nullable()->default(null)->constrained('businesses')->cascadeOnUpdate()->cascadeOnDelete();
            $table->smallInteger('status')->default(\App\Enums\AccountStatus::ACTIVE->value);
            $table->string('referral_code')->nullable()->default(null);
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
        Schema::dropIfExists('users');
    }
}
