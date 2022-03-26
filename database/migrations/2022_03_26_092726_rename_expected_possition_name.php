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
        Schema::table('cv_log_expected_positions', function (Blueprint $table) {
            $table->dropForeign(['expected_salary_id']);
            $table->renameColumn('expected_salary_id', 'expected_jobs_id');
        });

        Schema::table('cv_expected_positions', function (Blueprint $table) {
            $table->dropForeign(['expected_position']);
        });

        Schema::rename('cv_expected_positions', 'cv_expected_jobs');

        Schema::rename('cv_log_expected_positions', 'cv_log_expected_jobs');

        Schema::table('cv_log_expected_jobs', function (Blueprint $table) {
            $table->foreign('expected_jobs_id')->references('id')->on('cv_expected_jobs');
        });

        Schema::table('cv_expected_jobs',function(Blueprint $table){
            $table->foreign('expected_position')->references('id')->on('candidate_positions');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_log_expected_jobs', function (Blueprint $table) {
            $table->dropForeign(['expected_jobs_id']);
            $table->renameColumn('expected_jobs_id', 'expected_salary_id');
        });

        Schema::table('cv_expected_jobs', function (Blueprint $table) {
            $table->dropForeign(['expected_position']);
        });

        Schema::rename('cv_expected_jobs', 'cv_expected_positions');

        Schema::rename('cv_log_expected_jobs','cv_log_expected_positions');

        Schema::table('cv_log_expected_positions', function (Blueprint $table) {
            $table->foreign('expected_salary_id')->references('id')->on('cv_expected_positions');
        });

        Schema::table('cv_expected_positions',function(Blueprint $table){
            $table->foreign('expected_position')->references('id')->on('candidate_positions');
        });
    }
};
