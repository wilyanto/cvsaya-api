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
        Schema::create('cv_social_medias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('youtube')->nullable();
            $table->string('facebook')->nullable();
            $table->string('website_url')->nullable();
            $table->timestamps();
            // $table->softDeletes();
        });

        Schema::create('cv_log_social_medias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sosial_media_id')->unsigned();
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('youtube')->nullable();
            $table->string('facebook')->nullable();
            $table->string('website_url')->nullable();
            $table->timestamp('created_at');
        });

        Schema::table('cv_log_social_medias',function(Blueprint $table){
            $table->foreign('sosial_media_id')->references('id')->on('cv_social_medias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_log_social_medias',function(Blueprint $table){
            $table->dropForeign(['sosial_media_id']);
        });

        Schema::dropIfExists('cv_log_social_medias');

        Schema::dropIfExists('cv_social_medias');
    }
}
