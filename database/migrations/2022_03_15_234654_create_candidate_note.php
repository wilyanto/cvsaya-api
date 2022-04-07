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
        Schema::create('candidate_notes', function (Blueprint $table) {
            $table->id();
            $table->longText('note');
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('candidate_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('candidate_notes', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employee_details');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_notes', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['candidate_id']);
        });

        Schema::dropIfExists('candidate_notes');
    }
};
