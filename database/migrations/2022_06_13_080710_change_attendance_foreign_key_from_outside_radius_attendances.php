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
        Schema::table('outside_radius_attendances', function (Blueprint $table) {
            $table->dropForeign(['attendance_id']);
            $table->renameColumn('attendance_id', 'attendance_detail_id');
            $table->foreign('attendance_detail_id')->references('id')->on('attendance_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outside_radius_attendances', function (Blueprint $table) {
            $table->dropForeign(['attendance_detail_id']);
            $table->renameColumn('attendance_detail_id', 'attendance_id');
            $table->foreign('attendance_id')->references('id')->on('attendances');
        });
    }
};
