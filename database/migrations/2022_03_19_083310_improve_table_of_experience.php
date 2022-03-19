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
        Schema::table('cv_experiences',function(Blueprint $table){
            $table->longText('reason_resign')->nullable();
            $table->string('reference')->nullable();
            $table->integer('previous_salary')->default(0);
        });

        Schema::table('cv_log_experiences',function(Blueprint $table){
            $table->longText('reason_resign')->nullable();
            $table->string('reference')->nullable();
            $table->integer('previous_salary')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_experiences',function(Blueprint $table){
            $table->dropColumn('reason_resign');
            $table->dropColumn('reference');
            $table->dropColumn('previous_salary');
        });

        Schema::table('cv_log_experiences',function(Blueprint $table){
            $table->dropColumn('reason_resign');
            $table->dropColumn('reference');
            $table->dropColumn('previous_salary');
        });
    }
};
