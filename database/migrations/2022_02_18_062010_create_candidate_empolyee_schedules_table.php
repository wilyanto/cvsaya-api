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
        Schema::create('candidate_empolyee_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('empolyee_candidate_id')->unsigned();
            $table->timestamp('date_time')->nullable();
            $table->bigInteger('interview_by')->unsigned()->nullable();
            $table->bigInteger('result_id')->unsigned()->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
        });

        Schema::create('candidate_log_empolyee_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('empolyee_candidate_id')->unsigned();
            $table->timestamp('date_time')->nullable();
            $table->bigInteger('interview_by')->unsigned()->nullable();
            $table->bigInteger('result_id')->unsigned()->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
        });

        Schema::table('candidate_log_empolyee_schedules',function(Blueprint $table){
            $table->foreign('empolyee_candidate_id')->references('id')->on('candidate_empolyee_schedules');
        });

        Schema::create('candidate_result',function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('candidate_empolyee_schedules',function(Blueprint $table){
            $table->foreign('empolyee_candidate_id')->references('id')->on('candidate_empolyee_schedules');
        });

        Schema::table('candidate_empolyee_schedules',function(Blueprint $table){
            $table->foreign('interview_by')->references('id')->on('employee_details');
        });

        Schema::table('candidate_empolyee_schedules',function(Blueprint $table){
            $table->foreign('result_id')->references('id')->on('candidate_result');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_empolyee_schedules',function(Blueprint $table){
            $table->dropForeign(['interview_by']);
        });

        Schema::table('candidate_empolyee_schedules',function(Blueprint $table){
            $table->dropForeign(['empolyee_candidate_id']);
        });

        Schema::table('candidate_empolyee_schedules',function(Blueprint $table){
            $table->dropForeign(['result_id']);
        });

        Schema::dropIfExists('candidate_result');

        Schema::table('candidate_log_empolyee_schedules',function(Blueprint $table){
            $table->dropForeign(['empolyee_candidate_id']);
        });

        Schema::dropIfExists('candidate_log_empolyee_schedules');

        Schema::dropIfExists('candidate_empolyee_schedules');
    }
};
