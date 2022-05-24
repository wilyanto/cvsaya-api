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
        Schema::create('villages', function (Blueprint $table) {
            $table->integer('code')->length(11)->primary();
            $table->integer('sub_district_code')->length(2);
            $table->string('name');
            $table->decimal('latitude', '20', '15')->nullable();
            $table->decimal('longitude', '20', '15')->nullable();
            $table->text('postal_codes')->nullable();
            $table->timestamps();
        });

        Schema::table('villages', function (Blueprint $table) {
            $table->foreign('sub_district_code')->references('code')->on('sub_districts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->dropForeign(['sub_district_code']);
        });
        Schema::dropIfExists('villages');
    }
};
