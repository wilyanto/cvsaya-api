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
        Schema::create('sub_districts', function (Blueprint $table) {
            $table->integer('code')->length(4)->primary();
            $table->integer('city_code')->length(2);
            $table->string('name');
            $table->decimal('latitude', '20', '15')->nullable();
            $table->decimal('longitude', '20', '15')->nullable();
            $table->text('postal_codes')->nullable();
            $table->timestamps();
        });

        Schema::table('sub_districts', function (Blueprint $table) {
            $table->foreign('city_code')->references('code')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_districts', function (Blueprint $table) {
            $table->dropForeign(['city_code']);
        });
        Schema::dropIfExists('sub_districts');
    }
};
