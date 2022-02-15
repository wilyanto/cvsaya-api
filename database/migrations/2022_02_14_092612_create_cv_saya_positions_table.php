<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCvSayaPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cvsaya_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('department_id')->unsigned();
            $table->bigInteger('level_id')->unsigned();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('cvsaya_positions', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('cvsaya_departments');
            $table->foreign('parent_id')->references('id')->on('cvsaya_positions');
        });

        Schema::table('cvsaya_employee_details', function (Blueprint $table) {
            $table->foreign('position_id')->references('id')->on('cvsaya_positions');
        });
    }

    /**
     * Reverse the migrations.~
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cvsaya_employee_details', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
        });
        Schema::table('cvsaya_positions', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['parent_id']);
        });

        Schema::dropIfExists('cvsaya_positions');
    }
}
