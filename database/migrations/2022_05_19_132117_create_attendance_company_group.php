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
        Schema::create('attendance_company_group', function (Blueprint $table) {
            $table->id();
            $table->string('company_id')->nullable();
            $table->string('company_parent_id')->nullable();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('company_parent_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_company_group');
    }
};
