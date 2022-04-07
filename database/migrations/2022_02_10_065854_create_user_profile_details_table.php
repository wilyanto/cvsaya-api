<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfileDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_profile_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('birth_location')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('reference')->nullable();
            $table->bigInteger('religion_id')->unsigned()->nullable();
            $table->bigInteger('marriage_status_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('marriage_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->foreign('marriage_status_id')->references('id')->on('marriage_statuses');
        });

        Schema::create('religions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->foreign('religion_id')->references('id')->on('religions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->dropForeign(['marriage_status_id']);
        });

        Schema::dropIfExists('marriage_statuses');

        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->dropForeign(['religion_id']);
        });

        Schema::dropIfExists('religions');

        Schema::dropIfExists('cv_profile_details');
    }
}
