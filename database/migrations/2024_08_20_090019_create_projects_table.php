<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 11, 2)->default(0.00);
            $table->decimal('available_tonnes', 11, 2)->default(0.00);
            $table->longText('description')->nullable()->default(null);
            $table->string('image_url')->nullable()->default(null);
            $table->foreignId('country_id')->constrained('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('size')->default(0);
            $table->string('type')->nullable()->default(null);
            $table->foreignId('project_category_id')->nullable()->constrained('project_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('developer_name')->nullable()->default(null);
            $table->string('eligibility')->nullable()->default(null);
            $table->string('standard')->nullable()->default(null);
            $table->string('methodology')->nullable()->default(null);
            $table->string('additional_certificates')->nullable()->default(null);
            $table->string('cbb_validator')->nullable()->default(null);
            $table->string('project_validator')->nullable()->default(null);
            $table->date('issue_date')->nullable()->default(null);
            $table->boolean('is_visible')->default(true);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('projects');
    }
}
