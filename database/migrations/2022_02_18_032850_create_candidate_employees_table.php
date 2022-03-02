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
            $table->string('phone_number');
            $table->date('register_date')->nullable();
            $table->bigInteger('status')->unsigned();
            $table->bigInteger('suggest_by')->unsigned()->nullable();
            $table->integer('many_request')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('candidate_log_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('candidate_id')->unsigned();
            $table->string('name')->nullable();;
            $table->string('phone_number')->nullable();;
            $table->bigInteger('user_id')->nullable();;
            $table->date('register_date')->nullable();
            $table->bigInteger('status')->unsigned();
            $table->bigInteger('suggest_by')->nullable();
            $table->integer('many_request')->unsigned()->nullable();
            $table->timestamp('created_at');
        });


        Schema::table('candidate_log_employees',function(Blueprint $table){
            $table->foreign('candidate_id')->references('id')->on('candidate_employees');
        });

        Schema::table('candidate_employees',function(Blueprint $table){
            $table->foreign('suggest_by')->references('id')->on('employee_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_employees',function(Blueprint $table){
            $table->dropForeign(['suggest_by']);
        });

        Schema::table('candidate_log_employees',function(Blueprint $table){
            $table->dropForeign(['candidate_id']);
        });
        Schema::dropIfExists('candidate_log_employees');
        Schema::dropIfExists('candidate_employees');
    }
};
