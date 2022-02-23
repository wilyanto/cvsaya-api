<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogUserProfileDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_log_profile_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('profile_detail_id')->unsigned();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('birth_location')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('religion')->nullable();
            $table->string('married')->nullable();
            $table->timestamp('created_at');
        });

        Schema::table('cv_log_profile_details',function(Blueprint $table){
            $table->foreign('profile_detail_id')->references('id')->on('cv_profile_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_log_profile_details',function(Blueprint $table){
            // $table->dropForeign(['position_id']);
            $table->dropForeign(['profile_detail_id']);
        });

        Schema::dropIfExists('cv_log_profile_details');
    }
}
