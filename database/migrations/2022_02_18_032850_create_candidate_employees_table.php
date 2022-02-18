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
        Schema::create('candidate_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('name');
            $table->integer('country_code');
            $table->integer('phone_number');
            $table->date('register_date');
            $table->integer('filled_form')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('candidate_log_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('candidate_id')->unsigned();
            $table->string('name');
            $table->integer('country_code');
            $table->integer('phone_number');
            $table->bigInteger('user_id');
            $table->date('register_date');
            $table->integer('filled_form')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('candidate_log_employees',function(Blueprint $table){
            $table->foreign('candidate_id')->references('id')->on('candidate_employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_log_employees',function(Blueprint $table){
            $table->dropForeign(['candidate_id']);
        });
        Schema::dropIfExists('candidate_log_employees');
        Schema::dropIfExists('candidate_employees');
    }
};
