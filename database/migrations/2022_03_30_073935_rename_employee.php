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
            $table->dropForeign('suggest_by');
        });
        Schema::rename('candidate_employees', 'candidate');

        Schema::table('candidate', function (Blueprint $table) {
            $table->foreign('suggest_by')->references('id')->on('employee_details');
        });

        Schema::rename('candidate_employee_schedules', 'candidate_interview_schedules');
        Schema::rename('candidate_employee_schedule_character_traits', 'candidate_interview_schedules_character_traits');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate', function (Blueprint $table) {
            $table->dropForeign('suggest_by');
        });
        Schema::rename('candidate','candidate_employees');

        Schema::table('candidate_employees', function (Blueprint $table) {
            $table->foreign('suggest_by')->references('id')->on('employee_details');
        });

        Schema::rename('candidate_interview_schedules','candidate_employee_schedules');
        Schema::rename('candidate_interview_schedules_character_traits','candidate_employee_schedule_character_traits');
    }
};
