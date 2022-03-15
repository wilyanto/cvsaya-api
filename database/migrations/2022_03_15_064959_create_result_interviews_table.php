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
        Schema::create('result_interviews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('candidate_employee_schedules',function (Blueprint $table){
            $table->foreign('result')->references('id')->on('result_interviews');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_employee_schedules',function (Blueprint $table){
            $table->dropForeign(['result']);
        });

        Schema::dropIfExists('result_interviews');
    }
};
