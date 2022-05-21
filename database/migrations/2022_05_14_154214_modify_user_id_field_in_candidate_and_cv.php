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
        Schema::table('candidates', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
        Schema::table('cv_profile_details', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('candidate_id')->after('id');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
        Schema::table('cv_certifications', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('candidate_id')->after('id');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
        Schema::table('cv_documents', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('candidate_id')->after('id');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
        Schema::table('cv_domiciles', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('candidate_id')->after('id');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
        Schema::table('cv_educations', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('candidate_id')->after('id');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
        Schema::table('cv_expected_jobs', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('candidate_id')->after('id');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('candidate_id')->after('id');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
        Schema::table('cv_specialities', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('candidate_id')->after('id');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
        Schema::table('cv_social_medias', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('candidate_id')->after('id');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
        Schema::table('cv_hobbies', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('candidate_id')->after('id');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });
        Schema::table('cv_profile_details', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');
            $table->dropForeign(['candidate_id']);
            $table->dropColumn(['candidate_id']);
        });
        Schema::table('cv_certifications', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');
            $table->dropForeign(['candidate_id']);
            $table->dropColumn(['candidate_id']);
        });
        Schema::table('cv_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');
            $table->dropForeign(['candidate_id']);
            $table->dropColumn(['candidate_id']);
        });
        Schema::table('cv_domiciles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');
            $table->dropForeign(['candidate_id']);
            $table->dropColumn(['candidate_id']);
        });
        Schema::table('cv_educations', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');
            $table->dropForeign(['candidate_id']);
            $table->dropColumn(['candidate_id']);
        });
        Schema::table('cv_expected_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');
            $table->dropForeign(['candidate_id']);
            $table->dropColumn(['candidate_id']);
        });
        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');
            $table->dropForeign(['candidate_id']);
            $table->dropColumn(['candidate_id']);
        });
        Schema::table('cv_specialities', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');
            $table->dropForeign(['candidate_id']);
            $table->dropColumn(['candidate_id']);
        });
        Schema::table('cv_social_medias', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');
            $table->dropForeign(['candidate_id']);
            $table->dropColumn(['candidate_id']);
        });
        Schema::table('cv_hobbies', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');
            $table->dropForeign(['candidate_id']);
            $table->dropColumn(['candidate_id']);
        });
    }
};
