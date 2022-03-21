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
            $table->string('company_location')->nullable()->after('user_id');
            $table->string('company_name')->after('user_id');
            $table->dropColumn('location');
        });

        Schema::table('cv_log_experiences',function(Blueprint $table){
            $table->string('company_location')->nullable()->after('experience_id');
            $table->string('company_name')->after('experience_id');
            $table->dropColumn('location');
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
            $table->dropColumn('company_name');
            $table->dropColumn('company_location');
            $table->string('location')->after('user_id');
        });

        Schema::table('cv_log_experiences',function(Blueprint $table){
            $table->dropColumn('company_name');
            $table->dropColumn('company_location');
            $table->string('location')->after('experience_id');
        });
    }
};
