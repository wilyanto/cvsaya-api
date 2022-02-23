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
        Schema::create('cv_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('country_id')->unsigned();
            $table->bigInteger('province_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->bigInteger('district_id')->unsigned();
            $table->bigInteger('village_id')->unsigned();
            $table->string('detail')->nullable();
            $table->timestamps();
        });

        Schema::create('cv_log_addresses', function(Blueprint $table){
            $table->id();
            $table->bigInteger('address_id')->unsigned()->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->bigInteger('province_id')->unsigned()->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->bigInteger('district_id')->unsigned()->nullable();
            $table->bigInteger('village_id')->unsigned();
            $table->string('detail')->nullable();
            $table->timestamp('created_at');
        });

        Schema::table('cv_log_addresses', function(Blueprint $table){
            $table->foreign('address_id')->references('id')->on('cv_addresses');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('cv_log_addresses', function(Blueprint $table){
            $table->dropForeign(['address_id']);
        });

        Schema::dropIfExists('cv_log_addresses');

        Schema::dropIfExists('cv_addresses');
    }
};
