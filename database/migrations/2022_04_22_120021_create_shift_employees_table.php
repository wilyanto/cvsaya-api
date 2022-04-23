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
        Schema::create('shift_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shift_id')->unsigned();
            $table->bigInteger('employee_id')->unsigned();
            $table->timestamp('date');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('shift_id')->references('id')->on('shifts');
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
        Schema::dropIfExists('shift_employees');
    }
};
