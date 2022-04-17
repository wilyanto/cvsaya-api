<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_educations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('instance');
            $table->string('field_of_study');
            $table->bigInteger('degree_id')->unsigned();
            $table->string('grade');
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('degrees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('cv_educations', function (Blueprint $table) {
            $table->foreign('degree_id')->references('id')->on('degrees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('cv_educations', function (Blueprint $table) {
            $table->dropForeign(['degree_id']);
        });

        Schema::drop('degrees');

        Schema::dropIfExists('cv_educations');
    }
}
