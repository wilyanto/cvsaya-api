<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('religions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->dropColumn('religion');
            $table->bigInteger('religion_id')->unsigned()->nullable();
        });

        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->foreign('religion_id')->references('id')->on('religions');
        });

        Schema::table('cv_log_profile_details',function (Blueprint $table){
            $table->dropColumn('religion');
            $table->bigInteger('religion_id')->unsigned()->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_log_profile_details',function (Blueprint $table){
            $table->string('religion')->nullable();
            $table->dropColumn('religion_id');
        });

        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->dropForeign(['religion_id']);
        });

        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->string('religion')->nullable();
            $table->dropColumn('religion_id');
        });


        Schema::dropIfExists('religions');
    }
};
