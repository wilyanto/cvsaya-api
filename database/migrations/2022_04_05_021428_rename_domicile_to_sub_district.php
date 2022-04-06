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
        Schema::table('cv_domiciles', function (Blueprint $table) {
            $table->renameColumn('district_id', 'sub_district_id');
        });

        Schema::table('cv_log_domiciles', function (Blueprint $table) {
            $table->renameColumn('district_id', 'sub_district_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_domiciles', function (Blueprint $table) {
            $table->renameColumn('sub_district_id', 'district_id');
        });

        Schema::table('cv_log_domiciles', function (Blueprint $table) {
            $table->renameColumn('sub_district_id', 'district_id');
        });
    }
};
