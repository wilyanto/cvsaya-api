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
        Schema::table('employees_salary_types', function (Blueprint $table) {
            $table->enum('type', ['hourly', 'daily', 'monthly'])->after('salary_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees_salary_types', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
