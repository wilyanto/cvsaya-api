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
        Schema::create('candidate_interview_schedules_character_traits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('candidate_interview_schedule_id')->unsigned()->nullable();
            $table->bigInteger('character_trait_id')->unsigned()->nullable();
            $table->timestamps();
        });


        Schema::table('candidate_interview_schedules_character_traits', function (Blueprint $table) {
            $table->foreign('candidate_interview_schedule_id','candidate_interview_schedule')->references('id')->on('candidate_interview_schedules');
            $table->foreign('character_trait_id','character_trait')->references('id')->on('character_traits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_interview_schedules_character_traits', function (Blueprint $table) {
            $table->dropForeign('candidate_interview_schedule');
            $table->dropForeign('character_trait');
        });

        Schema::dropIfExists('candidate_interview_schedules_character_traits');
    }
};
