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
        Schema::create('provinces', function (Blueprint $table) {
            $table->integer('code')->length(2)->primary();
            $table->integer('country_code')->length(2);
            $table->string('name');
            $table->decimal('latitude', '20', '15')->nullable();
            $table->decimal('longitude', '20', '15')->nullable();
            $table->text('postal_code')->nullable();
            $table->timestamps();
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->foreign('country_code')->references('code')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropForeign(['country_code']);
        });
        Schema::dropIfExists('provinces');
    }
};
