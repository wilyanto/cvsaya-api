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
        Schema::create('attendances_employees', function (Blueprint $table) {
            $table->id();
            $table->uuid('attendance_id');
            $table->unsignedBigInteger('employee_id');

            $table->foreign('attendance_id')->references('id')->on('attendances');
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances_employees');
    }
};
