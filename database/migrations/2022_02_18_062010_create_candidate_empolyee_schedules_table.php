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
        Schema::create('candidate_employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_candidate_id')->unsigned();
            $table->timestamp('date_time')->nullable();
            $table->bigInteger('interview_by')->unsigned()->nullable();
            $table->bigInteger('result')->unsigned()->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
        });

        Schema::create('candidate_log_employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_candidate_id')->unsigned();
            $table->timestamp('date_time')->nullable();
            $table->bigInteger('interview_by')->unsigned()->nullable();
            $table->bigInteger('result')->unsigned()->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
        });

        Schema::table('candidate_log_employee_schedules',function(Blueprint $table){
            $table->foreign('employee_candidate_id')->references('id')->on('candidate_employee_schedules');
        });

        Schema::table('candidate_employee_schedules',function(Blueprint $table){
            $table->foreign('employee_candidate_id')->references('id')->on('candidate_employees');
        });

        Schema::table('candidate_employee_schedules',function(Blueprint $table){
            $table->foreign('interview_by')->references('id')->on('employee_details');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_employee_schedules',function(Blueprint $table){
            $table->dropForeign(['interview_by']);
        });

        Schema::table('candidate_employee_schedules',function(Blueprint $table){
            $table->dropForeign(['employee_candidate_id']);
        });


        Schema::table('candidate_log_employee_schedules',function(Blueprint $table){
            $table->dropForeign(['employee_candidate_id']);
        });

        Schema::dropIfExists('candidate_log_employee_schedules');

        Schema::dropIfExists('candidate_employee_schedules');
    }
};
