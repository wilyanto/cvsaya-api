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
            $table->unsignedBigInteger('province_id')->nullable()->change();
            $table->unsignedBigInteger('city_id')->nullable()->change();
            $table->unsignedBigInteger('subdistrict_id')->nullable()->change();
            $table->unsignedBigInteger('village_id')->nullable()->change();
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
            $table->unsignedBigInteger('province_id')->change();
            $table->unsignedBigInteger('city_id')->change();
            $table->unsignedBigInteger('subdistrict_id')->change();
            $table->unsignedBigInteger('village_id')->change();
        });
    }
};
