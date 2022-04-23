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
        Schema::table('employee_details', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['suggested_by']);
        });

        Schema::table('candidate_interview_schedules', function (Blueprint $table) {
            $table->dropForeign(['interviewed_by']);
        });

        Schema::table('candidate_notes', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });

        Schema::rename('employee_details', 'employees');

        Schema::table('employees', function (Blueprint $table) {
            $table->foreign(['position_id'])->references('id')->on('positions');;
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->foreign(['suggested_by'])->references('id')->on('employees');;;
        });

        Schema::table('candidate_interview_schedules', function (Blueprint $table) {
            $table->foreign(['interviewed_by'])->references('id')->on('employees');;;
        });

        Schema::table('candidate_notes', function (Blueprint $table) {
            $table->foreign(['employee_id'])->references('id')->on('employees');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['suggested_by']);
        });

        Schema::table('candidate_interview_schedules', function (Blueprint $table) {
            $table->dropForeign(['interviewed_by']);
        });

        Schema::table('candidate_notes', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });

        Schema::rename( 'employees','employee_details');

        Schema::table('employee_details', function (Blueprint $table) {
            $table->foreign(['position_id'])->references('id')->on('positions');;
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->foreign(['suggested_by'])->references('id')->on('employee_details');;;
        });

        Schema::table('candidate_interview_schedules', function (Blueprint $table) {
            $table->foreign(['interviewed_by'])->references('id')->on('employee_details');;;
        });

        Schema::table('candidate_notes', function (Blueprint $table) {
            $table->foreign(['employee_id'])->references('id')->on('employee_details');;
        });
    }
};
