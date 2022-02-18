<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfileDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_profile_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('first_name');
            $table->string('last_name');
            $table->longText('about')->nullable();
            $table->string('website_url')->nullable();
            $table->json('selfie_about')->nullable();
            $table->string('religion')->nullable();
            $table->string('reference')->nullable();
            $table->string('identity_number')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_location')->nullable();
            $table->bigInteger('address_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cv_profile_details');
    }
}
