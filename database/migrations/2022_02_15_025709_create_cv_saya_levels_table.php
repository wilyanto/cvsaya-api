w<?php

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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_id')->unique()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('positions',function(Blueprint $table){
            $table->foreign('level_id')->references('id')->on('levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('positions',function(Blueprint $table){
            $table->dropForeign(['level_id']);
        });

        Schema::dropIfExists('levels');
    }
}
