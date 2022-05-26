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
        Schema::table('cv_expected_jobs', function (Blueprint $table) {
            $table->integer('expected_salary')->nullable()->change();
            $table->longText('salary_reason')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_expected_jobs', function (Blueprint $table) {
            $table->integer('expected_salary')->nullable('false')->change();
            $table->longText('salary_reason')->nullable('false')->change();
        });
    }
};
