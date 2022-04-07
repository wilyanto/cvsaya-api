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
        Schema::create('cv_documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->uuid('identity_card')->nullable();
            $table->uuid('front_selfie')->nullable();
            $table->uuid('left_selfie')->nullable();
            $table->uuid('right_selfie')->nullable();
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('user_id')->unsigned();
            $table->longText('file_name');
            $table->longText('original_file_name');
            $table->string('mime_type');
            $table->bigInteger('type_id')->unsigned();
            $table->timestamps();
        });

        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('document_types');
        });

        Schema::table('cv_documents', function (Blueprint $table) {
            $table->foreign('right_selfie')->references('id')->on('documents');
            $table->foreign('left_selfie')->references('id')->on('documents');
            $table->foreign('front_selfie')->references('id')->on('documents');
            $table->foreign('identity_card')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
        });

        Schema::table('cv_documents', function (Blueprint $table) {
            $table->dropForeign(['right_selfie']);
            $table->dropForeign(['left_selfie']);
            $table->dropForeign(['front_selfie']);
            $table->dropForeign(['identity_card']);
        });
        Schema::drop('document_types');

        Schema::drop('documents');

        Schema::dropIfExists('cv_documents');
    }
};
