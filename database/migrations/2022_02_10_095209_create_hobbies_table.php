<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHobbiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_hobbies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cv_log_hobbies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hobby_id')->unsigned();
            $table->string('name');
            $table->timestamp('created_at');
        });

        Schema::table('cv_log_hobbies',function(Blueprint $table){
            $table->foreign('hobby_id')->references('id')->on('cv_hobbies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_log_hobbies',function(Blueprint $table){
            $table->dropForeign(['hobby_id']);
        });

        Schema::dropIfExists('cv_log_hobbies');

        Schema::dropIfExists('cv_hobbies');

    }
}
