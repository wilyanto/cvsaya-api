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
        Schema::table('cv_domiciles', function (Blueprint $table) {
            $table->string('country_id')->change();
            $table->string('province_id')->change();
            $table->string('city_id')->change();
            $table->string('subdistrict_id')->change();
            $table->string('village_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_domiciles', function (Blueprint $table) {
            $table->bigInteger('country_id')->unsigned()->change();
            $table->bigInteger('province_id')->unsigned()->change();
            $table->bigInteger('city_id')->unsigned()->change();
            $table->bigInteger('subdistrict_id')->unsigned()->change();
            $table->bigInteger('village_id')->unsigned()->change();
        });
    }
};
