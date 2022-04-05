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
        Schema::table('candidate_log_interview_schedules',function (Blueprint $table){
            $table->dropForeign('candidate_log_employee_schedules_employee_candidate_id_foreign');
        });

        Schema::table('candidate_log_interview_schedules',function (Blueprint $table){
            $table->bigInteger('candidate_interview_schedules_id')->unsigned()->nullable()->after('id');
        });

        Schema::table('candidate_log_interview_schedules',function (Blueprint $table){
            $table->dropColumn('candidate_id')->unsigned()->nullable();
        });

        Schema::table('candidate_log_interview_schedules',function (Blueprint $table){
            $table->foreign('candidate_interview_schedules_id','candidate_log_interview_schedules')->references('id')->on('candidate_interview_schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_log_interview_schedules',function (Blueprint $table){
            $table->dropForeign('candidate_log_interview_schedules');
        });

        Schema::table('candidate_log_interview_schedules',function (Blueprint $table){
            $table->dropColumn('candidate_interview_schedules_id');
        });

        Schema::table('candidate_log_interview_schedules',function (Blueprint $table){
            $table->bigInteger('candidate_id')->unsigned()->nullable()->after('id');
        });

        Schema::table('candidate_log_interview_schedules',function (Blueprint $table){
            $table->foreign('candidate_id','candidate_log_employee_schedules_employee_candidate_id_foreign')->references('id')->on('candidates');
        });

    }
};
