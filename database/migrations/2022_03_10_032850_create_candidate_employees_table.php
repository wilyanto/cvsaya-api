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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('name');
            $table->integer('country_code');
            $table->string('phone_number');
            $table->bigInteger('status')->unsigned();
            $table->bigInteger('suggested_by')->unsigned()->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->foreign('suggested_by')->references('id')->on('employee_details');
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
        Schema::dropIfExists('candidates');
    }
};
