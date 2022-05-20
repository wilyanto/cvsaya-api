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
        Schema::table('attendance_qr_codes', function (Blueprint $table) {
            $table->string('company_id')->after('is_geo_strict')->nullable();

            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_qr_codes', function (Blueprint $table) {
            $table->dropForeign(['company_id']);

            $table->dropColumn('company_id');
        });
    }
};
