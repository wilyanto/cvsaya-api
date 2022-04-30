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
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('amount');
            $table->bigInteger('attendance_types_id')->unsigned();
            $table->foreign('attendance_types_id')->references('id')->on('attendance_types');
            $table->string('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->time('passing_at');
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
        Schema::dropIfExists('penalties');
    }
};
