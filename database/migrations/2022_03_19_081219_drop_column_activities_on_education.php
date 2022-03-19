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
        Schema::table('cv_educations',function(Blueprint $table){
            $table->dropColumn('activity');
        });

        Schema::table('cv_log_educations',function(Blueprint $table){
            $table->dropColumn('activity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_educations',function(Blueprint $table){
            $table->longtext('activity')->after('until_at');
        });

        Schema::table('cv_log_educations',function(Blueprint $table){
            $table->longtext('activity')->after('until_at');
        });

    }
};
