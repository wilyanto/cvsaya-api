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
        Schema::create('cv_expected_jobs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('expected_salary');
            $table->bigInteger('expected_position')->unsigned()->nullable();
            $table->string('position_reason')->nullable();
            $table->longText('salary_reason');
            $table->timestamps();
        });

        Schema::create('candidate_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('validated_at')->default(null)->nullable();
            $table->timestamps();
        });

        Schema::table('cv_expected_jobs', function (Blueprint $table) {
            $table->foreign('expected_position')->references('id')->on('candidate_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_expected_jobs', function (Blueprint $table) {
            $table->dropForeign(['expected_position']);
        });

        Schema::dropIfExists('candidate_positions');

        Schema::dropIfExists('cv_expected_jobs');
    }
};
