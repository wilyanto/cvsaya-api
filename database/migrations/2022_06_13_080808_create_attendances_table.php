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
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('shift_id');
            $table->uuid('clock_in_id')->nullable();
            $table->uuid('clock_out_id')->nullable();
            $table->uuid('start_break_id')->nullable();
            $table->uuid('end_break_id')->nullable();
            $table->date('date');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->foreign('clock_in_id')->references('id')->on('attendance_details');
            $table->foreign('clock_out_id')->references('id')->on('attendance_details');
            $table->foreign('start_break_id')->references('id')->on('attendance_details');
            $table->foreign('end_break_id')->references('id')->on('attendance_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
