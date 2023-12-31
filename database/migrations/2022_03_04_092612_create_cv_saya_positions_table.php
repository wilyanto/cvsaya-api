<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCvSayaPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->bigInteger('level_id')->unsigned()->nullable();
            $table->integer('priority')->unsigned()->nullable();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->integer('remaining_slot')->nullable();
            $table->integer('min_salary')->nullable();
            $table->integer('max_salary')->nullable();
            $table->string('company_id')->unique()->nullable();
            $table->timestamps();
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('level_id')->references('id')->on('levels');
            $table->foreign('parent_id')->references('id')->on('positions');
        });


    }

    /**
     * Reverse the migrations.~
     *
     * @return void
     */
    public function down()
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['parent_id']);
        });

        Schema::dropIfExists('positions');
    }
}
