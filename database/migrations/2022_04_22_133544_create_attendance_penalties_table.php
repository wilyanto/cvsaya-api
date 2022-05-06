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
        Schema::create('attendance_penalties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_employee_id');
            $table->unsignedBigInteger('penalty_id');
            $table->string('penalty_name');
            $table->integer('penalty_amount');
            $table->timestamp('created_at');

            $table->foreign('attendance_employee_id')->references('id')->on('attendances_employees');
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
        Schema::dropIfExists('attendance_penalties');
    }
};
