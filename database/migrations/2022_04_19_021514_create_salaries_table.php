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
        Schema::create('salary_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_id');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::create('employees_salary_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->foreign(['employee_id'])->references('id')->on('employees');
            $table->bigInteger('salary_type_id')->unsigned()->nullable();
            $table->foreign(['salary_type_id'])->references('id')->on('salary_types');
            $table->integer('amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees_salary_types');
        Schema::dropIfExists('salary_types');
    }
};