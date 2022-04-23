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
        Schema::create('shift_positions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shift_id')->unsigned();
            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->bigInteger('position_id')->unsigned();
            $table->foreign('position_id')->references('id')->on('positions');
            $table->integer('day');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_positions');
    }
};
