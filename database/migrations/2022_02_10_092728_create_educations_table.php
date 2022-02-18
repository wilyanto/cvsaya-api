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
            $table->string('school');
            $table->string('degree');
            $table->string('field_of_study');
            $table->string('grade');
            $table->date('start_at');
            $table->date('until_at')->nullable();
            $table->longText('activity')->nullable();
            $table->longText('description')->nullable();
            $table->string('media')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cv_log_educations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('education_id')->unsigned();
            $table->string('school')->nullable();
            $table->string('degree')->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('grade')->nullable();
            $table->date('start_at')->nullable();
            $table->date('until_at')->nullable();
            $table->longText('activity')->nullable();
            $table->longText('description')->nullable();
            $table->string('media')->nullable();
            $table->timestamp('created_at');
        });

        Schema::table('cv_log_educations',function(Blueprint $table){
            $table->foreign('education_id')->references('id')->on('cv_educations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_log_educations',function(Blueprint $table){
            $table->dropForeign(['education_id']);
        });

        Schema::dropIfExists('cv_log_educations');

        Schema::dropIfExists('cv_educations');
    }
}
