<?php

use App\Models\Candidate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_employee_schedules', function (Blueprint $table) {
            $table->renameColumn('date_time', 'interview_at');
        });

        Schema::table('candidate_log_employee_schedules', function (Blueprint $table) {
            $table->renameColumn('date_time', 'interview_at');
        });

        Schema::table('candidate_employees', function (Blueprint $table) {
            $table->integer('country_code')->default(62);
            $table->timestamp('register_at')->nullable();
        });

        Schema::table('candidate_employees', function (Blueprint $table) {
            $table->integer('country_code')->default(null)->change();
        });

        $candidates =  Candidate::all();
        foreach ($candidates as $candidate) {
            $candidate->register_at = date('Y-m-d H:i:s', strtotime($candidate->register_date));
            $candidate->save();
        }

        Schema::table('candidate_employees', function (Blueprint $table) {
            $table->dropColumn('register_date');
        });

        Schema::table('cv_expected_positions', function (Blueprint $table) {
            $table->renameColumn('reason_position','position_reason');
            $table->renameColumn('reason_salary','salary_reason');
            $table->renameColumn('expected_amount','expected_salary');
        });

        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->renameColumn('reason_resign','resign_reason');
        });

        Schema::table('cv_log_expected_positions', function (Blueprint $table) {
            $table->renameColumn('reason_position','position_reason');
            $table->renameColumn('reason_salary','salary_reason');
            $table->renameColumn('expected_amount','expected_salary');
        });

        Schema::table('cv_log_experiences', function (Blueprint $table) {
            $table->renameColumn('reason_resign','resign_reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_employee_schedules', function (Blueprint $table) {
            $table->renameColumn('interview_at', 'date_time');
        });

        Schema::table('candidate_log_employee_schedules', function (Blueprint $table) {
            $table->renameColumn('interview_at', 'date_time');
        });

        Schema::table('candidate_employees', function (Blueprint $table) {
            $table->date('register_date')->nullable();
        });

        $candidates =  Candidate::all();
        foreach ($candidates as $candidate) {
            $candidate->register_date = date('Y-m-d', strtotime($candidate->register_at));
            $candidate->save();
        }

        Schema::table('candidate_employees', function (Blueprint $table) {
            $table->dropColumn('register_at');
            $table->dropColumn('country_code');
        });

        Schema::table('cv_expected_positions', function (Blueprint $table) {
            $table->renameColumn('position_reason','reason_position');
            $table->renameColumn('salary_reason','reason_salary');
            $table->renameColumn('expected_salary','expected_amount');
        });

        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->renameColumn('resign_reason','reason_resign');
        });

        Schema::table('cv_log_expected_positions', function (Blueprint $table) {
            $table->renameColumn('position_reason','reason_position');
            $table->renameColumn('salary_reason','reason_salary');
            $table->renameColumn('expected_salary','expected_amount');
        });

        Schema::table('cv_log_experiences', function (Blueprint $table) {
            $table->renameColumn('resign_reason','reason_resign');
        });
    }
};
