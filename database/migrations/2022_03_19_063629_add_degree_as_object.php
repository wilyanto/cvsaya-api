<?php

use App\Models\CvExperience;
use App\Models\Degree;
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
        Schema::create('degree', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('cv_educations', function (Blueprint $table) {
            $table->dropColumn('degree');
            $table->bigInteger('degree_id')->unsigned()->nullable();
        });

        Schema::table('cv_educations', function (Blueprint $table) {
            $table->foreign('degree_id')->references('id')->on('degree');
        });

        Schema::table('cv_log_educations', function (Blueprint $table) {
            $table->dropColumn('degree');
            $table->bigInteger('degree_id')->unsigned()->nullable();
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
        Schema::table('cv_educations', function (Blueprint $table) {
            $table->string('degree');
            $table->dropColumn('degree_id');
        });
        Schema::table('cv_log_educations', function (Blueprint $table) {
            $table->string('degree');
            $table->dropColumn('degree_id');
        });

        Schema::drop('degree');
    }
};
