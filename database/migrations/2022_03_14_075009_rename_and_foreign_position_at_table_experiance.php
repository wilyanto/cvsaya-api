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
        Schema::table('cv_experiences',function(Blueprint $table){
            $table->bigInteger('position_id')->unsigned()->nullable();
            // $table->dropColumn('position');
        });
        Schema::table('cv_experiences',function(Blueprint $table){
            $table->foreign('position_id')->references('id')->on('candidate_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_experiences',function(Blueprint $table){
            $table->dropForeign(['position_id']);
        });

        Schema::table('cv_experiences',function(Blueprint $table){
            $table->dropColumn('position_id');
            $table->string('position');
        });
    }
};
