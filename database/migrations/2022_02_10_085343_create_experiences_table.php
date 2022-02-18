<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_experiences', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('position');
            $table->string('employment_type');
            $table->longText('location');
            $table->date('start_at');
            $table->date('until_at')->nullable();
            $table->longText('description')->nullable();
            $table->string('media')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cv_log_experiences', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('experience_id')->unsigned();
            $table->string('position')->nullable();
            $table->string('employment_type')->nullable();
            $table->longText('location')->nullable();
            $table->date('start_at')->nullable();
            $table->date('until_at')->nullable();
            $table->longText('description')->nullable();
            $table->string('media')->nullable();
            $table->timestamp('created_at');
        });

        Schema::table('cv_log_experiences',function(Blueprint $table){
            $table->foreign('experience_id')->references('id')->on('cv_experiences');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_log_experiences',function(Blueprint $table){
            $table->dropForeign(['experience_id']);
        });

        Schema::dropIfExists('cv_log_experiences');

        Schema::dropIfExists('cv_experiences');
    }
}
