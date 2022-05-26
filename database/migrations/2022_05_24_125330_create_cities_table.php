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
        Schema::create('cities', function (Blueprint $table) {
            $table->integer('code')->length(4)->primary();
            $table->integer('province_code')->length(2);
            $table->string('name');
            $table->decimal('latitude', '20', '15')->nullable();
            $table->decimal('longitude', '20', '15')->nullable();
            $table->text('postal_codes')->nullable();
            $table->timestamps();
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->foreign('province_code')->references('code')->on('provinces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign(['province_code']);
        });
        Schema::dropIfExists('cities');
    }
};
