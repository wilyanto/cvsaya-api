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
        Schema::table('attendance_penalties', function (Blueprint $table) {
            $table->dropForeign(['attendance_employee_id']);
            $table->uuid('attendance_employee_id')->change();
            $table->renameColumn('attendance_employee_id', 'attendance_id');
            $table->foreign('attendance_id')->references('id')->on('attendances');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_penalties', function (Blueprint $table) {
            $table->dropForeign(['attendance_id']);
            $table->unsignedBigInteger('attendance_id')->change();
            $table->renameColumn('attendance_id', 'attendance_employee_id');
            $table->foreign('attendance_employee_id')->references('id')->on('attendances_employees');
        });
    }
};
