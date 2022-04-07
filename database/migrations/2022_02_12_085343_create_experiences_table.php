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
            $table->bigInteger('employment_type_id')->unsigned()->nullable();
            $table->bigInteger('position_id')->unsigned()->nullable();
            $table->string('company_name');
            $table->string('company_location')->nullable();
            $table->longText('jobdesc')->nullable();
            $table->string('resign_reason');
            $table->string('reference');
            $table->integer('previouse_salary')->nullable();
            $table->uuid('payslip')->nullable();
            $table->date('start_at');
            $table->date('until_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->foreign('position_id')->references('id')->on('candidate_positions');
        });

        Schema::create('employment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('cv_experiences',function (Blueprint $table){
            $table->foreign('employment_type_id')->references('id')->on('employment_types');
        });

        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->foreign('payslip')->references('id')->on('documents');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->dropForeign(['payslip']);
        });

        Schema::table('cv_experiences',function (Blueprint $table){
            $table->dropForeign(['employment_type_id']);
        });

        Schema::dropIfExists('employment_types');

        Schema::dropIfExists('cv_experiences');
    }
}
