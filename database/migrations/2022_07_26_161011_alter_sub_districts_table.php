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
        Schema::table('sub_districts', function (Blueprint $table) {
            $table->dropPrimary('code');
            $table->string('code')->primary()->change();
            $table->string('city_code')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_districts', function (Blueprint $table) {
            $table->dropPrimary('code');
            $table->integer('code')->length(4)->primary()->change();
            $table->integer('city_code')->length(2)->change();
        });
    }
};
