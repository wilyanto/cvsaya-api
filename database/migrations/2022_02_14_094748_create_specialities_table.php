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

        Schema::create('cv_specialities_certifications',function(Blueprint $table){
            $table->id();
            $table->bigInteger('certificate_id')->unsigned();
            $table->bigInteger('speciality_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('cv_specialities_certifications',function(Blueprint $table){
            $table->foreign('certificate_id')->references('id')->on('cv_certifications');
        });

        Schema::table('cv_specialities_certifications',function(Blueprint $table){
            $table->foreign('speciality_id')->references('id')->on('cv_specialities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_specialities_certifications',function(Blueprint $table){
            $table->dropForeign(['certificate_id']);
        });

        Schema::table('cv_specialities_certifications',function(Blueprint $table){
            $table->dropForeign(['speciality_id']);
        });

        Schema::dropIfExists('cv_specialities_certifications');


        Schema::dropIfExists('cv_specialities');
    }
}
