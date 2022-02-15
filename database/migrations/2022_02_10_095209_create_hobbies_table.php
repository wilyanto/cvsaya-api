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
        Schema::create('cvsaya_hobbies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cvsaya_log_hobbies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hobby_id')->unsigned();
            $table->string('name');
            $table->timestamp('created_at');
        });

        Schema::table('cvsaya_log_hobbies',function(Blueprint $table){
            $table->foreign('hobby_id')->references('id')->on('cvsaya_hobbies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cvsaya_log_hobbies',function(Blueprint $table){
            $table->dropForeign(['hobby_id']);
        });

        Schema::dropIfExists('cvsaya_log_hobbies');

        Schema::dropIfExists('cvsaya_hobbies');

    }
}
