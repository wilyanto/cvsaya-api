<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCvSayaLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cvsaya_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('company_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('cvsaya_positions',function(Blueprint $table){
            $table->foreign('level_id')->references('id')->on('cvsaya_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cvsaya_positions',function(Blueprint $table){
            $table->dropForeign(['level_id']);
        });

        Schema::dropIfExists('cvsaya_levels');
    }
}
