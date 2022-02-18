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
            $table->longText('about')->nullable();
            $table->string('website_url')->nullable();
            $table->json('selfie_about')->nullable();
            $table->string('religion')->nullable();
            $table->string('reference')->nullable();
            $table->string('identity_number')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('location_birth')->nullable();
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
