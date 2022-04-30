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
        Schema::create('attendances_penalties', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->bigInteger('attendance_id')->unsigned()->nullable();
            $table->foreign('attendance_id')->references('id')->on('attendances');
            $table->bigInteger('penalty_id')->unsigned();
            $table->foreign('penalty_id')->references('id')->on('penalties');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances_penalties');
    }
};
