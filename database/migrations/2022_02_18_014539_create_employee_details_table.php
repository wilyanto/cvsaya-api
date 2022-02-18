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
        Schema::create('employee_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('position_id')->unsigned();
            $table->integer('salary')->unsigned();
            $table->timestamps();
        });

        Schema::table('employee_details',function(Blueprint $table){
            $table->foreign('position_id')->references('id')->on('positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_details',function(Blueprint $table){
            $table->dropForeign(['position_id']);
        });

        Schema::dropIfExists('employee_details');
    }
};
