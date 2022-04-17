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
        Schema::create('interview_results', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('candidate_interview_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('candidate_id')->unsigned();
            $table->timestamp('interviewed_at')->nullable();
            $table->bigInteger('interviewed_by')->unsigned()->nullable();
            $table->bigInteger('result_id')->unsigned()->nullable();
            $table->longText('note')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });

        Schema::table('candidate_interview_schedules',function (Blueprint $table){
            $table->foreign('result_id')->references('id')->on('interview_results');
        });

        Schema::table('candidate_interview_schedules', function (Blueprint $table) {
            $table->foreign('candidate_id')->references('id')->on('candidates');
            $table->foreign('interviewed_by')->references('id')->on('employee_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_interview_schedules');

        Schema::dropIfExists('interview_results');
    }
};
