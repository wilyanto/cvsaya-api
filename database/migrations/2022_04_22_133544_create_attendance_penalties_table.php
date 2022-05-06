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
            $table->unsignedBigInteger('attendance_id')->nullable();
            $table->unsignedBigInteger('penalty_id')->unsigned();
            $table->string('penalty_name');
            $table->integer('penalty_amount');
            $table->timestamp('created_at');

            $table->foreign('attendance_id')->references('id')->on('attendances');
            $table->foreign('penalty_id')->references('id')->on('penalties');
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
