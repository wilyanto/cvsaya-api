<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_specialities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cv_speciality_certifications',function(Blueprint $table){
            $table->id();
            $table->bigInteger('certificate_id')->unsigned();
            $table->bigInteger('speciality_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('cv_speciality_certifications',function(Blueprint $table){
            $table->foreign('certificate_id')->references('id')->on('cv_certifications');
        });

        Schema::table('cv_speciality_certifications',function(Blueprint $table){
            $table->foreign('speciality_id')->references('id')->on('cv_specialities');
        });


        Schema::create('cv_log_specialities',function(Blueprint $table){
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('specialities_id')->unsigned();
            $table->timestamp('created_at');
            $table->bigInteger('speciality_certificate_id')->unsigned()->nullable();
        });

        Schema::table('cv_log_specialities',function(Blueprint $table){
            $table->foreign('specialities_id')->references('id')->on('cv_specialities');
        });

        Schema::create('cv_log_speciality_certifications',function(Blueprint $table){
            $table->id();
            $table->bigInteger('speciality_certifications_id')->unsigned();
            $table->bigInteger('certificate_id')->unsigned()->nullable();
            $table->bigInteger('speciality_id')->unsigned()->nullable();
            $table->timestamp('created_at');
        });


        Schema::table('cv_log_speciality_certifications',function(Blueprint $table){
            $table->foreign('speciality_certifications_id','sc_id')->references('id')->on('cv_speciality_certifications');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_log_speciality_certifications',function(Blueprint $table){
            $table->dropForeign('sc_id');
        });

        Schema::dropIfExists('cv_log_speciality_certifications');


        Schema::table('cv_log_specialities',function(Blueprint $table){
            $table->dropForeign(['specialities_id']);
        });

        Schema::dropIfExists('cv_log_specialities');

        Schema::table('cv_speciality_certifications',function(Blueprint $table){
            $table->dropForeign(['certificate_id']);
        });

        Schema::table('cv_speciality_certifications',function(Blueprint $table){
            $table->dropForeign(['speciality_id']);
        });

        Schema::dropIfExists('cv_speciality_certifications');


        Schema::dropIfExists('cv_specialities');
    }
}
