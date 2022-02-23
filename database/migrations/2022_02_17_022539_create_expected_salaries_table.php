<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_expected_salaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            // $table->integer('amount_before');
            $table->integer('expected_amount');
            $table->bigInteger('expected_position')->unsigned()->nullable();
            $table->string('reason_position')->nullable();
            // $table->longText('about_position')->nullable();
            $table->longText('reasons');
            $table->timestamps();
        });

        Schema::create('cv_log_expected_salaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('expected_salary_id')->unsigned();
            // $table->integer('amount_before');
            $table->integer('expected_amount');
            $table->bigInteger('expected_position')->unsigned()->nullable();
            $table->string('reason_position')->nullable();
            // $table->longText('about_position')->nullable();
            $table->longText('reasons');
            $table->timestamp('created_at');
        });

        Schema::table('cv_log_expected_salaries', function (Blueprint $table) {
            $table->foreign('expected_salary_id')->references('id')->on('cv_expected_salaries');
        });

        Schema::table('cv_expected_salaries', function (Blueprint $table) {
            $table->foreign('expected_position')->references('id')->on('positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_expected_salaries', function (Blueprint $table) {
            $table->dropForeign(['expected_position']);
        });

        Schema::table('cv_log_expected_salaries', function (Blueprint $table) {
            $table->dropForeign(['expected_salary_id']);
        });
        Schema::dropIfExists('cv_log_expected_salaries');
        Schema::dropIfExists('cv_expected_salaries');
    }
};
