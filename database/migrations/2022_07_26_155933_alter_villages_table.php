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
        Schema::table('villages', function (Blueprint $table) {
            $table->dropPrimary('code');
            $table->string('code')->primary()->change();
            $table->string('sub_district_code')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->dropPrimary('code');
            $table->integer('code')->length(11)->primary()->change();
            $table->integer('sub_district_code')->length(2)->change();
        });
    }
};
