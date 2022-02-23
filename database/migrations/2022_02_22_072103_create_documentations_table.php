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
        Schema::create('cv_documentations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->longText('identity_picture_card')->nullable();
            $table->longText('selfie_front')->nullable();
            $table->longText('selfie_left')->nullable();
            $table->longText('selfie_right')->nullable();
            $table->longText('mirrage_certificate')->nullable();
            $table->timestamps();
        });

        Schema::create('cv_log_documentations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('document_id')->unsigned();
            $table->longText('identity_picture_card')->nullable();
            $table->longText('selfie_front')->nullable();
            $table->longText('selfie_left')->nullable();
            $table->longText('selfie_right')->nullable();
            $table->longText('mirrage_certificate')->nullable();
            $table->timestamp('created_at');
        });

        Schema::table('cv_log_documentations', function (Blueprint $table){
            $table->foreign('document_id')->references('id')->on('cv_documentations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_log_documentations', function (Blueprint $table){
            $table->dropForeign(['document_id']);
        });
        Schema::dropIfExists('cv_log_documentations');
        Schema::dropIfExists('cv_documentations');
    }
};
