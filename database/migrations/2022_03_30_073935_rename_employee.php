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
        Schema::table('candidate_employees', function (Blueprint $table) {
            $table->dropForeign(['suggest_by']);
        });
        Schema::rename('candidate_employees', 'candidates');

        Schema::table('candidates', function (Blueprint $table) {
            $table->foreign('suggest_by')->references('id')->on('employee_details');
        });

        Schema::table('candidate_employee_schedules', function (Blueprint $table) {
            $table->dropForeign(['employee_candidate_id']);
            $table->renameColumn('employee_candidate_id', 'candidate_id');
            $table->dropForeign(['interview_by']);
            $table->dropForeign(['result_id']);
        });

        Schema::rename('candidate_employee_schedules', 'candidate_interview_schedules');

        Schema::table('candidate_interview_schedules', function (Blueprint $table) {
            $table->foreign('candidate_id')->references('id')->on('candidates');
            $table->foreign('interview_by')->references('id')->on('employee_details');
            $table->foreign('result_id')->references('id')->on('interview_results');
        });

        Schema::table('candidate_employee_schedule_character_traits', function (Blueprint $table) {
            $table->dropForeign('candidate_employee_schedule');
            $table->renameColumn('candidate_employee_schedule_id', 'candidate_interview_schedule_id');
        });

        Schema::rename('candidate_employee_schedule_character_traits', 'candidate_interview_schedules_character_traits');

        Schema::table('candidate_interview_schedules_character_traits', function (Blueprint $table) {
            $table->foreign('candidate_interview_schedule_id', 'candidate_interview_schedule')->references('id')->on('candidate_interview_schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_interview_schedules_character_traits', function (Blueprint $table) {
            $table->dropForeign('candidate_interview_schedule');
        });

        Schema::rename('candidate_interview_schedules_character_traits', 'candidate_employee_schedule_character_traits');

        Schema::table('candidate_employee_schedule_character_traits', function (Blueprint $table) {
            $table->renameColumn('candidate_interview_schedule_id', 'candidate_employee_schedule_id');
            $table->foreign('candidate_employee_schedule_id', 'candidate_employee_schedule')->references('id')->on('candidate_interview_schedules');;
        });

        Schema::table('candidate_interview_schedules', function (Blueprint $table) {
            $table->dropForeign(['candidate_id']);
            $table->dropForeign(['interview_by']);
            $table->dropForeign(['result_id']);
        });

        Schema::rename('candidate_interview_schedules', 'candidate_employee_schedules');

        Schema::table('candidate_employee_schedules', function (Blueprint $table) {
            $table->renameColumn('candidate_id', 'employee_candidate_id');
            $table->foreign('employee_candidate_id')->references('id')->on('candidates');
            $table->foreign('interview_by')->references('id')->on('employee_details');
            $table->foreign('result_id')->references('id')->on('interview_results');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['suggest_by']);
        });
        Schema::rename('candidates', 'candidate_employees');

        Schema::table('candidate_employees', function (Blueprint $table) {
            $table->foreign('suggest_by')->references('id')->on('employee_details');
        });
    }
};
