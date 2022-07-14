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
        Schema::table('salary_types', function (Blueprint $table) {
            $table->string('code')->after('name');
            $table->enum('type', ['allowance', 'deduction'])->after('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_types', function (Blueprint $table) {
            $table->dropColumn(['code', 'type']);
        });
    }
};
