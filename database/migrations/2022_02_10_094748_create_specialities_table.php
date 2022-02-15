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
        Schema::create('cvsaya_specialities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cvsaya_speciality_certifications',function(Blueprint $table){
            $table->id();
            $table->bigInteger('certificate_id')->unsigned();
            $table->bigInteger('speciality_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('cvsaya_speciality_certifications',function(Blueprint $table){
            $table->foreign('certificate_id')->references('id')->on('cvsaya_certifications');
        });

        Schema::table('cvsaya_speciality_certifications',function(Blueprint $table){
            $table->foreign('speciality_id')->references('id')->on('cvsaya_specialities');
        });


        Schema::create('cvsaya_log_specialities',function(Blueprint $table){
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('specialities_id')->unsigned();
            $table->timestamp('created_at');
            $table->bigInteger('speciality_certificate_id')->unsigned()->nullable();
        });

        Schema::table('cvsaya_log_specialities',function(Blueprint $table){
            $table->foreign('specialities_id')->references('id')->on('cvsaya_specialities');
        });

        Schema::create('cvsaya_log_speciality_certifications',function(Blueprint $table){
            $table->id();
            $table->bigInteger('speciality_certifications_id')->unsigned();
            $table->bigInteger('certificate_id')->unsigned()->nullable();
            $table->bigInteger('speciality_id')->unsigned()->nullable();
            $table->timestamp('created_at');
        });


        Schema::table('cvsaya_log_speciality_certifications',function(Blueprint $table){
            $table->foreign('speciality_certifications_id','sc_id')->references('id')->on('cvsaya_speciality_certifications');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cvsaya_log_speciality_certifications',function(Blueprint $table){
            $table->dropForeign('sc_id');
        });

        Schema::dropIfExists('cvsaya_log_speciality_certifications');


        Schema::table('cvsaya_log_specialities',function(Blueprint $table){
            $table->dropForeign(['specialities_id']);
        });

        Schema::dropIfExists('cvsaya_log_specialities');

        Schema::table('cvsaya_speciality_certifications',function(Blueprint $table){
            $table->dropForeign(['certificate_id']);
        });

        Schema::table('cvsaya_speciality_certifications',function(Blueprint $table){
            $table->dropForeign(['speciality_id']);
        });

        Schema::dropIfExists('cvsaya_speciality_certifications');


        Schema::dropIfExists('cvsaya_specialities');
    }
}
