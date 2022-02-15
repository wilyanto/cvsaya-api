<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSosmedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cvsaya_social_medias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name');
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cvsaya_log_social_medias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sosial_media_id')->unsigned();
            $table->string('name');
            $table->string('value');
            $table->timestamp('created_at');
        });

        Schema::table('cvsaya_log_social_medias',function(Blueprint $table){
            $table->foreign('sosial_media_id')->references('id')->on('cvsaya_social_medias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cvsaya_log_social_medias',function(Blueprint $table){
            $table->dropForeign(['sosial_media_id']);
        });

        Schema::dropIfExists('cvsaya_log_social_medias');

        Schema::dropIfExists('cvsaya_social_medias');
    }
}
