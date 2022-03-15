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
        Schema::create('marriage_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->dropColumn('married');
            $table->bigInteger('marriage_status_id')->unsigned()->nullable();
        });

        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->foreign('marriage_status_id')->references('id')->on('marriage_statuses');
        });

        Schema::table('cv_log_profile_details',function (Blueprint $table){
            $table->dropColumn('married');
            $table->bigInteger('marriage_status_id')->unsigned()->nullable();
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
            $table->string('married')->nullable();
            $table->dropColumn('marriage_status_id');
        });

        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->dropForeign(['marriage_status_id']);
        });

        Schema::table('cv_profile_details',function (Blueprint $table){
            $table->string('married')->nullable();
            $table->dropColumn('marriage_status_id');
        });

        Schema::dropIfExists('marriage_statuses');
    }
};
