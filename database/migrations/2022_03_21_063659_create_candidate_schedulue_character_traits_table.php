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
        Schema::create('candidate_employee_schedule_character_traits', function (Blueprint $table) {
            $table->bigInteger('candidate_employee_schedule_id')->unsigned()->nullable();
            $table->bigInteger('character_trait_id')->unsigned()->nullable();
            $table->timestamps();
        });


        Schema::table('candidate_employee_schedule_character_traits', function (Blueprint $table) {
            $table->foreign('candidate_employee_schedule_id','candidate_employee_schedule')->references('id')->on('candidate_employee_schedules');
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
        Schema::table('candidate_employee_schedule_character_traits', function (Blueprint $table) {
            $table->dropForeign('candidate_employee_schedule');
            $table->dropForeign('character_trait');
        });

        Schema::dropIfExists('candidate_employee_schedule_character_traits');
    }
};
