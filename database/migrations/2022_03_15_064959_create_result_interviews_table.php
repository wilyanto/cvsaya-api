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
        Schema::create('result_interviews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('candidate_employee_schedules',function(Blueprint $table){
            $table->bigInteger('result_id')->unsigned()->nullable();
            $table->dropColumn('result');
        });

        Schema::table('candidate_employee_schedules',function (Blueprint $table){
            $table->foreign('result_id')->references('id')->on('result_interviews');
        });

        Schema::table('candidate_log_employee_schedules',function(Blueprint $table){
            $table->bigInteger('result_id')->unsigned()->nullable();
            $table->dropColumn('result');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_log_employee_schedules',function(Blueprint $table){
            $table->bigInteger('result')->unsigned()->nullable();
            $table->dropColumn('result_id');
        });

        Schema::table('candidate_employee_schedules',function (Blueprint $table){
            $table->dropForeign(['result_id']);
        });

        Schema::table('candidate_employee_schedules',function(Blueprint $table){
            $table->bigInteger('result')->unsigned()->nullable();
            $table->dropColumn('result_id');
        });

        Schema::dropIfExists('result_interviews');
    }
};
