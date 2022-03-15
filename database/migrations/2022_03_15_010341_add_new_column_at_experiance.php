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
            $table->longText('slip_salary_img')->nullable();
        });

        Schema::table('cv_log_experiences',function(Blueprint $table){
            $table->longText('slip_salary_img')->nullable();
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
            $table->dropColumn('slip_salary_img');
        });

        Schema::table('cv_experiences',function(Blueprint $table){
            $table->dropColumn('slip_salary_img');
        });
    }
};
