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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('employees_salaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->bigInteger('salary_type_id')->unsigned()->nullable();
            $table->integer('amount');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('salaries');
        Schema::drop('employees_salaries');
    }
};
